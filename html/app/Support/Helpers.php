<?php
use App\Mail\CacciatoreAvviso;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

function isActive($routesName) {
    if (is_array($routesName)) {
        foreach ($routesName as $routeName) {
            $check = checkActive($routeName);
            if ($check) return $check;
        }
    }
    else
        return checkActive($routesName);

    return null;
}

function checkActive($routename) {
    $routename = str_replace('.*', '', $routename);
    //Log::info($routename.' - '.Route::currentRouteName());
    if ($routename == Route::currentRouteName() || Str::contains(Route::currentRouteName(), $routename))
        return 'mm-active';

    return null;
}

function data($date) {
    if (!$date) return null;
    return date('d/m/Y', strtotime($date));
}

function dataOra($date) {
    if (!$date) return null;
    return date('d/m/Y H:i', strtotime($date));
}

function ora($date) {
    if (!$date) return null;
    return date('H:i', strtotime($date));
}

function giorno($date) {
    setlocale(LC_TIME, 'it_IT');
    return \Carbon\Carbon::parse($date)->shortLocaleDayOfWeek;
}

function strToDate($value, $format = 'd/m/Y') {
    // Log::info(\Carbon\Carbon::createFromFormat($format, $value));
    return \Carbon\Carbon::createFromFormat($format, $value);
}

function storeAttachment($file, $el_id, $module, $extras = null) {
    $el = new \App\Models\Attachment();
    if ($module == 'doc')
        $el = App\Models\Attachment::find($el_id);

    $el->el_id = $el_id;
    $el->module = $module;
    $el->extras = $extras;

    $filename = $file->getClientOriginalName();
    $filename = pathinfo($filename, PATHINFO_FILENAME);
    $filename = Str::slug($filename);
    $extension = $file->getClientOriginalExtension();
    $filename = $filename . '.' . strtolower($extension);

    $el->filename = $filename;
    try {
        $el->save();
    } catch (\Exception $e) {
        throw new \App\Exceptions\UploadException("cannot save record");
    }

    $storage = $module.'/'.$el->id . '/';

    try {
        $file->move(public_path('docs/attachments/'.$storage), $filename);
    } catch (\Exception $e) {
        $el->delete();
        throw new \App\Exceptions\UploadException("cannot save the file");
    }

    return true;
}

function downloadAttachment($_attachment) {
    return url('docs/attachments/'.$_attachment->module.'/'.$_attachment->id.'/'.$_attachment->filename);
}

function deleteAttachment($_attachment) {
    File::deleteDirectory(public_path('docs/attachments/'.$_attachment->module.'/'.$_attachment->id));
}

function euro ($number, $prettify = false, $dec = 2 ) {
    if ($prettify)
        return number_format ($number, $dec,",",".");

    return number_format ($number, $dec,".", "");
}


function sendEmailGenerica($to, $bcc, $subject, $message) {
    if (config('app.debug')) {
        $to = null;
        $bcc = [];
        $bcc[] = 'dm@artisanlab.it';
        $bcc[] = 'nathanxp@hotmail.com';
        $bcc[] = 'mimmomecca@gmail.com';
    }

    if ($to && count($bcc))
        Mail::to($to)->bcc($bcc)->queue(new \App\Mail\EmailGenerica($subject, $message));
    else if($to)
        Mail::to($to)->queue(new \App\Mail\EmailGenerica($subject, $message));
    else {
        Mail::bcc($bcc)->queue(new \App\Mail\EmailGenerica($subject, $message));
    }

}

function sendHumanActivityAlertEmail($alert, $conf) {
    $emails = explode(',', $conf->emails_alert);
    if (config('app.debug')) $emails = ['mimmomecca@gmail.com'];
    if(count($emails))
        Mail::cc($emails)->queue(new \App\Mail\HumanActivityAlert($alert));
}

