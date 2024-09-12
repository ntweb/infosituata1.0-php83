<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Azienda;
use App\Models\DeviceConfiguration;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class AziendaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['list'] = Azienda::orderBy('id', 'desc')->with(['user', 'package', 'devices'])->paginate(50);
        return view('dashboard.azienda.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = [];
        return view('dashboard.azienda.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validationRules = [
            'uid' => 'required|unique:aziende',
            'label' => 'required',
            'citta' => 'required',
            'provincia' => 'required',
            'cap' => 'required',
            'indirizzo' => 'required',
            'legale_rappresentante' => 'required',
            'legale_rappresentante_tel' => 'required',
            'legale_rappresentante_email' => 'required|email',
            'rpd' => 'required',
            'rpd_email' => 'required|email',
            'email_contatto_privacy' => 'required|email',
        ];

        $validatedData = $request->validate($validationRules);

        $el = new Azienda();
        $fields = $request->except(['_token', '_redirect', '_method', '_type', '_module']);
        foreach ($fields as $k => $v) {
            $el->$k = $v;

            switch ($k) {
                case 'uid':
                    $el->$k = strtolower($v);
                    break;
                default:
                    $el->$k = $v;
            }
        }

        DB::beginTransaction();
        try {
            $el->save();

            // creo e associo un utente
            $u = new User;
            $u->name = $el->label;
            $u->email = $el->id.'@email.it';
            $u->password = Hash::make($u->email);
            $u->deactivate_at = \Carbon\Carbon::now()->addMonth();
            $u->azienda_id = $el->id;
            $u->save();

            // associo una configurazione globale di terminali
            DeviceConfiguration::firstOrCreate(['azienda_id' =>$el->id, 'device_id' => 0]);

            DB::commit();

            return redirect()->route('azienda.edit', [$el->id])->with('success', 'Salvataggio avvenuto correttamente!');
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
        $el = Azienda::with('user')->find($id);
        if (!$el) abort('404');

        $data['el'] = $el;
        return view('dashboard.azienda.create', $data);
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
        $el = Azienda::find($id);
        if (!$el) abort('404');

        $module_whatsapp_pre = $el->module_whatsapp;

        switch ($request->get('_module')) {
            case 'account':
                $validationRules = [
                    'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($el->user->id)],
                ];
                break;
            case 'fatturazione':
                $validationRules = [];
                break;
            case 'package':
                $validationRules = [
                    'package_id' => 'required'
                ];
                break;
            default:
                $validationRules = [
                    'label' => 'required',
                    'citta' => 'required',
                    'provincia' => 'required',
                    'cap' => 'required',
                    'indirizzo' => 'required',
                    'legale_rappresentante' => 'required',
                    'legale_rappresentante_tel' => 'required',
                    'legale_rappresentante_email' => 'required|email',
                    'rpd' => 'required',
                    'rpd_email' => 'required|email',
                    'email_contatto_privacy' => 'required|email',
                ];
        }

        $validatedData = $request->validate($validationRules);

        if ($request->get('_module') != 'account') {
            $fields = $request->except(['_token', '_redirect', '_method', '_type', '_module']);
            foreach ($fields as $k => $v) {
                $el->$k = $v;
            }
        }

        DB::beginTransaction();
        try {

            $el->save();

            $module_whatsapp_post = $el->module_whatsapp;

            // creo e associo un utente
            $u = User::whereAziendaId($id)->first();
            $u->email = $request->has('email') ?  $request->get('email') : $u->email;
            $u->password = trim($request->get('password', '')) !== '' ?  Hash::make($request->get('password')) : $u->password;

			if ($request->has('active'))
				$u->active = $request->input('active');

            /** Whatsapp send welcome **/
            DB::table('items')
                ->where('azienda_id', $el->id)
                ->where('controller', 'utente')
                ->update([
                    'whatsapp_send_welcome' => '0'
                ]);

			$u->deactivate_at = $request->has('deactivate_at') ?  strToDate($request->get('deactivate_at')) : $u->deactivate_at;
            $u->name = $el->label;
            $u->save();

            if ($u->active) {
                if ($module_whatsapp_post && ($module_whatsapp_post !== $module_whatsapp_pre)) {
                    // rimando la welcome
                    DB::table('items')
                        ->where('azienda_id', $el->id)
                        ->where('controller', 'utente')
                        ->update([
                            'whatsapp_send_welcome' => '1'
                        ]);
                }
            }

            DB::commit();

            $payload = 'Salvataggio avvenuto correttamente!';
            if ($request->get('_type') == 'json')
                return response()->json(['res' => 'success','payload' => $payload]);


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
}
