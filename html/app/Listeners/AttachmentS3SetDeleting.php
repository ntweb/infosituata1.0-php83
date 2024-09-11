<?php

namespace App\Listeners;

use App\Events\Illuminate\Events\AttachmentS3ParentDeleted;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\DB;

class AttachmentS3SetDeleting
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
     * @param  AttachmentS3ParentDeleted  $event
     * @return void
     */
    public function handle(AttachmentS3ParentDeleted $event)
    {
        DB::table('attachmentss3')->where('reference_id', $event->reference_id)
            ->where('reference_table', $event->reference_table)
            ->update(['to_delete' => '1']);


        /**
         * cancellazione a cascata degli allegati di commessa
        **/
        if ($event->reference_table == 'commesse') {

            /** ricavo gli allegati di eventuali rapportini o checklist associate **/
            $commessa = \App\Models\Commessa::find($event->reference_id);

            if (!$commessa->root_id) {
                /** è il root **/
                $idsChildren = \App\Models\Commessa::where('root_id', $commessa->id)->get()->pluck('id');
            }
            else {
                $idsChildren = \App\Models\Commessa::where('parent_id', $commessa->id)->get()->pluck('id');
            }

            DB::table('attachmentss3')->whereIn('reference_id', $idsChildren)
                ->where('reference_table', $event->reference_table)
                ->update(['to_delete' => '1']);

            /** Rapportini **/
            $ids = DB::table('commesse_rapportini')->whereIn('commesse_id', $idsChildren)
                ->get()
                ->pluck('id');

            DB::table('attachmentss3')->whereIn('reference_id', $ids)
                ->where('reference_table', 'commesse_rapportini')
                ->update(['to_delete' => '1']);

        }
    }
}
