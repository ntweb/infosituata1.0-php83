<?php

namespace App\Console\Commands;

use Illuminate\Support\Facades\Storage;
use Illuminate\Console\Command;

class DeleteTicketsAttachments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tickets:delete-attachments';

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
    protected $description = 'Cancella gli allegati edi ticket dopo un mese';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $files = Storage::disk("public")->allFiles("tickets");
        foreach ($files as $file) {
            $time = Storage::disk('public')->lastModified($file);
            $fileModifiedDateTime = \Carbon\Carbon::parse($time);

            if (\Carbon\Carbon::now()->gt($fileModifiedDateTime->addMonth())) {
                Storage::disk("public")->delete($file);
            }
        }
    }
}
