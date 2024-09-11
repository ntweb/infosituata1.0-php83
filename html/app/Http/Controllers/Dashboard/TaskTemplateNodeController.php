<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\TaskTemplate;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class TaskTemplateNodeController extends Controller
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
        $parent = TaskTemplate::find($request->input('node'));
        if (!$parent) abort('404');


        $data['parent'] = $parent;

        $data['title'] = 'Crea task';
        $data['sub_title'] = null;
        $data['action'] = action('Dashboard\TaskTemplateNodeController@store', ['_parent_id' => $parent->id]);
        return view('dashboard.tasks-tpl.modals.create-node', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $parent = TaskTemplate::find($request->input('_parent_id'));
        if (!$parent) abort('404');

        $validationRules = ['label' => 'required'];
        $validatedData = $request->validate($validationRules);

        $fields = $request->except(['_token', '_redirect', '_method', '_type', '_module']);
        $el = new TaskTemplate;

        foreach ($fields as $k => $v) {
            $el->$k = $v;
        }

        try {
            $id = Str::uuid();

            $el->id = $id;
            $el->azienda_id = $parent->azienda_id;
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
        $data['el'] = TaskTemplate::find($id);
        if (!$data['el']) abort('404');

        if ($request->has('delete')) {
            $data['title'] = 'Eliminazione: ' . $data['el']->label;
            $data['action'] = action('Dashboard\TaskTemplateNodeController@destroy', $id);
            return view('dashboard.tasks-tpl.modals.delete-node', $data);
        }


        $data['siblings'] = $data['el']->siblings()->get()->pluck('label', 'id');
        $data['siblings']->prepend('-', '');

        // $data['dependent_node'] = CommessaTemplate::where('execute_after_id', $id)->get()->pluck('id', 'id');

        // Log::info($data['siblings']);
        // Log::info($data['dependent_node']);

        /*
        if ($data['dependent_node']->count()) {
            $data['siblings'] = $data['siblings']->filter(function($label, $id) use ($data) {
                return $data['dependent_node']->contains(function($value, $key) use ($id) {
                    // Log::info($key .' '. $id);
                    return $key !== $id;
                });
            });
        }
        */

        $data['title'] = 'Modifica elemento';
        $data['action'] = action('Dashboard\TaskTemplateNodeController@update', $id);
        return view('dashboard.tasks-tpl.modals.create-node', $data);
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
        $el = TaskTemplate::find($id);
        if (!$el) abort('404');

        $validationRules = ['label' => 'required'];
        $validatedData = $request->validate($validationRules);

        $fields = $request->except(['_token', '_redirect', '_method', '_type', '_module']);

        foreach ($fields as $k => $v) {
            $el->$k = $v;
        }

        /** controllo dipendenza loop **/
        /**
        if ($request->input('execute_after_id', null)) {
            $node1 = CommessaTemplate::find($request->input('execute_after_id'));
            if ($node1->execute_after_id) {
                $node = CommessaTemplate::where('id', $node1->execute_after_id)->where('execute_after_id', $el->id)->first();
                if ($node) {
                    return response()->json(['res' => 'error', 'payload' => 'Errore dipendenza, non è possibile scegliere ' . $node1->label . ' rischio di loop'], 422);
                }
            }
        }
        **/

        try {
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
        $el = TaskTemplate::find($id);
        if (!$el) abort(404);

        DB::beginTransaction();
        try {

            // DB::table('tasks_templates')->where('execute_after_id', $id)->update(['execute_after_id' => null]);
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
        $node = TaskTemplate::find($id);
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
