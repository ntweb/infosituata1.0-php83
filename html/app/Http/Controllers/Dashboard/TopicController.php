<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Gruppo;
use App\Models\Pivot\MessaggioTopic;
use App\Models\Pivot\MessaggioUtente;
use App\Models\Sede;
use App\Models\Topic;
use App\Models\Utente;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;

class TopicController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Topic::with(['user']);

        if (\auth()->user()->utente_id)
            $query = $query->leftJoin('messaggio_utente', 'messaggi.id', '=', 'messaggio_utente.messaggio_id')
                ->where('messaggio_utente.utente_id', Auth::user()->utente_id);
        else
            $query = $query->where('user_id', \auth()->user()->id);

        if ($request->input('_search', null)) {

            $search = explode(' ', $request->input('_search'));

            $query = $query->where(function($q) use($search) {
                foreach ($search as $tag) {
                    if ($tag !== '') {
                        $q->where('oggetto', 'LIKE', '%'.$tag.'%');
                    }
                }
            });

        }

        $query = $query->select('messaggi.*');
        $data['list'] = $query->orderBy('sent_at', 'desc')->paginate(50)->appends(request()->query());

        return view('dashboard.topic.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!Gate::allows('can_create_topic'))
            abort(401);

        $data = [];
        return view('dashboard.topic.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validationRules = [
            'oggetto' => 'required',
        ];

        $validatedData = $request->validate($validationRules);

        $el = new Topic();
        $fields = $request->except(['_token', '_redirect', '_method', '_type', '_module', '_item_id']);
        foreach ($fields as $k => $v) {
            $el->$k = $v;
        }

        DB::beginTransaction();
        try {

            $el->module = 'topic';
            $el->azienda_id = getAziendaId();
            $el->user_id = \auth()->user()->id;

            $el->save();

            if (\auth()->user()->utente_id) {
                MessaggioUtente::firstOrCreate([
                    'messaggio_id' => $el->id,
                    'utente_id' => \auth()->user()->utente_id
                ]);
            }


            DB::commit();

            return redirect()->route('topic.edit', [$el->id])->with('success', 'Salvataggio avvenuto correttamente!');
        }catch (\Exception $e) {
            DB::rollBack();
            Log::info($e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Errore in fase di salvataggio! '.$e->getMessage());
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $el = Topic::with('utenti')->find($id);
        if (!$el) abort('404');

        $data['el'] = $el;
        $data['gruppi'] = Gruppo::whereAziendaId($el->azienda_id)->orderBy('label')->get()->pluck('label', 'id');
        $data['gruppiSel'] = array_flip(explode(',', $el->gruppi_ids));

        $data['sedi'] = Sede::whereAziendaId($el->azienda_id)->orderBy('label')->get()->pluck('label', 'id');
        $data['sediSel'] = array_flip(explode(',', $el->sedi_ids));

        $data['utenti'] = Utente::with('user')->orderBy('extras1')->get();
        $data['utenti'] = $data['utenti']->filter(function ($value, $key) {
            return $value->user->active == '1';
        })->mapWithKeys(function ($item) {
            return [$item->id => $item->extras1.' '.$item->extras2];
        });

        $data['utentiSel'] = array_flip(explode(',', $el->utenti_ids));

        if (\auth()->user()->id == $el->user_id || isset(auth()->user()->id)) {
            DB::table('messaggio_topic')
                ->where('users_id', \auth()->user()->id)
                ->where('messaggio_id', $id)
                ->update([
                    'opened_at' => \Carbon\Carbon::now()
                ]);

            $utente_id = getUtenteIdBySessionUser();
            if ($utente_id) {
                DB::table('messaggio_topic_notify')
                    ->where('messaggio_id', $id)
                    ->where('utente_id', $utente_id)
                    ->delete();
            }

            return view('dashboard.topic.create', $data);
        }

        abort(401);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $el = Topic::find($id);
        if (!$el) abort('404');

        $validationRules = [
            'oggetto' => 'required'
        ];

        $validatedData = $request->validate($validationRules);

        $fields = $request->except(['_token', '_redirect', '_method', '_type', '_module']);
        foreach ($fields as $k => $v) {
            switch ($k) {
                case 'sedi_ids':
                case 'gruppi_ids':
                case 'utenti_ids':
                    $el->$k = join(',', $v);
                    break;
                default:
                    $el->$k = $v;
            }
        }

        DB::beginTransaction();
        try {

            $azienda_id = (Auth::user()->superadmin && $request->has('azienda_id')) ? $request->get('azienda_id') : getAziendaId($el);
            $el->azienda_id = $azienda_id ? $azienda_id : $el->azienda_id;
            $el->user_id = Auth::user()->id;
            $el->save();

            // salvo i vari destinatari
            // sedi
            $utenti_ids = [];
            if ($el->sedi_ids) {
                $sedi_ids = explode(',', $el->sedi_ids);
                $utentiIds = DB::table('sede_item')
                    ->leftJoin('items', 'sede_item.item_id', '=', 'items.id')
                    ->whereController('utente')
                    ->whereIn('sede_id', $sedi_ids)->get()->pluck('item_id');
                // Log::info($utentiIds);
                foreach ($utentiIds as $utente_id) {
                    $utenti_ids[$utente_id] = $utente_id;
                    MessaggioUtente::firstOrCreate([
                        'messaggio_id' => $id,
                        'utente_id' => $utente_id
                    ]);
                }
            }

            // gruppi
            if ($el->gruppi_ids) {
                $gruppi_ids = explode(',', $el->gruppi_ids);
                $utentiIds = DB::table('gruppo_utente')->whereIn('gruppo_id', $gruppi_ids)->get()->pluck('utente_id');
                // Log::info($utentiIds);
                foreach ($utentiIds as $utente_id) {
                    MessaggioUtente::firstOrCreate([
                        'messaggio_id' => $id,
                        'utente_id' => $utente_id
                    ]);
                }
            }

            if ($el->utenti_ids) {
                $utentiIds = explode(',', $el->utenti_ids);
                // Log::info($utentiIds);
                foreach ($utentiIds as $utente_id) {
                    MessaggioUtente::firstOrCreate([
                        'messaggio_id' => $id,
                        'utente_id' => $utente_id
                    ]);
                }
            }

            $el->sent_at = \Carbon\Carbon::now();
            $el->save();

            DB::commit();

            $payload = 'Salvataggio avvenuto correttamente!';
            if ($request->get('_type') == 'json')
                return response()->json(['res' => 'success','payload' => $payload ]);

            return redirect()->back()->with('success', 'Salvataggio avvenuto correttamente!');

        }catch (\Exception $e) {
            DB::rollBack();

            Log::info($e->getMessage());
            $payload = 'Errore in fase di salvataggio!';
            if ($request->get('_type') == 'json')
                return response()->json(['res' => 'error', 'payload' => $payload]);

            return redirect()->back()->withInput()->with('error', $payload);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        if(!$request->has('confirm'))
            return redirect()->back()->withInput()->with('error', 'E\' necessario confermare la cancellazione!');

        $el = Topic::find($id);
        if (!$el) abort(404);

        DB::beginTransaction();
        try {

            // $el->delete();
            DB::table('messaggio_topic')->where('messaggio_id', $id)->delete();
            DB::table('messaggi')->where('id', $id)->delete();

            DB::commit();
            return redirect()->to($request->get('_redirect'))->with('success', 'A breve il sistema cancellerà l\'elemento!');
        }catch (\Exception $e) {
            DB::rollBack();
            Log::info($e->getMessage());
            return redirect()->back()->with('error', 'Errore in fase di cancellazione!');
        }
    }

    public function storeMessage(Request $request, $id) {
        $validationRules = [
            'message' => 'required',
        ];
        $validatedData = $request->validate($validationRules);

        $now = \Carbon\Carbon::now();

        try {
            DB::table('messaggio_topic')->insert([
                'messaggio_id' => $id,
                'users_id' => \auth()->user()->id,
                'messaggio' => $request->input('message'),
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            $ids = DB::table('messaggio_utente')->where('messaggio_id', $id)->get()->pluck('utente_id', 'utente_id');

            foreach ($ids as $utente_id) {
                if ($utente_id != getUtenteIdBySessionUser()) {
                    DB::table('messaggio_topic_notify')->insert([
                        'messaggio_id' => $id,
                        'utente_id' => $utente_id,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]);
                }
            }

            $payload = 'Salvataggio avvenuto correttamente!';
            return response()->json(['res' => 'success','payload' => $payload]);
        }
        catch (\Exception $e) {
            $payload = $e->getMessage();
            return response()->json(['res' => 'error', 'payload' => $payload]);
        }
    }

    public function messages(Request $request, $id) {
        $data['messages'] = MessaggioTopic::where('messaggio_id', $id)
            ->orderBy('id', 'desc')
            ->limit(50)
            ->with('topic', 'user')
            ->get();

        $data['messages'] = $data['messages']->reverse();

        return view('dashboard.topic.forms.messages', $data);
    }

    public function loadOtherMessages(Request $request) {
        // Log::info($request->all());
        $data['messages'] = MessaggioTopic::where('messaggio_id', $request->input('messaggio_id'))
            ->where('id', '<', $request->input('prev'))
            ->orderBy('id', 'desc')
            ->limit(50)
            ->with('topic', 'user')
            ->get();

        // $data['messages'] = $data['messages']->reverse();
        // dd($data['messages']);
        return view('dashboard.topic.forms.messages-stream', $data);
    }
}
