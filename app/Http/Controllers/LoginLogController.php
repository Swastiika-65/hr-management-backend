<?php

namespace App\Http\Controllers;

use App\Models\LoginLog;
use Illuminate\Http\Request;

class LoginLogController extends Controller
{
    public function login()
    {
        LoginLog::create([
            'user_id'=>auth()->id(),
            'login_at'=>now()
        ]);
    }

    public function logout()
    {
        LoginLog::where('user_id',auth()->id())
            ->latest()
            ->update(['logout_at'=>now()]);
    }
}