function sendHumanActivityAlertSMS($alert, $conf) {
    $telephones = explode(',',$conf->telephones_alert);
    if (config('app.debug')) $telephones = ['+393282519247'];

    if (count($telephones) <= 0)
        return;

    $BASEURL = "https://api.trendoo.net/API/v1.0/REST/";
    $MESSAGE_HIGH_QUALITY = "GP";

    $USERNAME = env('TRENDOO_USERNAME');
    $PASSWORD = env('TRENDOO_PASSWORD');

    /**
     * Login
     */
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $BASEURL . 'login?username=' . $USERNAME . '&password=' . $PASSWORD);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    $info = curl_getinfo($ch);
    curl_close($ch);

    if ($info['http_code'] != 200) {
        return null;
    }

    $auth = explode(";", $response);
    // Log::info($auth);

    /**
     * Send SMS
     */
    $utente = $alert->utente;
    $sendSMS = array(
        "message" => env('APP_NAME').' HAM Alarm: ' . strtoupper($utente ? $utente->label : 'ND'),
        "message_type" => $MESSAGE_HIGH_QUALITY,
        "returnCredits" => false,
        "recipient" => $telephones,
        "sender" => null,     // Place here a custom sender if desired
        // "scheduled_delivery_time" => date('YmdHi', strtotime("+5 minutes")), // postpone by 5 minutes
    );

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $BASEURL . 'sms');
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-type: application/json',
        'user_key: ' . $auth[0],
        'Session_key: ' . $auth[1]
    ));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($sendSMS));

    $response = curl_exec($ch);
    $info = curl_getinfo($ch);

    curl_close($ch);
    if ($info['http_code'] != 201) {
        return null;
    }

    $smsSent = json_decode($response);
    if ($smsSent->result == "OK") {
        return true;
    }

    return false;
}

function packageError($_module, $azienda_id) {
    if (Auth::user()->superadmin) return false;
    $azienda = $azienda_id ? \App\Models\Azienda::with('package')->whereId($azienda_id)->first() : \App\Models\Azienda::with('package')->whereId(Auth::user()->azienda_id)->first();

    if (!$azienda) return true;
    if (!$azienda->package) return true;


    switch ($_module) {
        case 'sede':
            $els = \App\Models\Sede::whereAziendaId($azienda->id)->count();
            if ($els >= $azienda->package->sedi) return true;
            break;

        case 'gruppo':
            $els = \App\Models\Gruppo::whereAziendaId($azienda->id)->count();
            if ($els >= $azienda->package->gruppi) return true;
            break;

        case 'utente':
            $els = \App\Models\Utente::whereAziendaId($azienda->id)->count();
            if ($els >= $azienda->package->utenti) return true;
            break;

        case 'terminali':
            $els = \App\Models\Device::whereAziendaId($azienda->id)->count();
            if ($els >= $azienda->terminali) return true;

            break;
    }

    return false;
}

function getScadenze($item, $infosituata_moduli_details_id = null) {
    $query = \App\Models\Scadenza::whereItemId($item->id)
        ->orderBy('checked_at')
        ->orderBy('advice_at')
        ->limit(150)
        ->with(['detail', 'gruppi', 'gruppi.utenti']);

    if (\auth()->user()) {
        if ($item->controller == 'utente' || $item->controller == 'risorsa') {
            if (\Illuminate\Support\Facades\Gate::denies('can-create')) {
                $query = $query->where(function($query) {
                    $query->where('created_by', \auth()->user()->id)
                        ->orWhere('item_id', \auth()->user()->utente_id)
                        ->orWhereHas('gruppi.utenti', function($query) {
                            $query->where('id', \auth()->user()->utente_id);
                        });
                });
            }
        }
    }


    if($infosituata_moduli_details_id)
        $query = $query->whereInfosituataModuliDetailsId($infosituata_moduli_details_id);

    return $query->get();
}

function statusScadenza($scadenza) {
    if ($scadenza->checked_at) return 'checked';
    if (scaduto($scadenza)) return 'scaduto';
    if (inScadenza($scadenza)) return 'in-scadenza';
}

function scaduto($scadenza) {
    $dt = new \Carbon\Carbon($scadenza->end_at);
    return $dt->lt(\Carbon\Carbon::now());
}

function inScadenza($scadenza) {
    $dt = new \Carbon\Carbon($scadenza->end_at);
    return $dt->lt(\Carbon\Carbon::now()->addDays(30));
}

function getCalendarScadenze($start, $end) {
    $query = \App\Models\Scadenza::whereBetween('end_at', [$start, $end])
        ->with(['item', 'detail', 'gruppi.utenti']);

    if (\Illuminate\Support\Facades\Gate::denies('can-create') || session()->has('fs')) {
        $query = $query->where(function($query) {
            $query->where('created_by', \auth()->user()->id)
                ->orWhere('item_id', \auth()->user()->utente_id)
                ->orWhereHas('gruppi.utenti', function($query) {
                    $query->where('id', \auth()->user()->utente_id);
                });
        });
    }

    return $query->get();
}

