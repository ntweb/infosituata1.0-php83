<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\AttachmentS3;
use App\Models\Item;
use App\Http\Controllers\Controller;
class UploadPublicController extends Controller
{

    public function download($attachment_id) {

        $el = AttachmentS3::withoutGlobalScopes()->where('id', $attachment_id)->first();
        if (!$el) abort(404);

        if ($el->reference_table == 'items') {
            $item = Item::withoutGlobalScopes()->where('id', $el->reference_id)->first();
            if (!$item) abort(404);

            if ($item->visibility == 'private') abort(401);
            // return redirect()->action('Dashboard\UploadController@download', [$md5_attachment_id]);
        }

        $file = public_path('docs/'.$el->azienda_id.'/'.$el->id.'/'.$el->filename);
        return response()->download($file, $el->filename);
    }
}
