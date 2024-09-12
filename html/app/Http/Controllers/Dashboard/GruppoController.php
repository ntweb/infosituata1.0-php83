<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Gruppo;
use App\Models\Utente;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class GruppoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Gruppo::with('azienda');
        if (Auth::user()->superadmin && $request->has('azienda'))
            $query->whereAziendaId($request->get('azienda'));

        if($request->has('q')) {
            $query->where('label', 'like', '%'.$request->get('q').'%');
        }

        $data['list'] = $query->paginate(50)->appends(request()->query());
        return view('dashboard.gruppo.index', $data);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $data['azienda_id'] = (Auth::user()->superadmin && $request->has('azienda_id')) ? $request->get('azienda_id') : getAziendaId();
        if (packageError('gruppo', $data['azienda_id']))
            return redirect()->route('package.error')->with(['package-error' => 'Non è consentito creare ulteriori gruppi']);

        return view('dashboard.gruppo.create', $data);
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
            'azienda_id' => 'required',
        ];

        if (!Auth::user()->superadmin)
            unset($validationRules['azienda_id']);

        $validatedData = $request->validate($validationRules);

        $el = new Gruppo;
        $fields = $request->except(['_token', '_redirect', '_method', '_type', '_module']);
        foreach ($fields as $k => $v) {
            $el->$k = $v;
        }

        DB::beginTransaction();
        try {

            $azienda_id = (Auth::user()->superadmin && $request->has('azienda_id')) ? $request->get('azienda_id') : getAziendaId($el);
            $el->azienda_id = $azienda_id ? $azienda_id : $el->azienda_id;
            $el->save();

            DB::commit();

            return redirect()->route('gruppo.edit', [$el->id])->with('success', 'Elemento creato correttamente');
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
        $el = Gruppo::with('utenti')->find($id);
        if (!$el) abort('404');

        $data['el'] = $el;

        $utenti = Utente::orderBy('extras1')->get();
        $data['utenti'] = $utenti->pluck('label', 'id');
        // Log::info($data['utenti']);

        return view('dashboard.gruppo.create', $data);
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
        $el = Gruppo::find($id);
        if (!$el) abort('404');

        switch ($request->get('_module')) {
            default:
                $validationRules = [
                    'azienda_id' => 'required',
                    'label' => 'required'
                ];
        }

        if(!Auth::user()->superadmin)
            unset($validationRules['azienda_id']);

        $validatedData = $request->validate($validationRules);

        $fields = $request->except(['_token', '_redirect', '_method', '_type', '_module']);
        foreach ($fields as $k => $v) {
            $el->$k = $v;
        }

        DB::beginTransaction();
        try {

            $azienda_id = (Auth::user()->superadmin && $request->has('azienda_id')) ? $request->get('azienda_id') : getAziendaId($el);
            $el->azienda_id = $azienda_id ? $azienda_id : $el->azienda_id;
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

    public function users(Request $request, $gruppo_id) {

        $gr = Gruppo::find($gruppo_id);
        $utentiIds = $request->input('utenti');
        if (count($utentiIds)) {
            foreach ($utentiIds as $utente_id) {
                $utente = Utente::find($utente_id);
                if ($gr && $utente) {
                    try {
                        DB::table('gruppo_utente')->insert([
                            'gruppo_id' => $gruppo_id,
                            'utente_id' => $utente_id,
                        ]);
                    }
                    catch (\Exception $e) {
                        // do nothing
                    }
                }
            }
        }

        $payload = 'Salvataggio avvenuto correttamente!';
        return response()->json(['res' => 'success','payload' => $payload]);
    }

    public function destroyUser(Request $request, $gruppo_id, $utente_id) {
        try {

            $gr = Gruppo::find($gruppo_id);
            $utente = Utente::find($utente_id);

            if ($gr && $utente) {
                DB::table('gruppo_utente')->where([
                    'gruppo_id' => $gruppo_id,
                    'utente_id' => $utente_id,
                ])->delete();
            }

            $payload = 'Cancellazione avvenuta correttamente!';
            return response()->json(['res' => 'success','payload' => $payload]);
        }catch (\Exception $e) {
            Log::info($e->getMessage());

            $payload = 'Errore in fase di cancellazione!';
            return response()->json(['res' => 'error', 'payload' => $payload]);
        }
    }
}
