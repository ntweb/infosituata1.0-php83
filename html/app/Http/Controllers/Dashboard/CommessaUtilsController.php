<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Commessa;
use App\Models\Evento;
use App\Models\Gruppo;
use App\Models\Item;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;

class CommessaUtilsController extends Controller
{
    public function sovrapposizioni(Request $request) {
        if (!Gate::allows('is-commesse-module-enabled')) {
            $data['message'] = 'Il modulo selezionato non è attivo per questo account.';
            $data['message_2'] = 'Il modulo COMMESSE permette di definire le proprie commesse, organizzarle e pianificarle in base allo stato occupazionale dei propri dipendenti, mezzi, attrezzature e materiali. Gantt e grafici di supporto, allegati e schedulatori permettono di monitorare in tempo reale lo stato di avanzamento della commessa.';
            return view('layouts.helpers.module-deactive', $data);
        }

        if(!Gate::allows('can_show_commesse_utility'))
            abort(401);

        $data = [];
        return view('dashboard.commesse-utils.sovrapposizioni.index', $data);
    }

    public function sovrapposizioniGantt(Request $request) {

        if(!Gate::allows('can_show_commesse_utility'))
            abort(401);

        $start = new \Carbon\Carbon($request->input('start'));
        $end = new \Carbon\Carbon($request->input('end'));

        $start = $start->startOfDay();
        $end = $end->endOfDay();

        $data['period'] = \Carbon\CarbonPeriod::create($start, $end);

        $data['elements'] = Commessa::where('label', $request->input('fase'))
            ->where(function($query) use ($start, $end) {
                $query->whereBetween('data_inizio_prevista', [$start, $end])
                    ->orWhereBetween('data_fine_prevista', [$start, $end])
                    ->orWhere(function($query) use ($start, $end) {
                        $query->whereDate('data_inizio_prevista', '<=', $start)->whereDate('data_fine_prevista', '>=', $end);
                    });
            })
            ->with('root')
            ->get();

        $data['events'] = [];

        $check_start = new \Carbon\Carbon($start);
        foreach ($data['elements'] as $node) {
            $b = new \Carbon\Carbon($node->data_inizio_prevista);
            $e = new \Carbon\Carbon($node->data_fine_prevista);

            if ($b->lt($check_start)) {
                $b = $check_start;
            }

            $data['events'][$node->id]['item_id'] = $node->id;
            $data['events'][$node->id]['lines'][] = [
                'type' => 'p',
                // 'from' => $node->data_inizio_prevista,
                'from' => $b->toDateString(),
                'to' => $node->data_fine_prevista,
                'days' => abs($e->diffInDays($b)) + 1,
                'title' => Str::title(strtolower($node->root->label)) . ' / ' . Str::title(strtolower($node->label)),
                'bgColor' => $node->color ?? '#ffffff',
                'class' => null
            ];

        }
        $data['events'] = json_encode(array_values($data['events']));
        return view('dashboard.commesse-utils.sovrapposizioni.show', $data);
    }

    public function fasiSelect2(Request $request)
    {
        $t = $request->input('term', null);
        $query = Commessa::select('label')
            ->whereIn('type', ['fase_lv_1', 'fase_lv_2'])
            ->orderBy('label')
            ->distinct();

        if (trim($t) != '') {
            $query = $query->where('label', 'like', '%'.$t.'%');
        }

        $list = $query->get();

        if ($list->count()) {
            $list = $list->map(function ($item){
                return ['id' => $item->label, 'text' => $item->label];
            });
        }

        $data['results'] = $list;
        return response()->json($data);
    }

    public function scheduler(Request $request) {
        if (!Gate::allows('is-commesse-module-enabled')) {
            $data['message'] = 'Il modulo selezionato non è attivo per questo account.';
            $data['message_2'] = 'Il modulo COMMESSE permette di definire le proprie commesse, organizzarle e pianificarle in base allo stato occupazionale dei propri dipendenti, mezzi, attrezzature e materiali. Gantt e grafici di supporto, allegati e schedulatori permettono di monitorare in tempo reale lo stato di avanzamento della commessa.';
            return view('layouts.helpers.module-deactive', $data);
        }

        if(!Gate::allows('can_show_commesse_utility'))
            abort(401);

        $data = [];
        return view('dashboard.commesse-utils.scheduler.index', $data);
    }

    public function schedulerSelectItems(Request $request) {
        switch ($request->input('_module')) {
            case 'mezzo':
                $title = 'mezzi';

                break;
            case 'attrezzatura':
                $title = 'attrezzi';

                break;
            case 'materiale':
                $title = 'materiali';

                break;
            default:
                $data['gruppi'] = Gruppo::orderBy('label')->get();
                $title = 'utenti';
        }

        $data['title'] = 'Selezione ' .$title;
        $data['search_route'] = route('item.search');

        return view('dashboard.commesse-utils.scheduler.modals.select-items', $data);

    }

