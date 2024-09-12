<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Commessa;
use App\Models\Squadra;
use App\Models\SquadraItem;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;

class SquadraController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(Gate::denies('can_create_commesse_squadre'))
            abort(401);

        $data['list'] = Squadra::paginate(500)->appends(request()->query());
        return view('dashboard.squadre.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(Gate::denies('can_create_commesse_squadre'))
            abort(401);

        $data = [];
        return view('dashboard.squadre.create', $data);
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
            'label' => 'required',
        ];

        $validatedData = $request->validate($validationRules);

        $el = new Squadra();
        $fields = $request->except(['_token', '_redirect', '_method', '_type', '_module']);
        foreach ($fields as $k => $v) {
            $el->$k = $v;
        }

        DB::beginTransaction();
        try {
            $el->azienda_id = getAziendaId();
            $el->save();
            DB::commit();

            return redirect()->route('squadra.edit', [$el->id])->with('success', 'Salvataggio avvenuto correttamente!');
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
        if(Gate::denies('can_create_commesse_squadre'))
            abort(401);

        $el = Squadra::find($id);
        if (!$el) abort('404');

        $data['el'] = $el;

        $data['squadra_id'] = $id;
        $data['elements'] = SquadraItem::where('squadre_id', $el->id)->with('item')->get();
        $data['elements'] = $data['elements']->sortBy('item.controller');

        return view('dashboard.squadre.create', $data);
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
        $el = Squadra::find($id);
        if (!$el) abort('404');

        $validationRules = [
            'label' => 'required',
        ];

        $validatedData = $request->validate($validationRules);

        $fields = $request->except(['_token', '_redirect', '_method', '_type', '_module']);
        foreach ($fields as $k => $v) {
            $el->$k = $v;
        }

        DB::beginTransaction();
        try {
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
    public function destroy(Request $request, $id)
    {
        if(!$request->has('confirm'))
            return redirect()->back()->withInput()->with('error', 'E\' necessario confermare la cancellazione!');

        $el = Squadra::find($id);
        if (!$el) abort(404);

        try {
            $el->delete();

            return redirect()->to($request->get('_redirect'))->with('success', 'A breve il sistema cancellerà l\'elemento!');
        }catch (\Exception $e) {

            Log::info($e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Errore in fase di salvataggio!');
        }
    }

    public function search(Request $request) {
        $data = [];

        $search = $request->input('search', '');
        $data['items'] = Squadra::where('label', 'like', '%'.$search.'%')
            ->get();

        /** Richiesta proveniente dall assegnazionne squadra a commessa **/
        $node = Commessa::find($request->input('node'));

        $data['node'] = $node;
        return view('dashboard.squadre.search', $data);
    }
}
