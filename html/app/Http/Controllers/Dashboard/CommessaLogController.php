<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\CommessaLog;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;

class CommessaLogController extends Controller
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
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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

        if ($request->has('delete')) {
            $data['el'] = CommessaLog::where('id', $id)
                ->where('commesse_id', $request->input('commesse_id'))
                ->first();
            if (!$data['el']) abort('404');

            $data['title'] = 'Eliminazione log';
            $data['action'] = route('commessa-log.destroy', $id);
            return view('dashboard.commesse.modals.delete-log', $data);
        }
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $el = CommessaLog::find($id);
        if (!$el) abort(404);

        try {
            $el->delete();

            $payload = 'Cancellazione avvenuta correttamente!';
            return response()->json(['res' => 'success','payload' => $payload]);
        }catch (\Exception $e) {
            Log::info($e->getMessage());

            $payload = 'Errore in fase di cancellazione!';
            return response()->json(['res' => 'error', 'payload' => $payload]);
        }
    }
}
