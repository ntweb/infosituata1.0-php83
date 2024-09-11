<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class S3Controller extends Controller
{
    public function get($uuid) {
        $s3Att = DB::table('attachmentss3')
            ->join('aziende', 'aziende.id', '=', 'attachmentss3.azienda_id')
            ->select('attachmentss3.*', 'aziende.uid')
            ->where('attachmentss3.id', $uuid)
            ->first();
        if (!$s3Att)
            abort(404);

        $check_permission = true;

        // Log::info($s3Att->reference_table);


        if ($s3Att->reference_table === 'items') {
            $i = DB::table('items')->find($s3Att->reference_id);
            if (!$i)
                abort(404);

            if ($i->controller === 'risorsa' && $i->visibility === 'public')
                $check_permission = false;
        }

        if ($check_permission) {
            if (!auth()->check())
                abort(401);

            if ($s3Att->azienda_id !== getAziendaId())
                abort(401);
        }

        // Log::info($s3Att->azienda_id .' '. getAziendaId());

        $headers = [
            'Content-Type' => $s3Att->mime,
            'Content-Disposition' => 'attachment; filename="'. $s3Att->filename .'"',
        ];

        error_reporting(E_ALL & ~E_USER_DEPRECATED);
        // Log::info($s3Att->uid.'/'.$s3Att->id);
        return response()->make(Storage::disk('s3')->get(strtolower($s3Att->uid).'/'.$s3Att->id), 200, $headers);
    }
}
