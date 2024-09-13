<?php

namespace App\Console\Commands;

use App\Models\Azienda;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class MakeFattura extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:fattura';

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
    protected $description = 'Richiede la fattura';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        DB::beginTransaction();
        try {
            $aziende = Azienda::with('user')->get();
            foreach ($aziende as $a) {
                if($a->user->active) {
                    // nuova attivazione
                    if (!$a->make_fattura_at) {
                        $make_fattura_at = \Carbon\Carbon::now();
                        $aziende->make_fattura_at = $make_fattura_at->lastOfMonth();
                        $this->info($aziende->make_fattura_at);
                    }
                    else {
                        $make_fattura_at = new \Carbon\Carbon($a->make_fattura_at);
                        if(\Carbon\Carbon::now()->gt($make_fattura_at)) {
                            $new_date = date('m-d', strtotime($a->user->deactivate_at));
                            $new_date = (date('Y') + 1).'-'.$new_date;

                            $make_fattura_at = new \Carbon\Carbon($new_date);
                            $aziende->make_fattura_at = $make_fattura_at->lastOfMonth();
                            $this->info($aziende->make_fattura_at);
                        }

                    }
                }
            }

        }catch (\Exception $e) {
            DB::rollBack();
             $this->info($e->getMessage());
        }
    }
}
