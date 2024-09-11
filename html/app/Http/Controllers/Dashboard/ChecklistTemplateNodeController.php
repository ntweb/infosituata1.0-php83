<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\ChecklistTemplate;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Str;

class ChecklistTemplateNodeController extends Controller
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
        $parent = ChecklistTemplate::find($request->input('node'));
        if (!$parent) abort('404');

        $data['parent'] = $parent;
        if ($request->has('_module')) {
            // fare i check di inserimento all'interno di unaa sezione
            if ($parent->isRoot()) abort(404);

            // Aprire il form del modulo selezionato
            $data['title'] = 'Crea elemento';
            $data['sub_title'] = null;
            $data['action'] = action('Dashboard\ChecklistTemplateNodeController@store', ['_parent_id' => $parent->id, '_module' => $request->input('_module')]);
            return view('dashboard.checklist-tpl.modals.create-node-'.$request->input('_module'), $data);
        }


        $data['title'] = 'Crea sezione';
        $data['sub_title'] = null;
        $data['action'] = action('Dashboard\ChecklistTemplateNodeController@store', ['_parent_id' => $parent->id]);
        return view('dashboard.checklist-tpl.modals.create-node', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $parent = ChecklistTemplate::find($request->input('_parent_id'));
        if (!$parent) abort('404');

        $validationRules = ['label' => 'required'];
        $validatedData = $request->validate($validationRules);

        $fields = $request->except(['_token', '_redirect', '_method', '_type', '_module']);
        $el = new ChecklistTemplate;

        foreach ($fields as $k => $v) {
            $el->$k = $v;
        }

        try {
            $el->key = Str::uuid();
            $el->azienda_id = $parent->azienda_id;
            $el->root_id = $parent->isRoot() ? $parent->id : $parent->root_id;
            $el->type = $parent->isRoot() ? 'sezione' : $request->input('_module');
            $el->required = $request->has('required') ? '1' : '0';

            $el->appendToNode($parent)->save();

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
    public function edit(Request $request, $id)
    {
        $data['el'] = ChecklistTemplate::find($id);
        if (!$data['el']) abort('404');

        if ($request->has('delete')) {
            $data['title'] = 'Eliminazione: ' . $data['el']->label;
            $data['action'] = action('Dashboard\ChecklistTemplateNodeController@destroy', $id);
            return view('dashboard.checklist-tpl.modals.delete-node', $data);
        }

        if ($data['el']->type != 'sezione') {
            // Aprire il form del modulo selezionato
            $data['title'] = 'Modifica elemento';
            $data['sub_title'] = null;
            $data['action'] = action('Dashboard\ChecklistTemplateNodeController@update', $data['el']->id);
            return view('dashboard.checklist-tpl.modals.create-node-'.$data['el']->type, $data);
        }

        $data['title'] = 'Modifica elemento';
        $data['action'] = action('Dashboard\ChecklistTemplateNodeController@update', $id);
        return view('dashboard.checklist-tpl.modals.create-node', $data);
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
        $el = ChecklistTemplate::find($id);
        if (!$el) abort('404');

        $validationRules = ['label' => 'required'];
        $validatedData = $request->validate($validationRules);

        $fields = $request->except(['_token', '_redirect', '_method', '_type', '_module']);

        foreach ($fields as $k => $v) {
            $el->$k = $v;
        }

        try {
            $el->required = $request->has('required') ? '1' : '0';
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
        $el = ChecklistTemplate::find($id);
        if (!$el) abort(404);

        DB::beginTransaction();
        try {

            $el->delete();
            DB::commit();

            $payload = 'Cancellazione avvenuta correttamente!';
            return response()->json(['res' => 'success','payload' => $payload]);
        }catch (\Exception $e) {
            DB::rollBack();
            Log::info($e->getMessage());

            $payload = 'Errore in fase di cancellazione!';
            return response()->json(['res' => 'error', 'payload' => $payload]);
        }
    }

    public function move($id, $versus) {
        $node = ChecklistTemplate::find($id);
        if (!$node) abort('404');

        try {
            if ($versus == 'up')
                $node->up();
            else
                $node->down();

            $payload = 'Salvataggio avvenuto correttamente!';
            return response()->json(['res' => 'success','payload' => $payload]);

        }catch (\Exception $e) {

            Log::info($e->getMessage());
            $payload = 'Errore in fase di salvataggio!';
            return response()->json(['res' => 'error', 'payload' => $payload]);
        }
    }
}
