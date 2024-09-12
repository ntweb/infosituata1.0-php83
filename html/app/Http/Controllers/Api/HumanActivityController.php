<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class HumanActivityController extends Controller
{
    function set(Request $request) {


        // Log::info($request->all());

        $device = isValidAndActiveDevice($request);
        if (!$device)
            return response()->json(getApiResponse('error', 'Device not found or not activated', null));

        //Log::info('pre save');

        saveGpsPosition($device, $request->get('lat', null), $request->get('lng', null), null);

        //Log::info('post save');

        DB::beginTransaction();
        try {

            /**
             * Prendo la configurazione attuale per capire l'entità di HRM
             */
            $conf = \App\Models\DeviceConfiguration::whereDeviceId($device->id)->whereActive('1')->first();
            if (!$conf)
                $conf = \App\Models\DeviceConfiguration::whereAziendaId($device->azienda_id)->whereDeviceId(0)->first();

            $hrm = 'nd';
            if ($request->get('hrm') <= $conf->hrm_bpm_max && $request->get('hrm') >= $conf->hrm_bpm_min) $hrm = 'normal';
            elseif ($request->get('hrm') > ($conf->hrm_bpm_max + 20) || $request->get('hrm') < ($conf->hrm_bpm_min - 20)) $hrm = 'critical';
            elseif ($request->get('hrm') > $conf->hrm_bpm_max || $request->get('hrm') < $conf->hrm_bpm_min) $hrm = 'warning';

            //Log::info('arrivo');

            $el = new \App\Models\HumanActivity;
            $el->device_id = $device->id;
            $el->utente_id = $device->utente_id;
            $el->azienda_id = $device->azienda_id;
            $el->stress_level = 'nd';
            $el->hrm = $hrm ?? 'no data';
            $el->hrm_bpm = $request->get('hrm') ?? -1;
            $el->man_down = strtolower($request->get('man_down')) == 'mandown' ? 'down' : 'up';
            $el->latitude = str_replace(',', '.', $request->get('lat', null));
            $el->longitude = str_replace(',', '.', $request->get('lng', null));
            $el->alert = $request->get('alert', 0) == 0 ? 'auto' : 'manual';

            /*
            if ($el->alert == 'manual') {
                $el->man_down = 'up';
            }
            */

            $el->save();

            sendHumanActivityAlertEmail($el, $conf);
            sendHumanActivityAlertSMS($el, $conf);

            DB::commit();

            return response()->json(getApiResponse('success', 'HumanActivity', $el));
        }catch (\Exception $e) {
            DB::rollBack();
            Log::info($e->getMessage());
            return response()->json(getApiResponse('error', 'HumanActivity', null));
        }


    }

    public function sms(Request $request) {

        // Log::info($request->all());

        $text = $request->get('text');
        $text = str_replace('Infosituata22', '', $text);
        $text = trim($text);

        $data = [];
        $parts = explode(' ', $text);

        $data['man_down'] = $parts[0];
        $data['device_id'] = $parts[1];
        $data['lat'] = $parts[2];
        $data['lng'] = $parts[3];
        $data['alert'] = isset($parts[4]) ? 1 : 0;

        $r = new \Illuminate\Http\Request();
        $r->replace($data);
        return $this->set($r);
    }

}
