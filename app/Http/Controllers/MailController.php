<?php

namespace App\Http\Controllers;

use App\Models\Mail;
use Illuminate\Http\Request;

class MailController extends Controller
{
    public function send(Request $request)
    {
        Mail::create([
            'from_id'=>auth()->id(),
            'to_id'=>$request->to_id,
            'subject'=>$request->subject,
            'message'=>$request->message
        ]);
    }
}