    public function showScheduler(Request $request) {
        $items = Item::whereIn('id', $request->input('item'))->get();
        $data['items'] = $items->groupBy('controller');

//        $dates = explode(' - ', $request->input('dates'));
//        $start = strToDate($dates[0])->startOfDay();
//        $end = strToDate($dates[1])->endOfDay();

        $start = new \Carbon\Carbon($request->input('start'));
        $end = new \Carbon\Carbon($request->input('end'));

        $start = $start->startOfDay();
        $end = $end->endOfDay();

        $data['period'] = \Carbon\CarbonPeriod::create($start, $end);

        /** Json da restituire **/
        $itemsIds = $items->pluck('id');
        $eventi = Evento::whereIn('items_id', $itemsIds)
            ->where(function($query) use ($start, $end) {
                $query->whereBetween('start', [$start, $end])
                    ->orWhereBetween('end', [$start, $end])
                    ->orWhere(function($query) use ($start, $end) {
                        $query->whereDate('start', '<=', $start)->whereDate('end', '>=', $end);
                    });
            })
            ->get()
            ->groupBy('items_id');

        $commesse = Commessa::whereIn('item_id', $itemsIds)
            ->where(function($query) use ($start, $end) {
                $query->whereBetween('data_inizio_prevista', [$start, $end])
                    ->orWhereBetween('data_fine_prevista', [$start, $end])
                    ->orWhere(function($query) use ($start, $end) {
                        $query->whereDate('data_inizio_prevista', '<=', $start)->whereDate('data_fine_prevista', '>=', $end);
                    });
            })
            ->with(['parent', 'root'])
            ->get()
            ->groupBy('item_id');

        $data['events'] = [];
        foreach ($eventi as $item_id => $itemGroup) {
            foreach ($itemGroup as $event) {
                $b = new \Carbon\Carbon($event->start);
                $e = new \Carbon\Carbon($event->end);

                if ($b->lt($start)) {
                    $b = $start;
                }

                $data['events'][$item_id]['item_id'] = $item_id;
                $data['events'][$item_id]['lines'][] = [
                    'type' => 'event',
                    'from' => $b->toDateString(),
                    'to' => $event->end,
                    'days' => abs($e->diffInDays($b)) + 1,
                    'title' => Str::title(strtolower($event->titolo))
                ];
            }
        }

        foreach ($commesse as $item_id => $itemGroup) {
            foreach ($itemGroup as $event) {
                $b = new \Carbon\Carbon($event->data_inizio_prevista);
                $e = new \Carbon\Carbon($event->data_fine_prevista);

                if ($b->lt($start)) {
                    $b = $start;
                }

                $data['events'][$item_id]['item_id'] = $item_id;
                $data['events'][$item_id]['lines'][] = [
                    'type' => 'commessa',
                    'from' => $b->toDateString(),
                    'to' => $event->data_fine_prevista,
                    'days' => abs($e->diffInDays($b)) + 1,
                    'title' => Str::title(strtolower($event->root->label . ' / ' . $event->parent->label))
                ];
            }
        }

        $data['events'] = json_encode(array_values($data['events']));
        return view('dashboard.commesse-utils.scheduler.show', $data);
    }

    public function map(Request $request) {
        if ($request->has('coord')) {
            return Commessa::whereNull('parent_id')
                ->whereNull('data_fine_effettiva')
                ->whereNotNull('lat')
                ->select('label as title', 'lat', 'lng')
                ->get();
        }
        $data = [];
        return view('dashboard.commesse-utils.map.index', $data);
    }

    public function schedulerCommesse(Request $request) {
        $data = [];
        return view('dashboard.commesse-utils.scheduler-commesse.index', $data);
    }

    public function showSchedulerCommesse(Request $request) {
        $start = $request->input('start');
        $end = $request->input('end');

        $commesse = Commessa::whereNull('parent_id')
            ->where(function($query) use ($start, $end) {
                $query->whereBetween('data_inizio_prevista', [$start, $end])
                    ->orWhereBetween('data_fine_prevista', [$start, $end])
                    ->orWhere(function($query) use ($start, $end) {
                        $query->whereDate('data_inizio_prevista', '<=', $start)->whereDate('data_fine_prevista', '>=', $end);
                    });
            })->get();


        $data['start'] = $start;
        $data['end'] = $end;
        $data['period'] = \Carbon\CarbonPeriod::create(new \Carbon\Carbon($start), new \Carbon\Carbon($end));

        $data['commesse'] = collect([]);
        foreach ($commesse as $c) {
            $data['commesse']->push(Commessa::defaultOrder()->descendantsAndSelf($c->id)->toTree()->first());
        }

        $ids = $commesse->pluck('id');
        $elements = Commessa::where(function($query) use ($ids) {
            $query->whereIn('id', $ids)->orWhereIn('root_id', $ids)->get();
        })->whereNull('item_id')->get();

        $data['events'] = [];

        $check_start = new \Carbon\Carbon($start);
        foreach ($elements as $node) {
            $b = new \Carbon\Carbon($node->data_inizio_prevista);
            $e = new \Carbon\Carbon($node->data_fine_prevista);

            if ($b->lt($check_start)) {
                $b = $check_start;
            }

            $data['events'][$node->id]['item_id'] = $node->id;
            $data['events'][$node->id]['lines'][] = [
                'type' => 'p',
                // 'from' => $node->data_inizio_prevista,
                'from' => $b->toDateString(),
                'to' => $node->data_fine_prevista,
                'days' => abs($e->diffInDays($b)) + 1,
                'title' => Str::title(strtolower($node->label)),
                'bgColor' => $node->color ?? '#ffffff',
                'class' => null
            ];
        }

        $data['events'] = json_encode(array_values($data['events']));

        return view('dashboard.commesse-utils.scheduler-commesse.show', $data);
    }
}
