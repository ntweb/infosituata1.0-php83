<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;

class ActivationController extends Controller
{
    function activate(Request $request) {

        // Log::info($request->all());

        $device = isValidDevice($request);
        if (!$device)
            return response()->json(getApiResponse('error', 'Device not found or not activated', null), 404);

        $device->os_device_name = $request->input('device_name');
        $device->active = '1';
        $device->save();
        $data['device'] = $device;

        return response()->json(getApiResponse('success', 'Device found', $data));

    }
}
