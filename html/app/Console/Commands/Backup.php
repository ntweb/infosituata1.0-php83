<?php

namespace App\Console\Commands;

use App\Mail\CacciatoreAvviso;
use App\Mail\NotificaListe;
use App\Models\PrenotazioneDay;
use App\Utilities\S3;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use PDF;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Backup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 's3-backup:run';

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
    protected $description = 'backup su S3';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $dir = config('app.name');
        Storage::disk('local')->deleteDirectory($dir);
        Artisan::call('backup:clean');
        Artisan::call('backup:run');

        $files = Storage::disk('local')->files($dir);
        $file = @$files[0];
        if ($file) {
            // $this->info('File: ' . $file);
            $path = Storage::disk('local')->path($file);
            $originalName = basename($path);
            // $this->info('$path: ' . $path);
            // $this->info('$originalName: ' . $originalName);

            // error_reporting(E_ALL & ~E_USER_DEPRECATED);
            try {

                DB::table('backups')->insert([
                    'filename' => $originalName,
                    'delete_at' => now()->addDays(15),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $uploadFile = new UploadedFile($path, $originalName);
                $uploadFile->storeAs('_backup', $originalName, 's3');

                // files to delete
                $deleteFiles = DB::table('backups')
                    ->where('delete_at', '<', now())
                    ->get();

                foreach ($deleteFiles as $deleteFile) {
                    $this->info('Deleting file: ' . $deleteFile->filename);
                    Storage::disk('s3')->delete('_backup/' . $deleteFile->filename);
                }

                DB::table('backups')
                    ->where('delete_at', '<', now())
                    ->delete();
            }
            catch (\Exception $e) {
                $this->error($e->getMessage());
                Mail::to('mimmomecca@gmail.com')
                    ->queue(new \App\Mail\EmailGenerica('Errore backup Infosituata S3', $e->getMessage()));
            }

            // $this->s3->saveFile($file, $reference, $reference_id, $reference_table, '0', '0', $a->label);
        }
    }
}
