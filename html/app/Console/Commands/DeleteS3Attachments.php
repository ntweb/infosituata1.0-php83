<?php

namespace App\Console\Commands;

use App\Mail\CacciatoreAvviso;
use App\Mail\NotificaListe;
use App\Models\PrenotazioneDay;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use PDF;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DeleteS3Attachments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'delete:s3';

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
    protected $description = 'Cancella risorse s3';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        try {
            $s3Attachments = DB::table('attachmentss3')
                ->join('aziende', 'aziende.id', '=', 'attachmentss3.azienda_id')
                ->select('attachmentss3.*', 'aziende.uid')
                ->where('attachmentss3.to_delete', '1')
                ->limit(50)
                ->get();

            foreach ($s3Attachments as $s3Att) {
                DB::table('attachmentss3')->where('id', $s3Att->id)->delete();
                Storage::disk('s3')->delete($s3Att->uid.'/'.$s3Att->id);
            }
        }
        catch (\Exception $e) {
            $this->error($e->getMessage());
        }
    }
}
