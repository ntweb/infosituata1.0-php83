<?php

namespace App\Http\Controllers\Dashboard;

use App\Events\Illuminate\Events\AttachmentS3ParentDeleted;
use App\Models\Attrezzatura;
use App\Models\Commessa;
use App\Models\Evento;
use App\Models\Item;
use App\Models\Materiale;
use App\Models\Mezzo;
use App\Models\Utente;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ItemController extends Controller
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
    public function edit($id)
    {
        $item = Item::find($id);
        if (!$item) abort(404);

        switch ($item->controller) {
            case 'utente':
                return redirect()->route('user.edit', $id);

            case 'mezzo':
                return redirect()->route('mezzi.edit', $id);

            case 'attrezzatura':
                return redirect()->route('attrezzature.edit', $id);

            case 'risorsa':
                return redirect()->route('risorse.edit', $id);
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
        $el = Item::find($id);
        if (!$el) abort('404');

        switch ($request->get('_module')) {
            case 'sedi':
                $validationRules = [];
                break;

            default:
                $validationRules = [];
        }

        if(!Auth::user()->superadmin)
            unset($validationRules['azienda_id']);

        $validatedData = $request->validate($validationRules);

        if ($request->get('_module', null) == 'sedi') {
            $sediIds = collect($request->get('sedi', []))->values()->toArray();
            $el->sedi()->sync($sediIds);
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

        $el = Item::find($id);
        if (!$el) abort(404);
        DB::beginTransaction();
        try {
            $el->deleted_at = \Carbon\Carbon::now();
            $el->save();

            /** Deleting s3 attachments **/
            event(new AttachmentS3ParentDeleted($id, 'items'));

            DB::commit();

            return redirect()->to($request->get('_redirect'))->with('success', 'A breve il sistema cancellerà l\'elemento!');
        }catch (\Exception $e) {
            DB::rollBack();
            Log::info($e->getMessage());

            return redirect()->back()->withInput()->with('error', 'Errore in fase di salvataggio!');
        }
    }

    public function search(Request $request) {
        $data = [];

        $search = $request->input('search', '');
        switch ($request->input('_module')) {
            case 'mezzo':
                $data['items'] = Mezzo::where('extras1', 'like', '%'.$search.'%')
                    ->orWhere('extras3', 'like', '%'.$search.'%')
                    ->orWhere('tags', 'like', '%'.$search.'%')
                    ->get();
                break;

            case 'attrezzatura':
                $data['items'] = Attrezzatura::where('extras1', 'like', '%'.$search.'%')
                    ->orWhere('extras3', 'like', '%'.$search.'%')
                    ->orWhere('tags', 'like', '%'.$search.'%')
                    ->get();
                break;

            case 'materiale':
                $data['items'] = Materiale::where('extras1', 'like', '%'.$search.'%')
                    ->orWhere('extras2', 'like', '%'.$search.'%')
                    ->orWhere('tags', 'like', '%'.$search.'%')
                    ->get();
                break;

            default:
                $data['items'] = Utente::where('extras1', 'like', '%'.$search.'%')
                    ->orWhere('extras2', 'like', '%'.$search.'%')
                    ->orWhere('tags', 'like', '%'.$search.'%')
                    ->with('gruppi')
                    ->get();

                if ($request->input('gruppo_id', null)) {
                    $data['items'] = $data['items']->filter(function($item) use ($request) {
                        $gruppi = $item->gruppi->pluck('id', 'id');
                        return isset($gruppi[$request->input('gruppo_id')]);
                    }) ;
                }
        }


        /** Richiesta proveniente dall assegnazionne risorsa a commessa **/
        if($request->has('node')) {
            $data['nodeOther'] = [];
            $data['eventi'] = [];
            // dd($data['items']);
            $node = Commessa::find($request->input('node'));

            // Serve per verificare se l'item non sia stato già associato al nodo selezionato
            $data['children'] = $node->children()->get()->pluck('item_id', 'item_id');

            // Serve per verificare se l'item è stato associato nello stesso periodo ad un'altra fase
            $nodeOther = Commessa::where('parent_id', '!=', $node->id)
                ->whereIn('type', ['utente', 'mezzo', 'attrezzatura'])
                ->where(function($query) use ($node) {
                    $query->whereBetween('data_inizio_prevista', [$node->data_inizio_prevista, $node->data_fine_prevista])
                        ->orWhereBetween('data_fine_prevista', [$node->data_inizio_prevista, $node->data_fine_prevista])
                        ->orWhere(function($query) use ($node) {
                            $query->where('data_inizio_prevista', '<=', $node->data_inizio_prevista)
                                ->where('data_fine_prevista', '>=',$node->data_fine_prevista);
                        });
                })
                ->with('root', 'parent')
                ->get();


            foreach ($nodeOther as $n) {
                $data['nodeOther'][$n->item_id] = [
                    'message' => 'Comm: '.$n->root->label.', fase: '.$n->parent->label,
                    'period' => data($n->data_inizio_prevista) .' - '. data($n->data_fine_prevista)
                ];
            }

            // serve per verificare se ci sono eventi associati durante il periodo all'item
            $eventi = Evento::whereBetween('start', [$node->data_inizio_prevista, $node->data_fine_prevista])
                ->orWhereBetween('end', [$node->data_inizio_prevista, $node->data_fine_prevista])
                ->orWhere(function($query) use ($node) {
                    $query->where('start', '<=', $node->data_inizio_prevista)
                        ->where('end', '>=',  $node->data_fine_prevista);
                })
                ->get();

            foreach ($eventi as $n) {
                $data['eventi'][$n->items_id] = [
                    'id' => $n->id,
                    'titolo' => 'Evento: '.$n->titolo,
                    'descrizione' => $n->descrizione,
                    'period' => data($n->start) .' - '. data($n->end)
                ];
            }

            $data['node'] = $node;
            return view('dashboard.item.search', $data);
        }

        return view('dashboard.commesse-utils.scheduler.item-search', $data);
    }

    public function select2(Request $request)
    {
        $t = $request->input('term', null);
        $list = [];


        if (trim($t) != '') {

            if ($request->input('_controller', '') != '') {
                // Log::info('1');
                $controller = [];
                switch ($request->input('_controller')) {
                    case 'utenti':
                        $controller[] = 'utente';
                        break;
                    case 'mezzi':
                        $controller[] = 'mezzo';
                        break;
                    case 'attrezzature':
                        $controller[] = 'attrezzatura';
                        break;
                    case 'materiali':
                        $controller[] = 'materiale';
                        break;
                    case 'risorse':
                        $controller[] = 'risorsa';
                        break;
                }

                $list = Item::whereIn('controller', $controller)
                    ->where(function($query) use ($t){
                        $query->where('extras1', 'like', '%'.$t.'%')
                            ->orWhere('extras2', 'like', '%'.$t.'%')
                            ->orWhere('extras3', 'like', '%'.$t.'%');
                    })->get();
            }
            else {
                // Log::info('2');
                $list = Item::whereIn('controller', ['utente', 'mezzo', 'attrezzatura'])
                    ->where(function($query) use ($t){
                        $query->where('extras1', 'like', '%'.$t.'%')
                            ->orWhere('extras2', 'like', '%'.$t.'%')
                            ->orWhere('extras3', 'like', '%'.$t.'%');
                    })->get();
            }
        }

        if (count($list)) {
            $list = $list->map(function ($item){
                return ['id' => $item->id, 'text' => $item->label];
            });
        }

        $data['results'] = $list;
        return response()->json($data);
    }
}

