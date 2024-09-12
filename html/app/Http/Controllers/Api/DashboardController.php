<?php

namespace App\Http\Controllers\Api;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller {
    public function index() {
        $inScadenza = getInscadenza();
        $inScadenza =  $inScadenza->sortBy(function ($row, $key) {
            return scadeTra($row);

        });

        $scaduti = getScaduti();
        $scaduti =  $scaduti->sortBy(function ($row, $key) {
            return scadeTra($row);
        });

        return response()->json(['inScadenza' => $inScadenza->values(), 'scaduti' => $scaduti->values()]);
    }
}
