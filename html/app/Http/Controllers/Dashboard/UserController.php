<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Azienda;
use App\Models\Gruppo;
use App\Models\Sede;
use App\Models\User;
use App\Models\Utente;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Utente::with(['azienda', 'user']);
        if (Auth::user()->superadmin && $request->has('azienda'))
            $query->whereAziendaId($request->get('azienda'));

        if($request->has('q')) {
            $query->where(function ($query) use ($request) {
                $query->where('extras1', 'like', '%'.$request->get('q').'%')
                    ->orWhere('extras2', 'like', '%'.$request->get('q').'%')
                    ->orWhere('extras3', 'like', '%'.$request->get('q').'%');
            });
        }

        $data['list'] = $query->paginate(500)->appends(request()->query());
        return view('dashboard.user.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $data['azienda_id'] = (Auth::user()->superadmin && $request->has('azienda')) ? $request->get('azienda') : getAziendaId();
        if (packageError('utente', $data['azienda_id']))
            return redirect()->action('Dashboard\PackageController@error')->with(['package-error' => 'Non è consentito creare ulteriori utenti']);

        return view('dashboard.user.create', $data);
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
            'extras1' => 'required',
            'extras2' => 'required',
            'azienda_id' => 'required',
        ];

        if (!Auth::user()->superadmin)
            unset($validationRules['azienda_id']);

        $validatedData = $request->validate($validationRules);

        $el = new Utente;
        $fields = $request->except(['_token', '_redirect', '_method', '_type', '_module']);
        foreach ($fields as $k => $v) {
            $el->$k = $v;
        }

        DB::beginTransaction();
        try {
            $azienda_id = (Auth::user()->superadmin && $request->has('azienda_id')) ? $request->get('azienda_id') : getAziendaId($el);
            $el->azienda_id = $azienda_id ? $azienda_id : $el->azienda_id;
            $el->controller = 'utente';
            $el = $this->_formatCompleteTelephone($el);
            $el->save();

            // creo e associo un utente
            $u = new User;
            $u->name = $el->extras1.' '.$el->extras2;
            $u->email = $el->id.'_utente@email.it';
            $u->password = Hash::make($u->email);
            $u->utente_id = $el->id;
            $u->utente_azienda_id = $el->azienda_id;

            $u->api_token = Str::random(60);

            $u->save();

            DB::commit();

            $payload = 'Salvataggio avvenuto correttamente!';
            if ($request->get('_type') == 'json')
                return response()->json(['res' => 'success','payload' => $payload]);

            return redirect()->action('Dashboard\UserController@edit', [$el->id])->with('success', 'Salvataggio avvenuto correttamente!');
        }catch (\Exception $e) {
            DB::rollBack();

            Log::info($e->getMessage());

            $payload = 'Errore in fase di salvataggio!';
            if ($request->get('_type') == 'json')
                return response()->json(['res' => 'error', 'payload' => $payload]);

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
        if(Gate::denies('can_create_utenti'))
            return redirect()->action('Dashboard\InfosituataPublicController@check', md5($id));

        $el = Utente::with('gruppi')->find($id);
        if (!$el) abort('404');

        $data['gruppi'] = Gruppo::whereAziendaId($el->azienda_id)->orderBy('label')->get()->pluck('label', 'id');
        $data['gruppiSel'] = $el->gruppi->pluck('id', 'id');

        $data['sedi'] = Sede::whereAziendaId($el->azienda_id)->orderBy('label')->get()->pluck('label', 'id');
        $data['sediSel'] = $el->sedi->pluck('id', 'id');

        $data['el'] = $el;

        return view('dashboard.user.create', $data);
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

        switch ($request->get('_module')) {
            case 'gruppi':
            case 'bigtext':
                $validationRules = [];
                break;

            case 'account':
				$u = User::whereUtenteId($id)->first();
                $validationRules = [
                    'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($u->id)],
                ];
                break;

            case 'password':
                $validationRules = ['password' => 'required'];
                break;

            default:
                $validationRules = [
                    'extras1' => 'required',
                    'extras2' => 'required',
                    'azienda_id' => 'required',
                ];
        }

        if(!Auth::user()->superadmin)
            unset($validationRules['azienda_id']);

        $validatedData = $request->validate($validationRules);

        if ($request->get('_module', null) == 'password') {
            return $this->updatePassword($request);
        }

        if ($request->get('_module', null) == 'account') {
            if ($request->has('active')) {
                if ($request->input('active')) {
                    $azienda = getAziendaBySessionUser();
                    if ($azienda->max_login) {
                        $numLogin = User::whereUtenteAziendaId($azienda->id)->whereActive('1')->where('id', '!=', $id)->count();
                        Log::info($numLogin);
                        if ($numLogin >= $azienda->max_login) {
                            $payload = 'Non è possibile attivare l\'utente. Hai raggiunto il numero massimo di utenti attivi consentiti dal tuo piano.';
                            if ($request->get('_type') == 'json')
                                return response()->json(['res' => 'error', 'payload' => $payload]);

                            return redirect()->back()->withInput()->with('error', $payload);
                        }
                    }
                }
            }
        }

        /**
         * IMPORTANTE
         * Carico l'utente dopo il controllo se sto cambiando la password
         * perchè utilizzo direttamente l'utente in sessione e non "l'Item / Utente"
        **/
        $el = Utente::find($id);
        if (!$el) abort('404');

        if ($request->get('_module', null) == null || $request->get('_module', null) == 'bigtext') {
            $fields = $request->except(['_token', '_redirect', '_method', '_type', '_module']);
            foreach ($fields as $k => $v) {
                $el->$k = $v;
            }
        }

        if ($request->get('_module', null) == 'gruppi') {
            $gruppiIds = collect($request->get('gruppi', []))->values()->toArray();
            $el->gruppi()->sync($gruppiIds);
        }

        DB::beginTransaction();
        try {
            $azienda_id = (Auth::user()->superadmin && $request->has('azienda_id')) ? $request->get('azienda_id') : getAziendaId($el);
            $el->azienda_id = $azienda_id ? $azienda_id : $el->azienda_id;

            $el = $this->_formatCompleteTelephone($el);

            $el->save();

            // creo e associo un utente
            $u = User::whereUtenteId($id)->first();
            $u->email = $request->has('email') ?  $request->get('email') : $u->email;
            $u->password = trim($request->get('password', '')) !== '' ?  Hash::make($request->get('password')) : $u->password;

            if ($request->has('power_user'))
                $u->power_user = $request->input('power_user');

            if ($request->has('active'))
                $u->active = $request->input('active');

            if ($request->has('_2fa'))
                $u->_2fa = $request->input('_2fa');

            // Log::info($u->_2fa);
            if ($u->_2fa && !$u->api_token) {
                $u->api_token = Str::random(60);
            }

            $u->name = $el->extras1.' '.$el->extras2;
            $u->save();

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

    public function password() {
        $data['el'] = Auth::user();
        return view('dashboard.user.password', $data);
    }

    private function updatePassword(Request $request) {
        $u = Auth::user();

        DB::beginTransaction();
        try {
            $u->password = Hash::make($request->get('password'));
            $u->save();

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

    public function export() {
        $filename = 'Export-utenti-'.auth()->user()->id.time().'.xlsx';

        $spreadsheet = new Spreadsheet();
        $spreadsheet->getProperties()->setCreator("Infosituata.com");
        $spreadsheet->getProperties()->setTitle("Export lista utenti");
        $spreadsheet->getProperties()->setSubject("Export lista utenti");
        $spreadsheet->getProperties()->setDescription("Export lista utenti");

        $i=1;
        //descrivo i criteri selezionati
        // $spreadsheet->getActiveSheet()->SetCellValue("A$i", "ITEM: " . Str::title($el->extras1));
        $i++;

        $celle = array(
            "A"=>"Cognome",
            "B"=>"Nome",
            "C"=>"Matricola",
            "D"=>"Email",
            "E"=>"Telefono",
            "F"=> "Attivo",
            "G"=> "Scadenze non gestite (ultimi 6 mesi)"
        );

        foreach ($celle as $k=>$v){
            //scrivo l'intestazione della colonna
            $spreadsheet->getActiveSheet()->SetCellValue("$k$i", $v);

            //formatto le intestazioni delle colonne
            $spreadsheet->getActiveSheet()->getStyle("$k$i")->applyFromArray(
                array(
                    'font'    => array(
                        'name'      => 'Arial',
                        'bold'      => true,
                        'italic'    => false
                    )
                )
            );

            //imposto per tutte le colonne l'autosize
            if ($k!='A') {
                $spreadsheet->getActiveSheet()->getColumnDimension("$k")->setAutoSize(true);
            }
        }
        $i++;
        $spreadsheet->getActiveSheet()->getColumnDimension("A")->setWidth(20);


        /**
         * query lista
        **/
        $list = Utente::with(['azienda', 'user', 'scadenzeNonGestite'])
            ->orderBy('extras1')
            ->orderBy('extras2')
            ->get();


        $styleAlignLeftString = [
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
            ]
        ];

        foreach ($list as $l)
        {
            /** Nome **/
            $cell = $spreadsheet->getActiveSheet()->getCell("A$i");
            $cell->getStyle()->applyFromArray($styleAlignLeftString);
            $cell->setValueExplicit(Str::title($l->extras1), \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING2);

            /** Cognome **/
            $cell = $spreadsheet->getActiveSheet()->getCell("B$i");
            $cell->getStyle()->applyFromArray($styleAlignLeftString);
            $cell->setValueExplicit(Str::title($l->extras2), \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING2);

            /** Matricola **/
            $cell = $spreadsheet->getActiveSheet()->getCell("C$i");
            $cell->getStyle()->applyFromArray($styleAlignLeftString);
            $cell->setValueExplicit(strtolower($l->extras3), \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING2);

            /** Email **/
            $cell = $spreadsheet->getActiveSheet()->getCell("D$i");
            $cell->getStyle()->applyFromArray($styleAlignLeftString);
            $cell->setValueExplicit(Str::title(strtolower($l->user->email)), \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING2);

            /** Telefono **/
            $cell = $spreadsheet->getActiveSheet()->getCell("E$i");
            $cell->getStyle()->applyFromArray($styleAlignLeftString);
            $cell->setValueExplicit(strtolower($l->extras4), \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING2);

            $spreadsheet->getActiveSheet()->SetCellValue("F$i", $l->user->active ? 'SI' : 'NO');

            $numScadenzeNonGestite = $l->scadenzeNonGestite->count();
            $cell = $spreadsheet->getActiveSheet()->getCell("G$i");
            $cell->setValueExplicit($numScadenzeNonGestite, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING2);

            if ($numScadenzeNonGestite) {
                $cell->getStyle()
                    ->getFill()
                    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    ->getStartColor()
                    ->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_RED);
                $cell->getStyle()->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_WHITE);
            }


            $i++;
        }

        $writer = new Xlsx($spreadsheet);
        $writer->save(public_path('temp/'.$filename));

        return response()->download(public_path('temp/'.$filename));
    }

    private function _formatCompleteTelephone($utente) {
        $oldExtras6 = $utente->extras6;

        $pref = trim($utente->extras5);
        $pref = str_replace(' ', '', $pref);
        $pref = str_replace('+', '', $pref);

        $telephone = trim($utente->extras4);
        $telephone = str_replace(' ', '', $telephone);
        $telephone = $pref . str_replace('+', '', $telephone);

        $utente->extras6 = $telephone ?? null;

        $azienda = getAziendaBySessionUser();
        if (!$azienda) {
            $azienda_id = getAziendaId($utente);
            $azienda = Azienda::find($azienda_id);
        }

        if ($utente->extras6 && $azienda->module_whatsapp && $utente->extras6 != $oldExtras6) {
            $utente->whatsapp_send_welcome = '1';
        }

        return $utente;
    }
}
