<?php

namespace App\Console\Commands;

use App\Models\AttachmentS3;
use App\Models\Azienda;
use App\Utilities\S3;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class TransferS3 extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'transfer:s3 {id}';

    protected $s3;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(S3 $s3)
    {
        parent::__construct();

        $this->s3 = $s3;
    }

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Trasferisce una tantum gli allegati su S3';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        set_time_limit(0);
        $started = \Carbon\Carbon::now();

        $id = $this->argument('id');

        $aziende = Azienda::with('user')->where('id', $id)->get();
        foreach ($aziende as $azi) {
            $this->info('---------------------------------------------');

            if ($azi->user->active == '0') {
                $this->warn($azi->label . '.. non attiva .. skipped');
            }
            else {
                $this->info($azi->label . '.. transfer');

                /** Item transfer **/
                $attachments = DB::table('attachments')->where('azienda_id', $azi->id)->get();
                foreach ($attachments as $a) {
                    $reference = DB::table('items')->find($a->item_id);
                    $reference_id = $reference->id;
                    $reference_table = 'items';
                    if ($a->url_cloud)
                        $this->transferCloud($a,$reference, $reference_id, $reference_table, '0');
                    else {
                        $path = public_path('docs/'.$azi->id.'/'.$a->id.'/'.$a->filename);
                        $file = new UploadedFile($path, $a->filename);
                        $this->s3->saveFile($file, $reference, $reference_id, $reference_table, $a->is_public, '0', $a->label);
                    }
                }
                $this->info('Items transfer finished');

                /** Commesse **/
                $attachments = DB::table('attachments_commessa')->where('azienda_id', $azi->id)
                    ->whereNull('commesse_rapportini_id')
                    ->get();
                foreach ($attachments as $a) {
                    $reference = DB::table('commesse')->find($a->commesse_id);
                    if (!$reference) {
                        $this->warn('Non trovata commesse_id : ' . $a->commesse_id);
                        continue;
                    }

                    $reference_id = $reference->id;
                    $reference_table = 'commesse';
                    if ($a->url_cloud) {
                        $this->transferCloud($a,$reference, $reference_id, $reference_table, '0');
                    }
                    else {
                        $path = public_path('docs/'.$azi->id.'/commessa/'.$a->id.'/'.$a->filename);
                        $file = new UploadedFile($path, $a->filename);
                        $this->s3->saveFile($file, $reference, $reference_id, $reference_table, '0', '0', $a->label);
                    }
                }
                $this->info('Commesse transfer finished');

                /** Commesse rapportini **/
                $attachments = DB::table('attachments_commessa')->where('azienda_id', $azi->id)
                    ->whereNotNull('commesse_rapportini_id')
                    ->get();
                foreach ($attachments as $a) {
                    $reference = DB::table('commesse_rapportini')->find($a->commesse_rapportini_id);
                    $reference_id = $reference->id;
                    $reference_table = 'commesse_rapportini';
                    if ($a->url_cloud)
                        $this->transferCloud($a,$reference, $reference_id, $reference_table, '0');
                    else {
                        $path = public_path('docs/'.$azi->id.'/commessa/'.$a->id.'/'.$a->filename);
                        $file = new UploadedFile($path, $a->filename);
                        $this->s3->saveFile($file, $reference, $reference_id, $reference_table, '0','0', $a->label);
                    }
                }
                $this->info('Commesse rapportini transfer finished');

                /** Manutenzioni **/
                $attachments = DB::table('attachments_manutenzione')->where('azienda_id', $azi->id)
                    ->get();
                foreach ($attachments as $a) {
                    $reference = DB::table('manutenzioni')->find($a->manutenzione_id);
                    $reference_id = $reference->id;
                    $reference_table = 'manutenzioni';
                    if ($a->url_cloud)
                        $this->transferCloud($a,$reference, $reference_id, $reference_table, '0');
                    else {
                        $path = public_path('docs/'.$azi->id.'/manutenzione/'.$a->id.'/'.$a->filename);
                        $file = new UploadedFile($path, $a->filename);
                        $this->s3->saveFile($file, $reference, $reference_id, $reference_table, '0','0', $a->label);
                    }
                }
                $this->info('Manutenzioni transfer finished');

                /** Messaggi **/
                $attachments = DB::table('attachments_messaggio')->where('azienda_id', $azi->id)
                    ->get();
                foreach ($attachments as $a) {
                    $reference = DB::table('messaggi')->find($a->messaggio_id);
                    $reference_id = $reference->id;
                    $reference_table = 'messaggi';
                    if ($a->url_cloud)
                        $this->transferCloud($a,$reference, $reference_id, $reference_table, '0');
                    else {
                        $path = public_path('docs/'.$azi->id.'/messaggio/'.$a->id.'/'.$a->filename);
                        $file = new UploadedFile($path, $a->filename);
                        $this->s3->saveFile($file, $reference, $reference_id, $reference_table, '0','0', $a->label);
                    }
                }
                $this->info('Messaggi transfer finished');

                /** Scadenze **/
                $attachments = DB::table('attachments_scadenza')->where('azienda_id', $azi->id)
                    ->get();
                foreach ($attachments as $a) {
                    $reference = DB::table('scadenze')->find($a->scadenza_id);
                    if (!$reference) {
                        $this->warn('NOn trovata scadenza id: ' . $a->scadenza_id);
                        continue;
                    }
                    $reference_id = $reference->id;
                    $reference_table = 'scadenze';
                    if ($a->url_cloud)
                        $this->transferCloud($a,$reference, $reference_id, $reference_table, '0');
                    else {
                        $path = public_path('docs/'.$azi->id.'/scadenza/'.$a->id.'/'.$a->filename);
                        $file = new UploadedFile($path, $a->filename);
                        $this->s3->saveFile($file, $reference, $reference_id, $reference_table, '0','0', $a->label);
                    }
                }
                $this->info('Scadenze transfer finished');

                /** Modot **/
                $attachments = DB::table('attachments_modot23')->where('azienda_id', $azi->id)
                    ->get();
                foreach ($attachments as $a) {
                    $reference = DB::table('inail_modot23')->find($a->item_id);
                    $reference_id = $reference->id;
                    $reference_table = 'inail_modot23';
                    if ($a->url_cloud)
                        $this->transferCloud($a,$reference, $reference_id, $reference_table, '0');
                    else {
                        $path = public_path('docs/'.$azi->id.'/modot/'.$a->id.'/'.$a->filename);
                        $file = new UploadedFile($path, $a->filename);
                        $this->s3->saveFile($file, $reference, $reference_id, $reference_table, '0','0', $a->label);
                    }
                }
                $this->info('Modot transfer finished');
            }
        }

        $ended = \Carbon\Carbon::now();
        $this->info('Completed in: '. $ended->diffInMinutes($started).' minutes');
    }

    protected function transferCloud($attachment, $reference, $reference_id, $reference_table, $is_embedded) {
        $s3Attachment = new AttachmentS3;
        $s3Attachment->id = Str::uuid();
        $s3Attachment->azienda_id = $reference->azienda_id;
        $s3Attachment->reference_id = $reference_id;
        $s3Attachment->reference_table = $reference_table;
        $s3Attachment->is_embedded = $is_embedded;
        $s3Attachment->users_id = 1;

        $filename = 'cloud';
        $s3Attachment->filename = $filename;

        $s3Attachment->label = $attachment->label;
        $s3Attachment->url_cloud = $attachment->url_cloud;
        $s3Attachment->size = 0;

        $s3Attachment->save();
    }
}
