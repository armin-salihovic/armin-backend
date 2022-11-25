<?php

namespace App\Http\Controllers;

use App\Mail\MessageSent;
use Illuminate\Http\Request;
use Mail;

class MailController extends Controller
{
    public function sendEmail(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'subject' => 'required',
            'message' => 'required',
        ]);

        Mail::to(['hello@armin.ba'])
            ->bcc(['armin.salihovic@live.com'])
            ->send(new MessageSent($request));

        return response()->json('OK!');
    }
}
