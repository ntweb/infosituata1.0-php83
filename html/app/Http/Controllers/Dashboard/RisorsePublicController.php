<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\AttachmentS3;
use App\Models\Risorsa;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class RisorsePublicController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     */
    public function show($id)
    {
        $el = Risorsa::withoutGlobalScopes()->with(['attachments'])->whereActive('1')->where('id', $id)->first();
        if (!$el) abort(404);

        if ($el->visibility == 'private')
            return redirect()->route('risorse.show', [$id]);

        risorsaLog($el);

        // in questo caso è esterna
        /** Invece di fare il redirect cerco di visualizzarla in un iframe **/
        /**
         * if ($el->extras2) return response()->redirectTo($el->extras2);
         */

        $data['el'] = $el;
        $data['attachments'] = AttachmentS3::where('reference_id', $el->id)
            ->where('reference_table', 'items')
            ->where('to_delete', '0')
            ->where('is_embedded', '0')
            ->orderBy('label')
            ->get();
        $data['scadenze'] = getScadenze($el);

        return view('dashboard.infosituata.risorse.show', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

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
