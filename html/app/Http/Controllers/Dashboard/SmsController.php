<?php

namespace App\Http\Controllers\Dashboard;

use App\Exceptions\MessageException;
use App\Models\Gruppo;
use App\Models\Pivot\SmsUtente;
use App\Models\Sede;
use App\Models\Sms;
use App\Models\Utente;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class SmsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (!Gate::allows('is-sms-module-enabled')) {
            $data['message'] = 'Il modulo selezionato non è attivo per questo account.';
            $data['message_2'] = 'Il modulo SMS permette di inviare brevi messaggi sms ad utenti specifici o gruppi di utenti';
            return view('layouts.helpers.module-deactive', $data);
        }

        $flag = $request->has('fl') ? $request->get('fl') : 'inbox';
        $data['title'] = $flag == 'inbox' ? 'Ricevuti' : 'Inviati';

        $query = Sms::with(['azienda', 'user']);

        if($flag == 'inbox') {
            $query = $query->leftJoin('messaggio_utente', 'messaggi.id', '=', 'messaggio_utente.messaggio_id')
                            ->where('messaggio_utente.utente_id', Auth::user()->utente_id)
                            ->select('messaggi.*', 'messaggio_utente.opened_at');
        }
        else {
            if (!Auth::user()->superadmin)
                $query = $query->whereUserId(Auth::user()->id);
        }

        if (Auth::user()->superadmin && $request->has('azienda'))
            $query = $query->whereAziendaId($request->get('azienda'));

        $data['list'] = $query->orderBy('sent_at', 'desc')->paginate(50)->appends(request()->query());
        return view('dashboard.sms.index', $data);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        if (!Gate::allows('is-sms-module-enabled')) {
            $data['message'] = 'Il modulo selezionato non è attivo per questo account.';
            $data['message_2'] = 'Il modulo SMS permette di inviare brevi messaggi sms ad utenti specifici o gruppi di utenti';
            return view('layouts.helpers.module-deactive', $data);
        }

        if (!Gate::allows('can_create_sms'))
            abort(401);

        $data = [];
        return view('dashboard.sms.create', $data);
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
            'oggetto' => 'required'
        ];

        if (!Auth::user()->superadmin)
            unset($validationRules['azienda_id']);

        $validatedData = $request->validate($validationRules);

        $el = new Sms;
        $fields = $request->except(['_token', '_redirect', '_method', '_type', '_module', 'gruppi_ids']);
        foreach ($fields as $k => $v) {
            switch ($k) {
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
            $el->module = 'sms';

            $el->save();

            DB::commit();

            return redirect()->route('sms.edit', [$el->id])->with('success', 'Salvataggio avvenuto correttamente!');
        }catch (\Exception $e) {
            DB::rollBack();
            Log::info($e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Errore in fase di salvataggio!');
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
        $el = Sms::find($id);
        if (!$el) abort('404');

        $data['el'] = $el;
        $gruppi_ids = explode(',', $el->gruppi_ids);
        $data['gruppiSel'] = Gruppo::whereAziendaId($el->azienda_id)->whereIn('id', $gruppi_ids)->get();

        $utenti_ids = explode(',', $el->utenti_ids);
        $data['utentiSel'] = Utente::with('user')->whereIn('id', $utenti_ids)->get();

        $data['utentiSelOpened'] = DB::table('messaggio_utente')->whereMessaggioId($id)->whereNotNull('opened_at')->get()->pluck('opened_at', 'utente_id');
        //dump($data);

        return view('dashboard.sms.show', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (!Gate::allows('is-sms-module-enabled')) {
            $data['message'] = 'Il modulo selezionato non è attivo per questo account.';
            $data['message_2'] = 'Il modulo SMS permette di inviare brevi messaggi sms ad utenti specifici o gruppi di utenti';
            return view('layouts.helpers.module-deactive', $data);
        }

        $el = Sms::find($id);
        if (!$el) abort('404');

        if ($el->sent_at)
            return redirect()->route('sms.show', [$id]);

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

        return view('dashboard.sms.create', $data);
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

        $el = Sms::find($id);
        if (!$el) abort('404');

        $validationRules = [
            'oggetto' => 'required',
            'messaggio' => 'required'
        ];

        if (!Auth::user()->superadmin)
            unset($validationRules['azienda_id']);

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

            DB::commit();

            if ($request->get('_module', null) == 'send')
                return $this->send($request, $el->id);

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
    public function destroy($id)
    {
        //
    }

    private function send(Request $request, $id) {

        $el = Sms::find($id);
        if (!$el) abort('404');

        DB::beginTransaction();
        try {

            $_can_send = $el->gruppi_ids || $el->utenti_ids || $el->sedi_ids;
            if (!$_can_send)
                throw new MessageException("Inserire correttamente i destinatari");

            // salvo i vari destinatari
            // sedi
            if ($el->sedi_ids) {
                $sedi_ids = explode(',', $el->sedi_ids);
                $utenti_ids = DB::table('sede_item')
                    ->leftJoin('items', 'sede_item.item_id', '=', 'items.id')
                    ->whereController('utente')
                    ->whereIn('sede_id', $sedi_ids)->get()->pluck('item_id');
                // Log::info($utenti_ids);
                foreach ($utenti_ids as $utente_id) {
                    SmsUtente::firstOrCreate([
                        'messaggio_id' => $id,
                        'utente_id' => $utente_id
                    ]);
                }
            }

            // gruppi
            if ($el->gruppi_ids) {
                $gruppi_ids = explode(',', $el->gruppi_ids);
                $utenti_ids = DB::table('gruppo_utente')->whereIn('gruppo_id', $gruppi_ids)->get()->pluck('utente_id');
                // Log::info($utenti_ids);
                foreach ($utenti_ids as $utente_id) {
                    SmsUtente::firstOrCreate([
                        'messaggio_id' => $id,
                        'utente_id' => $utente_id
                    ]);
                }
            }

            if ($el->utenti_ids) {
                $utenti_ids = explode(',', $el->utenti_ids);
                // Log::info($utenti_ids);
                foreach ($utenti_ids as $utente_id) {
                    SmsUtente::firstOrCreate([
                        'messaggio_id' => $id,
                        'utente_id' => $utente_id
                    ]);
                }
            }

            $el->sent_at = \Carbon\Carbon::now();
            $el->to_send = '1';
            $el->save();

            DB::commit();
            $payload = 'Salvataggio avvenuto correttamente!';

            // invio notifica email
            // $bcc = getMessageDesinatari($el);
            // sendEmailGenerica(null, $bcc, 'Nuovo messaggio da INFOSITUATA', 'Gentile utente la preghiamo di collegarsi nella sua area privata di INFOSITUATA. Ha ricevuto un nuovo messaggio con oggetto: '. $el->oggetto);

            if ($request->get('_type') == 'json')
                return response()->json(['res' => 'success','payload' => $payload, '_redirect' => route('sms.edit', [$id])]);

            return redirect()->back()->with('success', 'Salvataggio avvenuto correttamente!');

        }catch (\Exception $e) {
            DB::rollBack();

            Log::info($e->getMessage());
            $payload = 'Errore in fase di invio! ' . $e->getMessage();
            if ($request->get('_type') == 'json')
                return response()->json(['res' => 'error', 'payload' => $payload]);

            return redirect()->back()->withInput()->with('error', $payload);
        }
    }

    public function showUser($id) {
        $data['el'] = SmsUtente::whereUtenteId(Auth::user()->utente_id)
                                                            ->whereMessaggioId($id)
                                                            ->with(['messaggio', 'messaggio.user'])
                                                            ->orderBy('created_at', 'desc')
                                                            ->limit(50)
                                                            ->first();

        if (!$data['el']) abort(404);
        if (!$data['el']->opened_at) {
            DB::table('messaggio_utente')->whereMessaggioId($id)->whereUtenteId(Auth::user()->utente_id)
                ->update([
                    'opened_at' => \Carbon\Carbon::now()
                ]);
        }

        $data['el'] = $data['el']->messaggio;
        return view('dashboard.sms.show-user', $data);
    }

    public function configure() {
        if (!Gate::allows('is-sms-module-enabled')) {
            $data['message'] = 'Il modulo selezionato non è attivo per questo account.';
            $data['message_2'] = 'Il modulo SMS permette di inviare brevi messaggi sms ad utenti specifici o gruppi di utenti';
            return view('layouts.helpers.module-deactive', $data);
        }

        return view('dashboard.sms.configure');
    }
}
