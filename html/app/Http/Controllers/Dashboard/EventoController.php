<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Evento;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;

class EventoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Evento::with(['item'])->orderBy('start', 'desc');

        if($request->has('q')) {
            $query->where(function ($query) use ($request) {
                $query->where('titolo', 'like', '%'.$request->get('q').'%')
                    ->orWhereHas('item', function($query) use ($request) {
                        $query->where('extras1', 'like', '%'.$request->get('q').'%')
                            ->orWhere('extras2', 'like', '%'.$request->get('q').'%')
                            ->orWhere('extras3', 'like', '%'.$request->get('q').'%');
                    });
            });
        }

        $data['list'] = $query->paginate(50)->appends(request()->query());
        return view('dashboard.eventi.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {

        if (!Gate::allows('can_create_eventi'))
            abort(401);

        $data = [];
        return view('dashboard.eventi.create', $data);
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
            'titolo' => 'required',
            'descrizione' => 'required',
            'dates' => 'required',
            'items_id' => 'required',
        ];

        $validatedData = $request->validate($validationRules);

        $el = new Evento();
        $fields = $request->except(['_token', '_redirect', '_method', '_type', '_module', 'dates']);
        foreach ($fields as $k => $v) {
            $el->$k = $v;
        }

        try {
            $el->azienda_id = getAziendaId();
            $dates = explode(' - ', $request->input('dates'));
            $el->start = strToDate($dates[0]);
            $el->end = strToDate($dates[1]);

            $el->username = auth()->user()->name;
            $el->save();

            return redirect()->action('Dashboard\EventoController@index', [$el->id])->with('success', 'Salvataggio avvenuto correttamente!');
        }catch (\Exception $e) {

            Log::info($e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Errore in fase di salvataggio!');
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
        if (!Gate::allows('can_create_eventi'))
            abort(401);

        $el = Evento::with('item')->find($id);
        if (!$el) abort('404');

        $data['el'] = $el;

        return view('dashboard.eventi.create', $data);
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
        $el = Evento::find($id);
        if (!$el) abort('404');

        switch ($request->input('_module', null)) {
            default:
                $validationRules = [
                    'titolo' => 'required',
                    'descrizione' => 'required',
                    'dates' => 'required',
                    'items_id' => 'required',
                ];
        }

        $validatedData = $request->validate($validationRules);

        //if ($request->get('_module', null) == null) {
        $fields = $request->except(['_token', '_redirect', '_method', '_type', '_module', 'dates']);
        foreach ($fields as $k => $v) {
            $el->$k = $v;
        }
        //}

        try {

            $dates = explode(' - ', $request->input('dates'));
            $el->start = strToDate($dates[0]);
            $el->end = strToDate($dates[1]);

            $el->save();
            $payload = 'Salvataggio avvenuto correttamente!';
            if ($request->get('_type') == 'json')
                return response()->json(['res' => 'success','payload' => $payload]);

            return redirect()->back()->with('success', 'Salvataggio avvenuto correttamente!');
        }catch (\Exception $e) {
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

        $el = Evento::find($id);
        if (!$el) abort(404);

        try {

            $el->delete();

            return redirect()->to($request->get('_redirect'))->with('success', 'A breve il sistema cancellerà l\'elemento!');
        }catch (\Exception $e) {
            Log::info($e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Errore in fase di salvataggio!');
        }
    }

}
