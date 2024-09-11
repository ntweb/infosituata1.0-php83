<?php

namespace App\Http\Controllers\Helpers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Image;

class ImageController extends Controller
{
    public function resize(Request $request) {

        $validationRules = [
            'image' => 'required|file|max:25000'
        ];

        $validatedData = $request->validate($validationRules);
        try {
            foreach ($request->file() as $type => $file) {

                $extension = $file->getClientOriginalExtension();
                $filename = uniqid() . time() . '.' . strtolower($extension);

                $file->move(public_path('temp/'), $filename);

                // resize the image so that the largest side fits within the limit; the smaller
                // side will be scaled to maintain the original aspect ratio
                $img = Image::make(public_path('temp/'.$filename));
                $img->resize(600, 600, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });
                $img->save(public_path('temp/'.$filename), 30);

                return response(url('temp/'.$filename));
            }
        }
        catch (\Exception $e) {
            throw $e;
        }

    }
}