function getInscadenza($_days = 30) {
    $dt = \Carbon\Carbon::now()->addDays($_days);
    $query = \App\Models\Scadenza::whereNull('checked_at')
        ->whereBetween ('end_at', [\Carbon\Carbon::now()->toDateString(), $dt->toDateString()])
        ->with('gruppi.utenti', 'item', 'module', 'detail');

    if (\Illuminate\Support\Facades\Gate::denies('can-create') || session()->has('fs')) {
        $query = $query->where(function($query) {
            $query->where('created_by', \auth()->user()->id)
                ->orWhere('item_id', \auth()->user()->utente_id)
                ->orWhereHas('gruppi.utenti', function($query) {
                    $query->where('id', \auth()->user()->utente_id);
                });
        });
    }

    return $query->get();
}

function getScaduti($_days = 90, $item_id = null) {
    $query  = \App\Models\Scadenza::whereNull('checked_at')
        ->where('end_at', '<', \Carbon\Carbon::now()->toDateString())
        ->with('gruppi.utenti', 'item', 'module', 'detail')
        ->limit(150);

    if ($item_id !== null) {
        $query = $query->where('item_id', $item_id);
    }
    else {
        if (\Illuminate\Support\Facades\Gate::denies('can-create') || session()->has('fs')) {
            $query = $query->where(function($query) {
                $query->where('created_by', \auth()->user()->id)
                    ->orWhere('item_id', \auth()->user()->utente_id)
                    ->orWhereHas('gruppi.utenti', function($query) {
                        $query->where('id', \auth()->user()->utente_id);
                    });
            });
        }
    }


    return $query->get();
}

function scadeTra($scadenza) {
    $dt = new \Carbon\Carbon($scadenza->end_at);
    return $dt->diffInDays(\Carbon\Carbon::today());
}

function getCalendarTasks($start, $end) {

    $st = new \Carbon\Carbon($start);
    $en = new \Carbon\Carbon($end);

    $start = $st->startOfDay();
    $end = $en->endOfDay();

    $query = \App\Models\Task::whereJsonContains('users_ids', [\auth()->user()->id.""])
        ->where(function (Builder $query) use($start, $end) {
            $query->whereBetween('data_inizio_prevista', [$start, $end])
                ->orWhereBetween('data_fine_prevista', [$start, $end])
                ->orWhere(function($query) use ($start, $end) {
                    $query->whereDate('data_inizio_prevista', '<=', $start)->whereDate('data_fine_prevista', '>=', $end);
                });
        });

    return $query->get();
}

function allegato($upload, $crypt, $module) {

    if ($upload->url_cloud)
        return $upload->url_cloud;

    switch ($module) {
        case 'modot':
            return url('docs/'.$upload->azienda_id.'/modot/'.$upload->id.'/'.$upload->filename);
            break;

        case 'scadenza':
            return url('docs/'.$upload->azienda_id.'/scadenza/'.$upload->id.'/'.$upload->filename);
            break;

        case 'manutenzione':
            return url('docs/'.$upload->azienda_id.'/manutenzione/'.$upload->id.'/'.$upload->filename);
            break;

        case 'messaggio':
            return url('docs/'.$upload->azienda_id.'/messaggio/'.$upload->id.'/'.$upload->filename);
            break;

        case 'commessa-rapportino':
        case 'commessa':
            return url('docs/'.$upload->azienda_id.'/commessa/'.$upload->id.'/'.$upload->filename);
            break;
    }

    if ($crypt) {
        return action('Dashboard\UploadPublicController@download', $upload->id);
    }

    return url('docs/'.$upload->azienda_id.'/'.$upload->id.'/'.$upload->filename);
}

function risorsaLog($risorsa) {
    if (Auth::user()) {
        if (Auth::user()->utente_id) {

            $now = \Carbon\Carbon::now();
            DB::table('risorse_logs')->insert([
                'azienda_id' => $risorsa->azienda_id,
                'utente_id' => Auth::user()->utente_id,
                'item_id' => $risorsa->id,
                'created_at' => $now,
                'updated_at' => $now
            ]);
        }
    }
}

