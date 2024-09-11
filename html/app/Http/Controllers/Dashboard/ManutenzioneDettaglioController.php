<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Manutenzione;
use App\Models\ManutenzioneDetail;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
class ManutenzioneDettaglioController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $data['manutenzione'] = Manutenzione::find($request->id);
        if (!$data['manutenzione']) abort(404);

        return view('dashboard.manutenzione-dettaglio.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $manutenzione = Manutenzione::find($request->input('_manutenzione_id'));
        if (!$manutenzione) abort(404);

        $validationRules = [
            'ricambi_id' => 'required',
        ];

        $validatedData = $request->validate($validationRules);

        $el = new ManutenzioneDetail();
        $fields = $request->except(['_token', '_redirect', '_method', '_type', '_module', '_manutenzione_id']);
        foreach ($fields as $k => $v) {
            $el->$k = $v;
        }

        DB::beginTransaction();
        try {

            $el->manutenzioni_id = $manutenzione->id;
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
        $data['el'] = ManutenzioneDetail::find($id);
        if (!$data['el']) abort(404);

        $data['manutenzione'] = Manutenzione::find($data['el']->manutenzioni_id);
        if (!$data['manutenzione']) abort(404);

        $data['action'] = action('Dashboard\ManutenzioneDettaglioController@update', [$id, '_type' => 'json']);

        return view('dashboard.manutenzione-dettaglio.create', $data);
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

        $el = ManutenzioneDetail::find($id);
        if (!$el) abort(404);

        $manutenzione = Manutenzione::find($el->manutenzioni_id);
        if (!$manutenzione) abort(404);

        $validationRules = [
            'ricambi_id' => 'required',
        ];

        $validatedData = $request->validate($validationRules);

        $fields = $request->except(['_token', '_redirect', '_method', '_type', '_module', '_manutenzione_id']);
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

        $el = ManutenzioneDetail::find($id);
        if (!$el) abort('404');

        $_mm = Manutenzione::find($el->manutenzioni_id);
        if (!$_mm) abort('404');

        DB::beginTransaction();
        try {

            $el->delete();

            DB::commit();
            return basicSaveResponse($request, false);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::info($e->getMessage());

            return basicSaveResponse($request, true, $e->getMessage());
        }
    }
}
