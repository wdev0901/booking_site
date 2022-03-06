<?php

namespace App\Http\Controllers;

use App\Libraries\Email;
use App\Models\Contact;
use App\Models\EmailTemplate;
use App\Models\Setting;
use App\Models\Services;
use DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;


class HomeController extends Controller
{
    public function homePage($serviceAlias = '')
    {
        $installCheck = config('gain.installed');

        if ($installCheck == true) {

            $signupCheck = (object)[
                'can_signup' => (int)Setting::select('setting_value')->where('setting_name', 'can_signup')->first()->setting_value,
                'can_login' => (int)Setting::select('setting_value')->where('setting_name', 'can_login')->first()->setting_value,
                'submit_booking_after_login' => (int)Setting::select('setting_value')->where('setting_name', 'submit_booking_after_login')->first()->setting_value,
            ];

            $user = Auth::user();

            if (Auth::check() && Auth::user()->is_admin == 1 || Auth::check() && Auth::user()->role_id != 0) {

                return redirect('/dashboard');
            }

            $landingPageMessage = Setting::where('setting_name', 'landing_page_message')->first()->setting_value;
            $landingPageHeader = Setting::where('setting_name', 'landing_page_header')->first()->setting_value;
            $copyright_text = Setting::where('setting_name', 'copyright_text')->first()->setting_value;

            $service = 'false';
            if (!empty($serviceAlias)) {
                $service = Services::with('serviceImage')->where('alias', $serviceAlias)->first();
            }
            return view('welcome', [
                'signupCheck' => \GuzzleHttp\json_encode($signupCheck),
                'user' => $user,
                'service' => $service,
                'copyright_text' => $copyright_text,
                'landingPageMessage' => $landingPageMessage, 'landingPageHeader' => $landingPageHeader
            ]);

        } else {

            return redirect('/install');
        }

    }

    public function chooseDemoPage()
    {
        return view('choose-demo');
    }

    public function updateBusinessType(Request $request)
    {
        $businessType = $request->business_type;
        Setting::where('setting_name', 'business_type')->update(['setting_value' => $businessType]);

        return response()->json('', 200);
    }

    public function contactUs(Request $request)
    {

        $this->validate($request, [
            'email' => 'required',
            'message' => 'required',
        ]);
        $contact = new Contact();
        $contact->email = $request->email;
        $contact->subject = $request->subject;
        $contact->message = $request->message;
        $contact->save();


        $content = EmailTemplate::select('template_subject', 'default_content', 'custom_content')->where('template_type', 'contact_us')->first();
        $subject = $content->template_subject;

        if ($content->custom_content) {
            $text = $content->custom_content;

        } else {
            $text = $content->default_content;
        }

        $appName = Setting::select('setting_value')->where('setting_name', 'email_from_name')->first()->setting_value;

        $mailText = str_replace('{message}', $request->message, str_replace('{app_name}', $appName, $text));

        $emailSend = new Email();
        $email = Setting::select('setting_value')->where('setting_name', 'contact_us_email')->first()->setting_value;
        if (!$emailSend->sendEmail($mailText, $email, $subject)) {
            return response()->json('', 201);
        } else {
            return response()->json('', 201);
        }
    }
}
