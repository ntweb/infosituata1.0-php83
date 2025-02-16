<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
class UserController extends Controller
{
    function me(Request $request) {
        return \auth()->user();
    }
}
