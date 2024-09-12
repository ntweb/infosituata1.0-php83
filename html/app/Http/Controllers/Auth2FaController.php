<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;

class Auth2FaController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
    }



    /**

     * Show the application dashboard.

     *

     * @return \Illuminate\Contracts\Support\Renderable

     */

    public function index(Request $request)
    {
        return view('2fa.index');
    }

    public function check(Request $request)
    {
        $validationRules = [
            'code' => 'required',
        ];

        $validatedData = $request->validate($validationRules);

        $code = $request->get('code');
        $exists = DB::table('users')
            ->where('id', Auth::user()->id)
            ->where('_2fa_code', $code)
            ->where('_2fa_code_expiration', '>=', date('Y-m-d H:i:s'))
            ->get();

        if (!$exists->count()) {
            return redirect()->back()->withInput()->with('message', 'Codice non valido o scaduto!');
        }

        DB::table('users')->where('id', Auth::user()->id)->update([
            '_2fa_code' => null,
            '_2fa_code_expiration' => null,
        ]);

        return redirect()->route('dashboard.index');
    }

}

