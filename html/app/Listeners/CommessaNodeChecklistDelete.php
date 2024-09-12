<?php

namespace App\Listeners;

use App\Events\Illuminate\Events\CommessaNodeDeleted;
use App\Models\Commessa;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\DB;

class CommessaNodeChecklistDelete
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
     * @param  CommessaNodeDeleted  $event
     * @return void
     */
    public function handle(CommessaNodeDeleted $event)
    {
        $commessa = $event->node;

        if (!$commessa->root_id) {
            /** è il root **/
            $idsChildren = Commessa::where('root_id', $commessa->id)->get()->pluck('id');
        }
        else {
            $idsChildren = Commessa::where('parent_id', $commessa->id)->get()->pluck('id');
        }

        /** Checklist **/
        $ids = DB::table('checklists')->whereIn('reference_id', $idsChildren)
            ->where('reference_controller', 'commesse')
            ->get()
            ->pluck('id');

        DB::table('checklists')->whereIn('id', $ids)
            ->where('reference_controller', 'commesse')
            ->delete();

        DB::table('attachmentss3')->whereIn('reference_id', $ids)
            ->where('reference_table', 'checklists')
            ->update(['to_delete' => '1']);

    }
}
