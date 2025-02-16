<?php

namespace App\Http\Controllers\Api;

use App\Services\ScadenzeRepo;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ScadenzarioController extends Controller {


    protected $scadenzeRepo;

    public function __construct(ScadenzeRepo $scadenzeRepo)
    {
        $this->scadenzeRepo = $scadenzeRepo;
    }

    public function edit($id) {

        $scadenza = $this->scadenzeRepo->edit($id);
        if (!$scadenza) abort(404);

        return $scadenza;
    }

    public function check(Request $request, $id)
    {
        $scadenza = $this->scadenzeRepo->check($id);
        if (!$scadenza) abort(404);

        return $scadenza;
    }
}
