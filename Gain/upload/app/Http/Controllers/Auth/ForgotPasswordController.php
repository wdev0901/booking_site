<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function showLinkRequest()
    {
        $copyright_text = Setting::where('setting_name', 'copyright_text')->first()->setting_value;
        return view('auth.passwords.email',
            ['copyright_text' => $copyright_text]
        );
    }

    use SendsPasswordResetEmails;
}
