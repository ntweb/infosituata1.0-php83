<?php

namespace App\Utilities;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class S3
{
    public function saveFile(UploadedFile $file, $reference, $reference_id, $reference_table, $is_public, $is_embedded, $label)
    {

        error_reporting(E_ALL & ~E_USER_DEPRECATED);

        $filename = $file->getClientOriginalName();
        $filename = pathinfo($filename, PATHINFO_FILENAME);
        $filename = Str::slug($filename);
        $extension = $file->getClientOriginalExtension();
        $filename = $filename . '.' . strtolower($extension);

        // Log::info($filename);

        $uuid = Str::uuid();
        $s3Attachment = new \App\Models\AttachmentS3;
        $s3Attachment->id = $uuid;
        $s3Attachment->azienda_id = $reference->azienda_id;
        $s3Attachment->reference_id = $reference_id;
        $s3Attachment->reference_table = $reference_table;
        $s3Attachment->is_public = $is_public ?? '0';
        $s3Attachment->is_embedded = $is_embedded ?? '0';
        $s3Attachment->users_id = auth()->user()->id ?? 1;


        $s3Attachment->filename = $filename;
        $s3Attachment->mime = $file->getClientMimeType();

        $s3Attachment->label = $label ?? $filename;
        $s3Attachment->size = $file->getSize();

        $s3Attachment->save();


        // Log::info($uuid);
        $azi = \App\Models\Azienda::find($reference->azienda_id);
        $azi_folder = strtolower($azi->uid);

        $file->storeAs($azi_folder, $uuid, 's3');
        // move the file name
        // $file->move($finalPath, $fileName);

        return response()->json([
            'name' => $filename,
        ]);
    }
}
