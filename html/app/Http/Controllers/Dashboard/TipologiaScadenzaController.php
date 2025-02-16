<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\InfosituataModuleDetailScadenza;
use App\Models\InfosituataModuleDetailScadenzaLimited;
use Illuminate\Auth\Access\Gate;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class TipologiaScadenzaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        if (!\Illuminate\Support\Facades\Gate::allows('can_create_tip_scadenza'))
            abort(401);

        $query = InfosituataModuleDetailScadenzaLimited::with(['moduleDetail', 'moduleDetail.module']);
        if (Auth::user()->superadmin && $request->has('azienda'))
            $query->whereAziendaId($request->get('azienda'));
        elseif (Auth::user()->superadmin)
            $query->whereAziendaId(0);


        $data['list'] = $query->paginate(10000)->appends(request()->query());
        return view('dashboard.tipologiascadenze.index', $data);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        if (!\Illuminate\Support\Facades\Gate::allows('can_create_tip_scadenza'))
            abort(401);

        $data['azienda_id'] = (Auth::user()->superadmin && $request->has('azienda')) ? $request->get('azienda') : null;

        return view('dashboard.tipologiascadenze.create', $data);
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

        $el = new InfosituataModuleDetailScadenza();
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

            return redirect()->route('tipologia-scadenza.edit', [$el->id])->with('success', 'Salvataggio avvenuto correttamente!');
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
        if (!\Illuminate\Support\Facades\Gate::allows('can_create_tip_scadenza'))
            abort(401);

        $el = InfosituataModuleDetailScadenza::find($id);
        if (!$el) abort('404');

        $data['el'] = $el;
        return view('dashboard.tipologiascadenze.create', $data);
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
        $el = InfosituataModuleDetailScadenza::find($id);
        if (!$el) abort('404');

        switch ($request->get('_module', null)) {
            default:
                $validationRules = [
                    'label' => 'required'
                ];
        }

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
}
