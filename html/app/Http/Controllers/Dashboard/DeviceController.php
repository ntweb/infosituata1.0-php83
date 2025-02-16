<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Azienda;
use App\Models\Device;
use App\Models\DeviceConfiguration;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class DeviceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if(!Gate::allows('can_create_ham_terminali'))
            abort(401);

        $query = Device::with(['azienda', 'utente', 'type']);
        if (Auth::user()->superadmin && $request->has('azienda'))
            $query->whereAziendaId($request->get('azienda'));

        if($request->has('q')) {
            $query->where(function ($query) use ($request) {
                $query->where('label', 'like', '%'.$request->get('q').'%')
                    ->orWhere('identifier', 'like', '%'.$request->get('q').'%');
            });
        }

        $data['list'] = $query->paginate(50)->appends(request()->query());
        return view('dashboard.device.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function create(Request $request)
    {
        if(!Gate::allows('can_create_ham_terminali'))
            abort(401);

        $data['azienda_id'] = Auth::user()->azienda_id;
        if (packageError('terminali', $data['azienda_id']))
            return redirect()->route('package.error')->with(['package-error' => 'Non è consentito creare ulteriori terminali']);

        return view('dashboard.device.create', $data);
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
            'label' => 'required',
            'device_type_id' => 'required',
        ];

        if (!Auth::user()->superadmin)
            unset($validationRules['azienda_id']);

        $validatedData = $request->validate($validationRules);

        $el = new Device();
        $fields = $request->except(['_token', '_redirect', '_method', '_type', '_module']);
        foreach ($fields as $k => $v) {
            $el->$k = $v;
        }

        DB::beginTransaction();
        try {

            $azienda_id = (Auth::user()->superadmin && $request->has('azienda_id')) ? $request->get('v') : getAziendaId($el);
            $el->azienda_id = $azienda_id ? $azienda_id : $el->azienda_id;
            $el->identifier = substr(Str::uuid(), -12);
            $el->active = '0';
            // forzo la richiesta di aggiornamento
            $el->save();

            /**
             *  creo anche la configuratione personalizzata disattivata
             */
            $configuration = DeviceConfiguration::firstOrCreate(['azienda_id' => $el->azienda_id, 'device_id' => $el->id]);
            $configuration->request_configuration_update = \Carbon\Carbon::now();

            DB::commit();

            return redirect()->route('device.edit', [$el->id])->with('success', 'Salvataggio avvenuto correttamente!');
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
        if(!Gate::allows('can_create_ham_terminali'))
            abort(401);

        $el = Device::with('configuration')->find($id);
        if (!$el) abort('404');

        $data['el'] = $el;

        return view('dashboard.device.create', $data);
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
        $el = Device::find($id);
        if (!$el) abort('404');

        switch ($request->get('_module')) {
            case 'utente':
                $validationRules = [];

                break;
            default:
                $validationRules = [
                    'label' => 'required',
                    'device_type_id' => 'required',
                    'identifier' => ['required', Rule::unique('devices', 'identifier')->ignore($el->id)],
                ];
        }

        if(!Auth::user()->superadmin && $request->get('_module') != 'utente')
            unset($validationRules['azienda_id']);

        $validatedData = $request->validate($validationRules);

        //if ($request->get('_module', null) == null) {
            $fields = $request->except(['_token', '_redirect', '_method', '_type', '_module']);
            foreach ($fields as $k => $v) {
                $el->$k = $v;
            }
        //}

        DB::beginTransaction();
        try {

            if (!$request->has('_module'))
                $el->active = $request->has('active') ? '1' : '0';

            $el->save();

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

    public function configuration(Request $request) {

        if(!Gate::allows('can_create_ham_terminali'))
            abort(401);

        $azienda_id = (Auth::user()->superadmin && $request->has('azienda_id')) ? $request->get('azienda_id') : getAziendaId();
        if ($azienda_id > 0) {
            $data['el'] = DeviceConfiguration::firstOrCreate(['azienda_id' =>$azienda_id, 'device_id' => 0]);
            return view('dashboard.device.configuration-global', $data);
        }

        if (Auth::user()->superadmin) {
            if ($request->has('azienda')) {
                $azienda = Azienda::find($request->get('azienda'));
                if (!$azienda) abort(404);

                $data['el'] = DeviceConfiguration::firstOrCreate(['azienda_id' => $azienda->id, 'device_id' => 0]);
                return view('dashboard.device.configuration-global', $data);
            }

            return redirect()->route('azienda.index');
        }

        abort(401);
    }

    public function updateConfiguration(Request $request, $id)
    {
        $el = DeviceConfiguration::find($id);
        if (!$el) abort('404');

        switch ($request->get('_module', null)) {
            default:
                $validationRules = [
                    'hrm_bpm_min' => 'required|numeric|min:0',
                    'hrm_bpm_max' => 'required|numeric|min:0|gt:hrm_bpm_min',
                ];
        }

        $validatedData = $request->validate($validationRules);

        //if ($request->get('_module', null) == null) {
        $fields = $request->except(['_token', '_redirect', '_method', '_type', '_module']);
        foreach ($fields as $k => $v) {
            switch ($k) {
                case 'emails_alert':
                    $_email_to_insert = [];
                    $_emails = explode(',', $v);
                    foreach ($_emails as $_email) {
                        $_email = strtolower(trim($_email));
                        if (filter_var($_email, FILTER_VALIDATE_EMAIL)) {
                            $_email_to_insert[] = $_email;
                        }
                    }
                    $el->$k = join(',',$_email_to_insert);
                    break;
                case 'telephones_alert':
                    $_phones = strtolower(trim($v));
                    $_phones = str_replace(' ','',$_phones);
                    $_phones = str_replace('.','',$_phones);
                    $_phones = str_replace('-','',$_phones);
                    $_phones = str_replace('(','',$_phones);
                    $_phones = str_replace(')','',$_phones);
                    $el->$k = $_phones;
                    break;
                default:
                    $el->$k = $v;
            }

        }
        //}

        DB::beginTransaction();
        try {

            // Log::info($el);
            /**
             * se è una configurazione globale forzo il flag di aggiornamento su tutti i dispositivi che
             * hanno disattivato l'aggiornamento personalizato
             */
            if ($el->device_id <= 0) {
                DB::table('devices_configuration')
                    ->whereAziendaId($el->azienda_id)
                    ->where('device_id', '>', 0)
                    ->whereActive('0')
                    ->update([
                        'request_configuration_update' => \Carbon\Carbon::now()
                    ]);
            } else {
                $el->request_configuration_update = \Carbon\Carbon::now();
            }

            $el->save();


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
}
