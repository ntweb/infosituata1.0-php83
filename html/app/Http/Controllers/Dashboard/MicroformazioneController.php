<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MicroformazioneController extends Controller
{
    public function index() {
        $data = [];
        return view('dashboard.microformazione.index', $data);
    }

    public function alcol() {
        $data = [];
        return view('dashboard.microformazione.alcol', $data);
    }

    public function manuale() {
        $data = [];
        return view('dashboard.microformazione.manuale', $data);
    }
}
