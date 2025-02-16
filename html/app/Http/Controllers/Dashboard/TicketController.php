<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Ticket;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class TicketController extends Controller
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

        // Log::info($request->all());

        $validationRules = [
            'modulo' => 'required',
            'oggetto' => 'required',
            'descrizione' => 'required',
        ];

        $validatedData = $request->validate($validationRules);
        $el = new Ticket();
        $fields = $request->except(['_token', '_redirect', '_method', '_type', '_module', 'ticketId', 'ticketUrl']);
        foreach ($fields as $k => $v) {
            $el->$k = $v;
        }


        try {
            $utente = auth()->user()->name.' (id: '.auth()->user()->id.')';
            $el->utente = $utente;
            $el->email = auth()->user()->email;

            $azienda = getAziendaBySessionUser();
            $el->azienda = $azienda->label;

            $el->url = $request->input('ticketUrl', null);
            $el->id = $request->input('ticketId');
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

    public static function aree() {
        $aree = [
            '',
            'Dashboard',
            'Azienda - sedi',
            'Azienda - gruppi',
            'Infosituata - Utenti',
            'Infosituata - Mezzi',
            'Infosituata - Attrezzature',
            'Infosituata - Materiali',
            'Infosituata - Risorse',
            'Infosituata - Scadenzario',
            'Infosituata - Tipologia scadenza',
            'Infosituata - Eventi',
            'Comunicazioni - Messaggi',
            'Comunicazioni - Topic',
            'Comunicazioni - SMS',
            'Comunicazioni - Whatapp',
            'Rapportini',
            'Checklist',
            'Commesse',
            'Commesse - Template',
            'Commesse - Squadre',
            'Commesse - Utility',
            'Human activity',
            'Prevenzione - Mancati infortuni',
            'Autorizzazioni',
            'Altro',
        ];

        $aree = array_combine($aree, $aree);
        return $aree;
    }

    public function ps(Request $request) {

        $image = $request->input('ticketBase64PS');
        $image = str_replace('data:image/png;base64,', '', $image);
        $image = str_replace(' ', '+', $image);
        $imageName = $request->input('ticketId').'.'.'png';
        Storage::disk('public')->put('tickets/'.$imageName, base64_decode($image));

    }

    public function attachment($id) {
        $attachment = 'tickets/'.$id.'.png';
        $file = Storage::disk('public')->get($attachment);
        return (new Response($file, 200))->header('Content-Type', 'image/png');
    }
}
