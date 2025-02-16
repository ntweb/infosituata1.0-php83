<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Iva;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class IvaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Iva::orderBy('codice');

        if($request->has('q')) {
            $query->where(function ($query) use ($request) {
                $query->where('codice', 'like', '%'.$request->get('q').'%')
                    ->orWhere('descrizione', 'like', '%'.$request->get('q').'%')
                    ->orWhere('descrizione_estesa', 'like', '%'.$request->get('q').'%');
            });

            $data['list'] = $query->paginate(500);
            return view('dashboard.iva.tables.index', $data);
        }

        if($request->has('_render_table')) {
            $data['list'] = $query->paginate(500);
            return view('dashboard.iva.tables.index', $data);
        }

        $data['list'] = $query->paginate(500);
        return view('dashboard.iva.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = [];
        return view('dashboard.iva.modals.iva-edit', $data);
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
            'codice' => 'required',
        ];

        $validatedData = $request->validate($validationRules);
        $el = new Iva;
        $fields = $request->except(['_token', '_redirect', '_method', '_type', '_module', 'fl_esenzione', 'fl_spese_bollo']);
        foreach ($fields as $k => $v) {
            $el->$k = $v;
        }


        try {
            $azienda_id = getAziendaId();
            $el->azienda_id = $azienda_id ? $azienda_id : $el->azienda_id;

            $el->fl_esenzione = $request->has('fl_esenzione') ? '1' : '0';
            $el->fl_spese_bollo = $request->has('fl_spese_bollo') ? '1' : '0';

            $el->id = Str::uuid();

            $el->save();

            $payload = 'Salvataggio avvenuto correttamente!';
            return response()->json(['res' => 'success','payload' => $payload]);

        }catch (\Exception $e) {
            Log::info($e->getMessage());

            $payload = 'Errore in fase di salvataggio!';
            return response()->json(['res' => 'error', 'payload' => $payload]);
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
        $data['el'] = Iva::find($id);
        if (!$data['el']) abort(404);

        return view('dashboard.iva.modals.iva-edit', $data);
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
        $el = Iva::find($id);
        if (!$el) abort(404);

        $validationRules = [
            'codice' => 'required',
        ];

        $validatedData = $request->validate($validationRules);
        $fields = $request->except(['_token', '_redirect', '_method', '_type', '_module', 'fl_esenzione', 'fl_spese_bollo']);
        foreach ($fields as $k => $v) {
            $el->$k = $v;
        }


        try {
            $el->fl_esenzione = $request->has('fl_esenzione') ? '1' : '0';
            $el->fl_spese_bollo = $request->has('fl_spese_bollo') ? '1' : '0';

            $el->save();

            $payload = 'Salvataggio avvenuto correttamente!';
            return response()->json(['res' => 'success','payload' => $payload]);

        }catch (\Exception $e) {
            Log::info($e->getMessage());

            $payload = 'Errore in fase di salvataggio!';
            return response()->json(['res' => 'error', 'payload' => $payload]);
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
