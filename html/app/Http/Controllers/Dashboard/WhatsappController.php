<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Azienda;
use App\Models\Gruppo;
use App\Models\MessaggioWhatsapp;
use App\Models\Sede;
use App\Models\Utente;
use App\Models\Whatsapp;
use http\Env\Response;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;

class WhatsappController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (!Gate::allows('is-whatsapp-module-enabled')) {
            $data['message'] = 'Il modulo selezionato non è attivo per questo account.';
            $data['message_2'] = 'Il modulo Whatsapp permette di inviare messaggi ed allegati ad utenti o gruppi di utenti che hanno un account Whatsapp e relativa app installata sul proprio smartphone';
            return view('layouts.helpers.module-deactive', $data);
        }

        if(!Gate::allows('can_create_whatsapp'))
            abort(401);

        $query = Whatsapp::with(['user']);

        if (\auth()->user()->utente_id)
            $query = $query->leftJoin('messaggio_utente', 'messaggi.id', '=', 'messaggio_utente.messaggio_id')
                ->where('messaggio_utente.utente_id', Auth::user()->utente_id);
        else
            $query = $query->where('user_id', \auth()->user()->id);

        $query = $query->select('messaggi.*');
        $data['list'] = $query->orderBy('sent_at', 'desc')->paginate(50)->appends(request()->query());

        return view('dashboard.whatsapp.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!Gate::allows('is-whatsapp-module-enabled')) {
            $data['message'] = 'Il modulo selezionato non è attivo per questo account.';
            $data['message_2'] = 'Il modulo Whatsapp permette di inviare messaggi ed allegati ad utenti o gruppi di utenti che hanno un account Whatsapp e relativa app installata sul proprio smartphone';
            return view('layouts.helpers.module-deactive', $data);
        }

        if(!Gate::allows('can_create_whatsapp'))
            abort(401);

        $data['gruppi'] = Gruppo::whereAziendaId(getAziendaId())->orderBy('label')->get()->pluck('label', 'id');
        $data['gruppiSel'] = [];

        $data['sedi'] = Sede::whereAziendaId(getAziendaId())->orderBy('label')->get()->pluck('label', 'id');
        $data['sediSel'] = [];

        $data['utenti'] = Utente::with('user')->orderBy('extras1')->get();
        $data['utenti'] = $data['utenti']->filter(function ($value, $key) {
            return $value->user->active == '1';
        })->mapWithKeys(function ($item) {
            return [$item->id => $item->extras1.' '.$item->extras2];
        });

        $data['utentiSel'] = [];

        return view('dashboard.whatsapp.create', $data);
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
            'message' => 'required',
        ];

        $validatedData = $request->validate($validationRules);

        $el = new Whatsapp;
        $fields = $request->except(['_token', '_redirect', '_method', '_type', '_module', '_item_id']);
        foreach ($fields as $k => $v) {


            switch ($k) {
                case 'sedi_ids':
                case 'gruppi_ids':
                case 'utenti_ids':
                    $el->$k = join(',', $v);
                    break;

                case 'message':
                    $el->messaggio = $v;
                    break;
                default:
                    $el->$k = $v;
            }
        }

        DB::beginTransaction();
        try {

            $el->module = 'whatsapp';
            $el->azienda_id = getAziendaId();
            $el->user_id = \auth()->user()->id;

            // salvo i vari destinatari
            // sedi
            $utenti_ids = [];
            if ($request->has('sedi_ids')) {
                $sedi_ids = $request->input('sedi_ids');
                $utentiIds = DB::table('sede_item')
                    ->leftJoin('items', 'sede_item.item_id', '=', 'items.id')
                    ->whereController('utente')
                    ->whereIn('sede_id', $sedi_ids)->get()->pluck('item_id');
                // Log::info($utentiIds);
                foreach ($utentiIds as $utente_id) {
                    $utenti_ids[$utente_id] = $utente_id;
                }
            }

            // gruppi
            if ($request->has('gruppi_ids')) {
                $gruppi_ids = $request->input('gruppi_ids');
                $utentiIds = DB::table('gruppo_utente')->whereIn('gruppo_id', $gruppi_ids)->get()->pluck('utente_id');
                // Log::info($utentiIds);
                foreach ($utentiIds as $utente_id) {
                    $utenti_ids[$utente_id] = $utente_id;
                }
            }

            if ($request->has('utenti_ids')) {
                $utentiIds = $request->input('utenti_ids');
                // Log::info($utentiIds);
                foreach ($utentiIds as $utente_id) {
                    $utenti_ids[$utente_id] = $utente_id;
                }
            }

            if (!count($utenti_ids))
                throw new \Exception('Selezionare un utente o un gruppo di utenti');

            $el->sent_at = \Carbon\Carbon::now();
            $el->save();

            $azienda = getAziendaBySessionUser();
            $this->_saveUsersMessage($azienda, $el, $utenti_ids, 'business');

            DB::commit();

            return redirect()->action('Dashboard\WhatsappController@index', [$el->id])->with('success', 'Salvataggio avvenuto correttamente!');
        }catch (\Exception $e) {
            DB::rollBack();
            Log::info($e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Errore in fase di invio! '. $e->getMessage());
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

        if (!Gate::allows('is-whatsapp-module-enabled')) {
            $data['message'] = 'Il modulo selezionato non è attivo per questo account.';
            $data['message_2'] = 'Il modulo Whatsapp permette di inviare messaggi ed allegati ad utenti o gruppi di utenti che hanno un account Whatsapp e relativa app installata sul proprio smartphone';
            return view('layouts.helpers.module-deactive', $data);
        }


        $el = Whatsapp::with('utenti')->find($id);
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

        if (\auth()->user()->id == $el->user_id || isset(auth()->user()->id))
            return view('dashboard.whatsapp.create', $data);

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
        $el = Whatsapp::find($id);
        if (!$el) abort('404');

        $validationRules = [
            'oggetto' => 'required',
            'message' => 'required',
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
                case 'message':
                    $el->messaggio = $v;
                    break;
                default:
                    $el->$k = $v;
            }
        }

        DB::beginTransaction();
        try {

            $el->azienda_id = getAziendaId();
            $el->user_id = \auth()->user()->id;

            // salvo i vari destinatari
            // sedi
            $utenti_ids = [];
            if ($request->has('sedi_ids')) {
                $sedi_ids = $request->input('sedi_ids');
                $utentiIds = DB::table('sede_item')
                    ->leftJoin('items', 'sede_item.item_id', '=', 'items.id')
                    ->whereController('utente')
                    ->whereIn('sede_id', $sedi_ids)->get()->pluck('item_id');
                // Log::info($utentiIds);
                foreach ($utentiIds as $utente_id) {
                    $utenti_ids[$utente_id] = $utente_id;
                }
            }

            // gruppi
            if ($request->has('gruppi_ids')) {
                $gruppi_ids = $request->input('gruppi_ids');
                $utentiIds = DB::table('gruppo_utente')->whereIn('gruppo_id', $gruppi_ids)->get()->pluck('utente_id');
                // Log::info($utentiIds);
                foreach ($utentiIds as $utente_id) {
                    $utenti_ids[$utente_id] = $utente_id;
                }
            }

            if ($request->has('utenti_ids')) {
                $utentiIds = $request->input('utenti_ids');
                // Log::info($utentiIds);
                foreach ($utentiIds as $utente_id) {
                    $utenti_ids[$utente_id] = $utente_id;
                }
            }

            if (!count($utenti_ids))
                throw new \Exception('Selezionare un utente o un gruppo di utenti');

            $el->sent_at = \Carbon\Carbon::now();
            $el->save();

            $azienda = getAziendaBySessionUser();
            $this->_saveUsersMessage($azienda, $el, $utenti_ids, 'business');

            DB::commit();

            return redirect()->action('Dashboard\WhatsappController@index', [$el->id])->with('success', 'Salvataggio avvenuto correttamente!');
        }catch (\Exception $e) {
            DB::rollBack();
            Log::info($e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Errore in fase di invio! '. $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    private function _saveUsersMessage($azienda, $messaggioWhatsapp, $utentiIds, $from = 'business') {
        $createdAt = \Carbon\Carbon::now();
        foreach ($utentiIds as $utente_id) {
            DB::table('messaggio_whatsapp')->insert([
                'azienda_id' => $azienda->id,
                'messaggio_id' => $messaggioWhatsapp->id,
                'utente_id' => $utente_id,
                'message' => $messaggioWhatsapp->messaggio,
                'phone_number_id' => $azienda->module_whatsapp_phone_number_id,
                'from' => $from,
                'sent_at' => null,
                'opened_at' => null,
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ]);
        }
    }

    public function webhook(Request $request) {
        if ($request->has('hub_mode') && $request->has('hub_verify_token')) {
            if ($request->input('hub_mode') === 'subscribe' && $request->input('hub_verify_token') === 'infosituata') {
                return $request->input('hub_challenge');
            }
        }
        abort(400);
    }

    public function webhookCallback(Request $request) {
        Log::info(json_encode($request->all()));
        // return;

        $response = json_decode(json_encode($request->all()));
        $object = $response->object;
        $entry = $response->entry[0];
        $change = $entry->changes[0]->value;
        $phone_number_id = $change->metadata->phone_number_id;

        if (isset($change->statuses)) {
            $status = $change->statuses[0];
            $wamid = $status->id;
            $s = $status->status;

            DB::table('messaggio_whatsapp')->where('wamid', $wamid)->update([
                'status' => $s
            ]);

            return;
        }

        $message = $change->messages[0];
        $from = $message->from;
        $wamid = $message->id;
        $type = $message->type;



        // trovo l'azienda
        $azienda = Azienda::where('module_whatsapp_phone_number_id', $phone_number_id)->first();
        if ($azienda) {
            // trovo l'utente
            $utente = Utente::where('azienda_id', $azienda->id)
                ->where('extras6', $from)
                ->with('user')
                ->first();

            if ($utente) {
                if ($utente->user->active) {

                    switch ($type) {
                        case 'text':
                            $message = $message->text->body;
                            break;
                    }

                    if (isset($message)) {
                        $created_at = \Carbon\Carbon::now();
                        DB::table('messaggio_whatsapp')->insert([
                            'azienda_id' => $azienda->id,
                            'utente_id' => $utente->id,
                            'message' => $message,
                            'phone_number_id' => $phone_number_id,
                            'from' => 'guest',
                            'sent_at' => $created_at,
                            'wamid' => $wamid,
                            'opened_at' => null,
                            'created_at' => $created_at,
                            'updated_at' => $created_at,
                        ]);
                    }
                }
            }
        }

//        Log::info($object);
//        Log::info($phone_number_id);
//        Log::info($from);
//        Log::info($wamid);
//        Log::info($type);
//        Log::info($text);
    }

    public function chat() {
        if (!Gate::allows('is-whatsapp-module-enabled')) {
            $data['message'] = 'Il modulo selezionato non è attivo per questo account.';
            $data['message_2'] = 'Il modulo Whatsapp permette di inviare messaggi ed allegati ad utenti o gruppi di utenti che hanno un account Whatsapp e relativa app installata sul proprio smartphone';
            return view('layouts.helpers.module-deactive', $data);
        }

        $data = [];

        $data['hide_utenti_list'] = false;
        if (Gate::allows('can-create')) {
            // è un superadmin

            /** Lista degli utenti **/
            $data['utenti'] = Utente::orderBy('extras1')
                ->orderBy('extras2')
                ->get();
        }
        else {
            $data['hide_utenti_list'] = true;
            $data['utente_id'] = \auth()->user()->utente_id;
            // è un utente normale
            $data['utenti'] = Utente::where('id', \auth()->user()->utente_id)->get();
            // dd(\auth()->user()->utente_id);
        }

        return view('dashboard.whatsapp.chat', $data);
    }

    public function showChat($idUtente) {
        $data['el'] = Utente::find($idUtente);
        $data['utente_id'] = $idUtente;

        return view('dashboard.whatsapp.forms.message', $data);
    }

    public function messages ($idUtente) {

        $data['messages'] = MessaggioWhatsapp::where('utente_id', $idUtente)
            ->orderBy('id', 'desc')
            ->limit(50)
            ->with('messaggio', 'utente', 'azienda')
            ->get();

        $data['messages'] = $data['messages']->reverse();
        // dd($data['messages']);

        return view('dashboard.whatsapp.forms.messages', $data);
    }

    public function loadOtherMessages(Request $request) {
        // Log::info($request->all());
        $data['messages'] = \App\Models\Pivot\MessaggioWhatsapp('utente_id', $request->input('utente_id'))
            ->where('id', '<', $request->input('prev'))
            ->orderBy('id', 'desc')
            ->limit(50)
            ->with('messaggio', 'utente', 'azienda')
            ->get();

        // $data['messages'] = $data['messages']->reverse();
        // dd($data['messages']);
        return view('dashboard.topic.forms.messages-stream', $data);
    }

    public function send(Request $request) {
        try {

            $utente_id = $request->input('utente_id');
            $utente = Utente::find($utente_id);

            if (!$utente) {
                abort('404');
            }

            $text = $request->input('text');
            $azienda = getAziendaBySessionUser();

            if (\auth()->user()->utente_id == $utente_id) {
                $from = 'guest';
                $res = sendWhatsappMessage($azienda, $azienda->module_whatsapp_tel, $text);
            }
            else {
                $from = 'business';
                $res = sendWhatsappMessage($azienda, $utente->extras6, $text);
            }

            $m = new MessaggioWhatsapp();
            $m->azienda_id = $azienda->id;
            $m->utente_id = $utente_id;
            $m->message = $text;
            $m->phone_number_id = $azienda->module_whatsapp_phone_number_id;
            $m->from = $from;


            $m->sent_at = \Carbon\Carbon::now();
            Log::info(json_encode($res));
            $m->wamid = $res->messages[0]->id;
            $m->save();

            return response()->json(['res' => 'success']);

        }
        catch (\Exception $e) {
            abort($e->getMessage());
        }

    }
}