function currentCloudUsage() {
    if (Auth::user()) {
        if ((Auth::user()->utente_id <= 0 && !Auth::user()->superadmin) ||  Auth::user()->power_user) {

            $azienda = \App\Models\Azienda::with('package')->find(Auth::user()->azienda_id);
            if (Auth::user()->power_user) {
                $item = \App\Models\Item::find(Auth::user()->utente_id);
                $azienda = \App\Models\Azienda::with('package')->find($item->azienda_id);
            }

            $size = \App\Models\AttachmentS3::sum('size');

            $_package_size = @$azienda->package->size <= 0 ? 0.1 : $azienda->package->size;
            $_size_perc = ($size * 100) / $_package_size;
            $_status = 'success';
            if ($_size_perc >= 60) $_status = 'warning';
            if ($_size_perc >= 85) $_status = 'danger';

            return ['status' => $_status, 'size' => $size, 'package' => $azienda->package ];
        }
    }

    return ['status' => 'success', 'size' => 0, 'package' => null ];
}

function isa_convert_bytes_to_specified($bytes, $to, $decimal_places = 2) {
//    $formulas = array(
//        'K' => number_format($bytes / 1024, $decimal_places),
//        'M' => number_format($bytes / 1048576, $decimal_places),
//        'G' => number_format($bytes / 1073741824, $decimal_places)
//    );
        $formulas = array(
        'K' => number_format($bytes / 1000, $decimal_places),
        'M' => number_format($bytes / 1000000, $decimal_places),
        'G' => number_format($bytes / 1000000000, $decimal_places)
    );
    return isset($formulas[$to]) ? $formulas[$to] : 0;
}

function isLimitUpload() {
    if (Auth::user()) {
        if ((Auth::user()->utente_id <= 0 && !Auth::user()->superadmin) ||  Auth::user()->power_user) {
            $size = \App\Models\Attachment::sum('size');
            $size_scadenze = \App\Models\AttachmentScadenza::sum('size');
            $size_manutenzioni = \App\Models\AttachmentManutenzione::sum('size');
            $size_modot = \App\Models\AttachmentModOt23::sum('size');
            $size_messaggi = \App\Models\AttachmentMessaggio::sum('size');
            $size = $size + $size_scadenze + $size_modot + $size_messaggi + $size_manutenzioni;
            $azienda = \App\Models\Azienda::with('package')->find(Auth::user()->azienda_id);
            if (Auth::user()->power_user) {
                $item = \App\Models\Item::find(Auth::user()->utente_id);
                $azienda = \App\Models\Azienda::with('package')->find($item->azienda_id);
            }
            $_package_size = $azienda->package->size;
            // Log::info($_package_size.' <= '.$size.' '.$azienda->package->id);

            if ($_package_size <= $size)
                return true;
        }
    }
    return false;
}

function getAziendaId ($el = null) {
    if (Auth::user()->azienda_id > 0) {
        return Auth::user()->azienda_id;
    }

    if (Auth::user()->utente_azienda_id > 0) {
        return Auth::user()->utente_azienda_id;
    }

    /**
    if (Auth::user()->power_user || Auth::user()->utente_id) {
        return session()->get('azienda_id');
    }
    **/

    if ($el)
        if ($el->azienda_id)
            return $el->azienda_id;

    return -1;
}

function getAziendaBySessionUser () {
    if (Auth::user()->azienda_id) {
        return Auth::user()->azienda;
    }

    if (Auth::user()->power_user) {
        return Auth::user()->utente->azienda;
    }

    if (Auth::user()->utente_id) {
        return Auth::user()->utente->azienda;
    }

    return null;

}

function getUtenteIdBySessionUser() {
    return auth()->user()->utente_id;
}

function getApiResponse($status = 'success', $message = null, $payload = null) {
    return ['status' => $status, 'message' => $message, 'payload' => $payload];
}

function isValidDevice($request) {
    if (!$request->has('device_id'))
        return null;

    $identifier = $request->get('device_id');
	// Log::info($identifier);
    $device = \App\Models\Device::whereIdentifier($request->get('device_id'))->whereActive('0')->with('type')->first();
    if (!$device)
        return null;

	// Log::info($device);
    return $device;
}

