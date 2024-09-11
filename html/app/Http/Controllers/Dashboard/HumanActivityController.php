<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\HumanActivity;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;

class HumanActivityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        if(!Gate::allows('can_create_ham'))
            abort(401);

        $query = HumanActivity::with(['azienda', 'utente', 'device', 'device.type']);
        if (Auth::user()->superadmin && $request->has('azienda'))
            $query->whereAziendaId($request->get('azienda'));

        $data['today'] = [];
        if($request->has('q')) {
            $query->whereHas('utente', function (Builder $query) use ($request) {
                $terms = explode(' ', $request->get('q'));
                foreach ($terms as $t) {
                    if (trim($t) != '')
                        $query->where('extras1', 'like', '%'.$t.'%')
                            ->orWhere('extras2', 'like', '%'.$t.'%')
                            ->orWhere('extras3', 'like', '%'.$t.'%');
                }
            });
        }
        else {
            $data['today'] = HumanActivity::with(['azienda', 'utente', 'device'])->orderBy('id', 'desc')->whereDate("created_at", \Carbon\Carbon::now()->toDateString())->get();
        }

        $data['list'] = $query->orderBy('id', 'desc')->whereDate("created_at", "<>", \Carbon\Carbon::now()->toDateString())->paginate(1500)->appends(request()->query());

        return view('dashboard.humanactivity.index', $data);

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
        if(!Gate::allows('can_create_ham'))
            abort(401);

        $el = HumanActivity::with(['azienda', 'utente', 'device', 'checkedBy', 'device.type'])->find($id);
        if (!$el) abort(404);

        $data['el'] = $el;
        return view('dashboard.humanactivity.show', $data);
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
        $el = HumanActivity::find($id);
        if (!$el) abort('404');

        DB::beginTransaction();
        try {

            $el->checked_at = \Carbon\Carbon::now();
            $el->checked_by = Auth::user()->id;
            $el->save();

            DB::commit();

            $payload = $el->id;
            return response()->json(['res' => 'success','payload' => $payload]);

        }catch (\Exception $e) {
            DB::rollBack();

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

    public function save(Request $request) {

        if (!$request->has('id'))
            return response()->json(['res' => 'error', 'payload' => 'Identifier error']);

        $device = DB::table('devices')->whereIdentifier($request->get('id'))->whereActive('1')->first();
        if (!$device) return response()->json(['res' => 'error', 'payload' => 'Cannot save data']);

        DB::beginTransaction();
        try {

            $el = new HumanActivity;
            $el->device_id = $device->id;
            $el->utente_id = $device->utente_id;
            $el->azienda_id = $device->azienda_id;
            $el->stress_level = $request->get('stress_level', 'nd');
            $el->hrm = $request->get('hrm', 'nd');
            $el->hrm_bpm = $request->get('hrm_bpm', 0);
            $el->man_down = $request->has('man_down') ? 'down' : 'up';
            $el->latitude = $request->get('latitude', null);
            $el->longitude = $request->get('longitude', null);
            $el->alert = $request->get('alert', 'auto');

            $el->save();

            DB::commit();

            $payload = $el->id;
            return response()->json(['res' => 'success','payload' => $payload]);

        }catch (\Exception $e) {
            DB::rollBack();

            Log::info($e->getMessage());
            $payload = 'Errore in fase di salvataggio!';
            return response()->json(['res' => 'error', 'payload' => $payload]);
        }
    }
}
