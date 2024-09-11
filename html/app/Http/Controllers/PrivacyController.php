<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PrivacyController extends Controller
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
        $data['azienda'] =  auth()->user()->azienda ? auth()->user()->azienda : null;
        if (!$data['azienda'])
            $data['azienda'] = auth()->user()->utente ? auth()->user()->utente->azienda : null;

        // dd($data['azienda']);

        return view('dashboard.privacy.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Log::info($request->all());
        $validationRules = [
            'privacy_fl_1' => 'required',
//            'privacy_fl_2' => 'required',
//            'privacy_fl_4' => 'required',
//            'privacy_fl_5' => 'required',
        ];

        $validatedData = $request->validate($validationRules);

        $u = Auth::user();
        $u->privacy_fl_1 = $request->has('privacy_fl_1') ? \Carbon\Carbon::now() : null;
        $u->privacy_fl_2 = $request->has('privacy_fl_2') ? \Carbon\Carbon::now() : null;
        $u->privacy_fl_3 = $request->has('privacy_fl_3') ? \Carbon\Carbon::now() : null;
        $u->privacy_fl_4 = $request->has('privacy_fl_4') ? \Carbon\Carbon::now() : null;
        $u->privacy_fl_5 = $request->has('privacy_fl_5') ? \Carbon\Carbon::now() : null;

        $u->save();
        return redirect()->action('DashboardController@index');
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
    public function destroy($id)
    {
        //
    }
}