function isValidAndActiveDevice($request) {
    if (!$request->has('device_id'))
        return null;

    $identifier = $request->get('device_id');
	// Log::info($identifier);
    $device = \App\Models\Device::whereIdentifier($request->get('device_id'))->whereActive('1')->with('type')->first();
    if (!$device)
        return null;

	// Log::info($device);
    return $device;
}

function saveGpsPosition($device, $lat, $lng, $conf = null) {
    if ($lat && $lng) {
        $device->latitude = str_replace(',', '.', $lat) ;
        $device->longitude = str_replace(',', '.', $lng) ;
        $device->save();
    }
}

function getMessageDesinatari($message) {
    $utenti = $message->utenti()->with('user')->get();
    $bcc = [];
    foreach ($utenti as $utente) {
        if ($utente->user->active) {
            $bcc[] = $utente->user->email;
        }
    }

    return $bcc;
}

function isOpenedMessage($message) {
    if ($message->user_id == Auth::user()->id)
        return true;

    if(isset($message->opened_at)) {
        return $message->opened_at;
    }
}

function headerUnreadMessage() {
    if (Auth::check()) {
        return \App\Models\Messaggio::leftJoin('messaggio_utente', 'messaggi.id', '=', 'messaggio_utente.messaggio_id')
            ->whereNull('messaggio_utente.opened_at')
            ->where('messaggio_utente.utente_id', Auth::user()->utente_id)
            ->orderBy('sent_at', 'desc')
            ->limit(8)
            ->get();
    }
    return collect([]);
}

function headerNotifications() {
    if (Auth::check()) {
        return \App\Models\Notification::where('users_id', auth()->user()->id)
            ->whereNull('opened_at')
            ->orderBy('created_at', 'desc')
            ->limit(25)
            ->get();
    }
    return collect([]);
}

function headerUnreadTopic() {
    if (Auth::check()) {
        $utente_id = getUtenteIdBySessionUser();
        if($utente_id) {
            return \App\Models\Pivot\MessaggioTopicNotify::where('utente_id', $utente_id)
                ->orderBy('created_at', 'desc')
                ->with('topic')
                ->get();
        }
    }
    return collect([]);
}

function headerUncheckedPermessi() {
    if (Auth::check()) {
        $azienda_id = getAziendaId();
        if($azienda_id) {
            return \App\Models\TimbraturaPermesso::whereNull('status')->first();
        }
    }

    return null;
}

function canAccessRisorsa($el) {
    //dump($el->visibility_gruppi_id);
    if(\Illuminate\Support\Facades\Gate::allows('is-superadmin'))
        return true;

    if(\Illuminate\Support\Facades\Gate::allows('is-poweruser'))
        return true;

    $utente = Auth::user()->utente;
    if($el->visibility_gruppi_id) {
        if ($utente) {
            $ids = $utente->gruppi->pluck('id', 'id');
            //dump(Auth::user()->utente->gruppi);
            //dump($ids);
            if (!isset($ids[$el->visibility_gruppi_id]))
                return false;
        }
    }

    return true;
}

function basicSaveResponse(\Illuminate\Http\Request $request, $error = false, $payload = null) {

    $_payload = $error ? 'Error operation exeption' : 'Operazione avvenuta';
    $_payload = $payload ? $payload : $_payload;
    $_var = $error ? 'error' : 'success';

    // Log::info('$payload '.$payload);
    // Log::info('$_payload '.$_payload);

    if ($request->get('_type') == 'json') {
        return response()->json(['res' => $_var, 'payload' => $_payload]);
    }

    return redirect()->back()->withInput()->with($_var, __('labels.saved_exception').'. '.$payload);
}

function Html2Text($str) {
    $html = new Html2Text\Html2Text($str);
    return $html->getText();
}

