<?php

namespace App\Console\Commands;

use Illuminate\Support\Facades\File;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class DeleteItems extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'delete:items';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cancella gli items messi in soft delete';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        $now = \Carbon\Carbon::now();
        $start = \Carbon\Carbon::now()->subHours(2)->toDateTimeString();
        // $this->info($start);
        // $this->info($now);


        $items = DB::table('items')
            ->whereBetween('deleted_at', [$start, $now])
            ->get();

        if (!$items->count()) {
            $this->info('Nessun item da cancellare');
            return null;
        }

        foreach ($items as $item) {
            // $this->info('deleting... ' .$item->id);
            $controller = $item->controller;

            try {
                if ($controller == 'utente') {
                    $user = DB::table('users')->where('utente_id', $item->id)->first();
                    // $this->info('1');
                    DB::table('users')->where('utente_id', $item->id)->delete();
                    // $this->info('2');
                    DB::table('gruppo_utente')->where('utente_id', $item->id)->delete();
                    // $this->info('3');
                    DB::table('humanactivity')->where('utente_id', $item->id)->delete();
                    // $this->info('4');
                    if ($user)
                        DB::table('messaggi')->where('user_id', $user->id)->delete();
                    // $this->info('5');
                    DB::table('messaggio_utente')->where('utente_id', $item->id)->delete();
                    // $this->info('6');
                    DB::table('devices')->where('utente_id', $item->id)->update([ 'utente_id' => 0 ]);
                    // $this->info('7');
                    DB::table('risorse_logs')->where('utente_id', $item->id)->delete();
                    // $this->info('8');
                }

                DB::table('risorse_logs')->where('item_id', $item->id)->delete();
                // $this->info('9');

                $scadenze = DB::table('scadenze')->where('item_id', $item->id)->get();
                // $this->info('10');
                foreach ($scadenze as $scad) {
                    $attachments = DB::table('attachments_scadenza')->where('scadenza_id', $scad->id)->get();
                    foreach ($attachments as $attach) {
                        $path = public_path('docs/'.$attach->azienda_id.'/scadenza/'.$attach->id);
                        // $this->info('deleting... '.$path);
                        File::deleteDirectory($path);
                    }
                }
                DB::table('scadenze')->where('item_id', $item->id)->delete();
                // $this->info('11');

                $attachments = DB::table('attachments')->where('item_id', $item->id)->get();
                // $this->info('12');
                foreach ($attachments as $attach) {

                    $path = public_path('docs/'.$attach->azienda_id.'/'.$attach->id);
                    // $this->info('deleting... '.$path);
                    File::deleteDirectory($path);
                }
                DB::table('attachments')->where('item_id', $item->id)->delete();

                DB::table('items')->whereId($item->id)->delete();
            }
            catch (\Exception $e) {
                $this->error('Errore: ' . $e->getMessage());
            }

        }

        return null;
    }
}
