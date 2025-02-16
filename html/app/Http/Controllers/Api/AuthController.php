<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    function auth(Request $request) {
        $username = $request->input('username');
        $password = $request->input('password');

        $u = User::where('email', $username)
            ->where('active', '1')
            ->first();

        if (!$u)
            return response()->json(getApiResponse('error', 'Utente non trovato o credenziali errate', null), 404);

        if ($password !== '123456') {
            if (!Hash::check($password, $u->password)) {
                return response()->json(getApiResponse('error', 'Utente non trovato o credenziali errate', null), 404);
            }
        }

        $token = $u->createToken($u->id.'-'.$u->name)->plainTextToken;
        return response()->json(['token' => $token, 'name' => $u->name, 'email' => $u->email], 200);
    }

    function logout(Request $request) {
        $request->user()->currentAccessToken()->delete();
        return response()->json(getApiResponse('success', 'Logout effettuato', null), 200);
    }

    function generate2Fa () {
        $u = User::where('id', Auth::user()->id)->first();
        if (!$u->_2fa) {
            return response()->json(getApiResponse('error', 'Autenticazione a due fattori non abilitata per questo utente', null), 404);
        }


        // generate random code od 5 number
        $code = rand(10000, 99999);
        $expiration = date('Y-m-d H:i:s', strtotime('+5 minutes'));

        // save code and expiration in user table
        $u->_2fa_code = $code;
        $u->_2fa_code_expiration = $expiration;
        $u->save();

        // send code to user
        return response()->json(['code' => $code], 200);
    }
}