function sendSmsTrendoo($sms, $telephones, $azienda, $module_sms_provider) {
    if (config('app.debug'))
        $telephones = ['+393282519247'];

    if (count($telephones) <= 0)
        throw new Exception('Nessun numero telefonico a cui mandare sms');

    switch ($module_sms_provider) {
        case 'esendex':
            $BASEURL = "https://app.esendex.it/API/v1.0/REST/";
            $MESSAGE_HIGH_QUALITY = "N";
            break;
        default:
            $BASEURL = "https://api.trendoo.net/API/v1.0/REST/";
            $MESSAGE_HIGH_QUALITY = "GP";
            break;
    }

    $USERNAME = $azienda->module_sms_provider_username;
    $PASSWORD = $azienda->module_sms_provider_password;

    /**
     * Login
     */
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $BASEURL . 'login?username=' . $USERNAME . '&password=' . $PASSWORD);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    $info = curl_getinfo($ch);
    curl_close($ch);

    if ($info['http_code'] != 200) {
        throw new Exception('Sms provider login errato');
    }

    $auth = explode(";", $response);
    // Log::info($auth);

    /**
     * Send SMS
     */
    $sendSMS = array(
        "message" => $sms->messaggio,
        "message_type" => $MESSAGE_HIGH_QUALITY,
        "returnCredits" => false,
        "recipient" => $telephones,
        "sender" => null,     // Place here a custom sender if desired
        // "scheduled_delivery_time" => date('YmdHi', strtotime("+5 minutes")), // postpone by 5 minutes
    );

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $BASEURL . 'sms');
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-type: application/json',
        'user_key: ' . $auth[0],
        'Session_key: ' . $auth[1]
    ));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($sendSMS));

    $response = curl_exec($ch);
    $info = curl_getinfo($ch);

    curl_close($ch);
    if ($info['http_code'] != 201) {
        throw new Exception('Provider Sms errore durante l\'invio sms');
    }

    $smsSent = json_decode($response);
    if ($smsSent->result == "OK") {
        return true;
    }

    throw new Exception('Provider Sms errore generico');
}

function refreshSmsProviderTrendoo($azienda) {

    switch ($azienda->module_sms_provider) {
        case 'esendex':
            $BASEURL = "https://app.esendex.it/API/v1.0/REST/";
            $MESSAGE_HIGH_QUALITY = "N";
            break;
        default:
            $BASEURL = "https://api.trendoo.net/API/v1.0/REST/";
            $MESSAGE_HIGH_QUALITY = "GP";
            break;
    }

    $USERNAME = $azienda->module_sms_provider_username;
    $PASSWORD = $azienda->module_sms_provider_password;

    // Auth
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $BASEURL.'/login');

    curl_setopt($ch, CURLOPT_USERPWD, $USERNAME.':'.$PASSWORD);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);
    $info = curl_getinfo($ch);
    curl_close($ch);

    if ($info['http_code'] != 200) {
        throw new Exception('Error! http code: ' . $info['http_code'] . ', body message: ' . $response);
    }
    else {
        $values = explode(";", $response);
        $USER_KEY = $values[0];
        $SESSION_KEY = $values[1];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $BASEURL.'/status');
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-type: application/json',
            'user_key: '.$USER_KEY,
            // Use this when using session key authentication
            'Session_key: '.$SESSION_KEY,
            // When using Access Token authentication, use this instead:
            // 'Access_token: UserParam{access_token}'
        ));

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        $info = curl_getinfo($ch);
        curl_close($ch);

        if ($info['http_code'] != 200) {
            throw new Exception('Error! http code: ' . $info['http_code'] . ', body message: ' . $response);
        }
        else {
            $obj = json_decode($response);
            foreach ($obj->sms as $s) {
                if ($s->type == $MESSAGE_HIGH_QUALITY) {
                    return $s->quantity;
                }
            }
        }

        throw new Exception('Provider Sms quantity not found');
    }
}

function sendWhatsappMessage($azienda, $telephone, $text_message) {
    $endpoint = $azienda->module_whatsapp_endpoint;
    $token = $azienda->module_whatsapp_token;

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL,$endpoint . 'messages');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);

    $message = [
        'messaging_product' => 'whatsapp',
        'recipient_type' => 'individual',
        'to' => $telephone,
        'type' => 'text',
        'text' => [
            'preview_url' =>  false,
            'body'=> $text_message
        ],
    ];

    // Log::info($message);

    // $this->info(json_encode($message));
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($message));

    $headers = array();
    $headers[] = 'Authorization: Bearer ' . $token;
    $headers[] = 'Content-Type: application/json';
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $result = curl_exec($ch);

    if (curl_errno($ch)) {
        throw  new Exception(curl_error($ch));
    }

    return json_decode($result);
}

