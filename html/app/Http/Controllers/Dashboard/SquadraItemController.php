<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Item;
use App\Models\Squadra;
use App\Models\SquadraItem;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SquadraItemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $list = SquadraItem::where('squadre_id', $request->input('squadra_id'))
            ->with('item')
            ->get();

        $data['squadra_id'] = $request->input('squadra_id');
        $data['elements'] = $list->sortBy('item.controller');

        return view('dashboard.squadre.forms.item-rows', $data);
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
        $el = Item::whereIn('controller', ['utente', 'mezzo', 'attrezzatura'])->where('id', $request->input('id'))->first();
        if (!$el) abort(404);

        $squadra = Squadra::find($request->input('squadra_id'));
        if (!$squadra) abort(404);

        try {
            $created_at = \Carbon\Carbon::now();
            DB::table('squadre_items')->insert([
                'squadre_id' => $squadra->id,
                'item_id' => $el->id,
                'created_at' => $created_at,
                'updated_at' => $created_at,
            ]);

            $payload = 'Salvataggio avvenuto correttamente!';
            return response()->json(['res' => 'success','payload' => $payload]);
        }
        catch (\Exception $e) {
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
        //
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
    public function destroy(Request $request, $id)
    {
        $squadra = Squadra::find($request->input('squadra_id'));
        if (!$squadra) abort(404);

        $el = SquadraItem::where('id', $id)->where('squadre_id', $squadra->id)->first();
        if (!$el) abort(404);

        try {
            $el->delete();
            $payload = 'Salvataggio avvenuto correttamente!';
            return response()->json(['res' => 'success','payload' => $payload]);
        }
        catch (\Exception $e) {
            $payload = 'Errore in fase di salvataggio!';
            return response()->json(['res' => 'error', 'payload' => $payload]);
        }
    }

    public function add(Request $request) {
        $costo_item_orario_previsto = $request->input('costo_item_orario_previsto', []);

        try {
            foreach ($costo_item_orario_previsto as $id => $co) {
                DB::table('squadre_items')
                    ->where('id', $id)
                    ->update([
                        'costo_item_orario_previsto' => $co
                    ]);
            }

            $payload = 'Salvataggio avvenuto correttamente!';
            return response()->json(['res' => 'success','payload' => $payload]);
        }
        catch (\Exception $e) {
            $payload = 'Errore in fase di salvataggio!';
            return response()->json(['res' => 'error', 'payload' => $payload]);
        }
    }

}
