<?php

namespace App\Http\Controllers\Api;

use App\Models\DeviceConfiguration;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class ConfigurationController extends Controller
{
    function get(Request $request) {

        // Log::info($request->all());

        $device = isValidDevice($request);
        if (!$device)
            return response()->json(getApiResponse('error', 'Device not found or not activated', null));

        /**
         * Ricavo la configurazione:
         * 1 - Controllo se c'è quella particolare per il dispositivo
         * 2 - Se non c'è prendo quella globale dell'azienda
         */
        $conf = DeviceConfiguration::whereDeviceId($device->id)->whereActive('1')->first();
        if ($conf) {
            if ($conf->request_configuration_update) {
                $conf->request_configuration_update = null;
                $conf->save();
            }

            saveGpsPosition($device, $request->get('lat', null), $request->get('lng', null), $conf);
            return response()->json(getApiResponse('success', 'Configuration', $conf));
        }

        // Globale
        DB::table('devices_configuration')->whereDeviceId($device->id)->update(['request_configuration_update'=>null]);
        $conf = DeviceConfiguration::whereAziendaId($device->azienda_id)->whereDeviceId(0)->first();

        saveGpsPosition($device, $request->get('lat', null), $request->get('lng', null), $conf);
        return response()->json(getApiResponse('success', 'Configuration', $conf));

    }
}