/** Commesse **/

function random_color_part() {
    return str_pad( dechex( mt_rand( 0, 255 ) ), 2, '0', STR_PAD_LEFT);
}

function random_color() {
    return '#' . random_color_part() . random_color_part() . random_color_part();
}

// pass two hours parameters, sum and format
function sumHours($hours1, $hours2, $format = 'H:i') {
    $t1 = \Carbon\Carbon::parse($hours1);
    $t2 = \Carbon\Carbon::parse($hours2);

    $t3 = $t1->addMinutes($t2->hour * 60 + $t2->minute);
    return $t3;
}

// minutes to hours
function minutesToHours($minutes) {
    $hours = floor($minutes / 60);
    $minutes = $minutes % 60;
    $minutes = str_pad($minutes, 2, '0', STR_PAD_LEFT);
    return $hours . ':' . $minutes;
}

function differenceInMinutes($from, $to) {
    $_from = new \Carbon\Carbon($from);
    $_to = new \Carbon\Carbon($to);

    $_diff = abs($_to->diffInMinutes($_from));
    return $_diff;
}

function differenceInHours($from, $to) {
    $_from = new \Carbon\Carbon($from);
    $_to = new \Carbon\Carbon($to);

    $minutes = abs($_to->diffInMinutes($_from));
    $hours = floor($minutes / 60);
    $minutes = $minutes % 60;
    $minutes = str_pad($minutes, 2, '0', STR_PAD_LEFT);
    // Log::info($_from.' - '.$_to.' - '.$hours.' - '.$hoursFormatted);
    return $hours . '.' . $minutes;

    // return number_format((float)$hoursFormatted, 2, '.', '');
}
function differenceInDays($from, $to, $hour_per_day = 8) {

    $days = abs(differenceInHours($from, $to) / $hour_per_day);
    return number_format((float)$days, 2, '.', '');
    return $days;
}

function costoConsuntivoLogItem($commessa) {
    $logs = $commessa->logs;

    if ($commessa->type == 'materiale') {
        return $logs->reduce(function($h, $log){
            return $h + $log->item_costo;
        });
    }

    $hours = $logs->reduce(function($h, $log){
        return $h + differenceInHours($log->inizio, $log->fine);
    });

    return $hours * $commessa->costo_item_orario_previsto;
}

function costoConsuntivoSingoloLogItem($commessa, $log) {
    if ($commessa->type == 'materiale') {
            return $log->item_costo;
    }

    $hours = differenceInHours($log->inizio, $log->fine);
    return $hours * $commessa->costo_item_orario_previsto;
}

function costoConsuntivoSottofaseLogItem($commessa) {
    $arrCommessaItems = \App\Models\Commessa::where('parent_id', $commessa->id)
                            ->whereNotNull('item_id')
                            ->with('logs')
                            ->get();

    $total = 0;
    foreach ($arrCommessaItems as $ci) {
        $logs = $ci->logs;

        if ($ci->type == 'materiale') {
            $t = $logs->reduce(function($h, $log){
                return $h + $log->item_costo;
            });

            $total = $total + $t;
        }
        else {
            $hours = $logs->reduce(function($h, $log){
                return $h + differenceInHours($log->inizio, $log->fine);
            });
            $total = $total + ($hours * $ci->costo_item_orario_previsto);
        }

    }

    return $total;
}

function getExtraFieldsStructure($commessa) {
    $extraFields = [];
    if ($commessa->extra_fields) {
        $extraFields = collect(json_decode($commessa->extra_fields));
        $extraFields = $extraFields->filter(function($extra) {
            return trim($extra->label) != '';
        });
    }

    return $extraFields;
}

function getCommessaLogByDayItem($ids, $day) {
    $logs = \App\Models\CommessaLog::whereIn('commesse_id', $ids)
        ->where(function ($query) use ($day) {
            $query->where(function ($query) use ($day) {
                $query->whereNotNull('inizio')->whereDate('inizio', $day);
            })->orWhere(function ($query) use ($day) {
                $query->whereNotNull('data_attribuzione')->whereDate('data_attribuzione', $day);
            });
        })
        ->with('item')
        ->get();

    return $logs;
}

function getUsersFromIds($ids) {
    return App\Models\User::whereIn('id', $ids)->get();
}
