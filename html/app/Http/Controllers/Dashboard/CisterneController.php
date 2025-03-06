<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Cisterna;
use App\Models\Gruppo;
use App\Models\Mezzo;
use App\Models\Utente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;

class CisterneController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['list'] = Cisterna::all();
        return view('dashboard.cisterne.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (!Gate::allows('can_create_mezzi'))
            abort(403);

        $gruppiIds = Gruppo::get()->pluck('id', 'id');
        $data['gruppi'] = Gruppo::whereIn('id', $gruppiIds)->select('id', 'label')->get()->pluck('label', 'id');
        $data['gruppiSel'] = [];

        return view('dashboard.cisterne.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validationRules = [
            'label' => 'required',
        ];

        $validatedData = $request->validate($validationRules);

        $el = new Cisterna();
        $el->azienda_id = getAziendaId();

        $fields = $request->except(['_token', '_redirect', '_method', '_type', '_module']);
        foreach ($fields as $k => $v) {
            switch ($k) {
                case 'gruppi_ids':
                    $el->$k = json_encode($v);
                    break;
                default:
                    $el->$k = $v;
            }
        }

        try {
            $el->save();

            $payload = 'Salvataggio avvenuto correttamente!';
            if ($request->get('_type') == 'json')
                return response()->json(['res' => 'success','payload' => $payload]);

            return redirect()->route('cisterne.index', [$el->id])->with('success', 'Salvataggio avvenuto correttamente!');
        }
        catch (\Exception $e) {

            Log::info($e->getMessage());
            $payload = 'Errore in fase di salvataggio!';
            if ($request->get('_type') == 'json')
                return response()->json(['res' => 'error', 'payload' => $payload]);

            return redirect()->back()->withInput()->with('error', 'Errore in fase di salvataggio!');
        }

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $el = Cisterna::find($id);
        if (!$el) {
            abort(404);
        }

        $data['el'] = $el;
        $data['gruppiSel'] = json_decode($el->gruppi_ids);
        // make value equal at key
        $data['gruppiSel'] = array_combine($data['gruppiSel'], $data['gruppiSel']);

        $gruppiIds = Gruppo::get()->pluck('id', 'id');
        $data['gruppi'] = Gruppo::whereIn('id', $gruppiIds)->select('id', 'label')->get()->pluck('label', 'id');

        return view('dashboard.cisterne.create', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $el = Cisterna::find($id);
        if (!$el) {
            abort(404);
        }

        $validationRules = [
            'label' => 'required',
        ];

        $validatedData = $request->validate($validationRules);

        $fields = $request->except(['_token', '_redirect', '_method', '_type', '_module']);
        foreach ($fields as $k => $v) {
            switch ($k) {
                case 'gruppi_ids':
                    $el->$k = json_encode($v);
                    break;
                default:
                    $el->$k = $v;
            }
        }

        try {
            $el->save();

            $payload = 'Salvataggio avvenuto correttamente!';
            if ($request->get('_type') == 'json')
                return response()->json(['res' => 'success','payload' => $payload]);

            return redirect()->route('cisterne.index', [$el->id])->with('success', 'Salvataggio avvenuto correttamente!');
        }
        catch (\Exception $e) {

            Log::info($e->getMessage());
            $payload = 'Errore in fase di salvataggio!';
            if ($request->get('_type') == 'json')
                return response()->json(['res' => 'error', 'payload' => $payload]);

            return redirect()->back()->withInput()->with('error', 'Errore in fase di salvataggio!');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
