<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;

class ConnectionController extends Controller
{
    function test(Request $request) {

        // Log::info($request->all());

        $device = isValidDevice($request);
        if (!$device)
            return response()->json(getApiResponse('error', 'Device not found or not activated', null));

        $data['label'] = $device->label;
        $data['identifier'] = $device->identifier;
        $data['brand'] = $device->type->brand;
        $data['model'] = $device->type->label;
        $data['os'] = $device->type->os;
        $data['hw'] = $device->type->hw;

        return response()->json(getApiResponse('success', 'Device found', $data));

    }
}
