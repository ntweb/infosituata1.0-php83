<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Item;
use App\Http\Controllers\Controller;

class InfosituataPublicController extends Controller
{

    public function check($md5_id) {

        $item = Item::withoutGlobalScopes()->whereRaw("md5(id) = '$md5_id'")->first();
        if (!$item) abort(404);


        if ($item->visibility != 'public')
            return redirect()->route('infosituata.check', [$md5_id]);


        switch ($item->controller) {
            case 'risorsa':
                return redirect()->route('risorse-public.show', [$item->id]);

        }

        abort(404);
    }

}
