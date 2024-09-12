<?php

namespace App\Listeners;

use App\Events\Illuminate\Events\TimbraturaModified;
use App\Exceptions\TimbraturaException;
use App\Models\CommessaLog;
use App\Models\Timbratura;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class InsertTimbraturaCommessa
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  TimbraturaModified  $event
     * @return void
     */
    public function handle($event)
    {
        $t = $event->timbratura;
        $utente = null;
        if ($t->commesse_id) {

            $user = User::with('utente')->find($t->users_id);
            if (!$user->utente) {
                // squadrato
                // Log::info('Utente non trovato');
                return;
            }

            $utente = $user->utente;

            /** controllo che le timbrature quadrino **/
            $day = new \Carbon\Carbon($t->marked_at);
            $timbrature = Timbratura::where('users_id', $t->users_id)
                                                    ->whereDate('marked_at', $day->toDateString())
                                                    ->orderBy('marked_at')
                                                    ->get();
            $ids = $timbrature->pluck('id');

            try {

                $type = 'in';
                foreach ($timbrature as $_t) {
                    if ($_t->type !== $type) {
                        // squadrato
                        throw new TimbraturaException('Squadrato ordine sbagliato');
                    }
                    $type = $type === 'in' ? 'out' : 'in';
                }

                $ins =  $timbrature->filter(function($item){
                    return $item->type === 'in';
                });

                $outs =  $timbrature->filter(function($item){
                    return $item->type !== 'in';
                });

                if (!$ins->count() || !$outs->count()) {
                    // squadrato
                    throw new TimbraturaException('Squadrato count');
                }

                if ($ins->count() !=  $outs->count()) {
                    // squadrato
                    throw new TimbraturaException('Squadrato ingresso / uscita');
                }

                $ins = $ins->values();
                $outs = $outs->values();

                $insertInCommessa = collect([]);
                foreach ($ins as $index => $in) {
                    $out = $outs[$index];
                    $di = new \Carbon\Carbon($in->marked_at);
                    $do = new \Carbon\Carbon($out->marked_at);

                    if ($do->lt($di)) {
                        // squadrato
                        throw new TimbraturaException('Squadrato uscita minore di entrata');
                    }

                    if ($in->commesse_id != $out->commesse_id) {
                        // squadrato
                        throw new TimbraturaException('Squadrato commesa diversa tra uscita / entrata');
                    }

                    if ($in->commesse_id) {
                        $insert = [
                            'commesse_id' => $in->commesse_id,
                            'in_timbrature_id' => $in->id,
                            'out_timbrature_id' => $out->id,
                            'in_date' => $in->marked_at,
                            'out_date' => $out->marked_at,
                        ];

                        $insertInCommessa->push($insert);
                    }

                    if ($insertInCommessa->count()) {

                        foreach ($insertInCommessa as $data) {

                            // Log::info($data);

                            $c = CommessaLog::where('in_timbrature_id', $data['in_timbrature_id'])
                                ->where('out_timbrature_id', $data['out_timbrature_id'])
                                ->first();

                            if (!$c) {
                                // Log::info('CommessaLog creating');
                                $c = new CommessaLog;
                                $c->id = Str::uuid();
                            }


                            $c->commesse_id = $data['commesse_id'];
                            $c->item_id = $utente->id;
                            $c->item_label = $utente->label;
                            $c->inizio = $data['in_date'];
                            $c->fine = $data['out_date'];
                            $c->in_timbrature_id = $data['in_timbrature_id'];
                            $c->out_timbrature_id = $data['out_timbrature_id'];
                            $c->username = 'system';
                            $c->save();

                        }
                    }
                }

            }
            catch (TimbraturaException $e) {
                Log::info($e->getMessage());

                /** In caso di squadrature cancello **/
                DB::table('commesse_log')
                    ->whereIn('in_timbrature_id', $ids)
                    ->orWhereIn('out_timbrature_id', $ids)
                    ->delete();
            }
        }
    }
}
