<?php

namespace App\Http\Controllers\API;

use App\Libraries\AllSettingsFormat;
use App\Libraries\Email;
use App\Models\Setting;
use App\Models\PaymentMethod;
use Cache;
use DateTimeZone;
use Illuminate\Contracts\Session\Session;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use App\Libraries\Permissions;
use Config;
use Illuminate\Support\Facades\Lang;


class SettingController extends Controller
{
    public function permissionCheck()
    {
        $controller = new Permissions;
        return $controller;
    }

    public function index()
    {
        $timezone = timezone_identifiers_list();
        $allTimezone = array();
        foreach ($timezone as $key => $value) {
            $temp = [
                'id' => $key,
                'name' => $value
            ];
            array_push($allTimezone, $temp);
        }
        return view('setting.index', compact('allTimezone'));
    }

    public function getAppPublicPath()
    {
        return $this->publicPath;
    }

    public function cacheData()
    {
        return Setting::saveCacheData();
    }

    public function getSmsData()
    {
        Artisan::call('cache:clear');

        return $this->cacheData();
    }

    public function smsSettingUpdate(Request $request)
    {
        $smsData = array(
            'sms_from_name_phone_number' => $request->sms_from_name_phone_number,
            'sms_driver' => $request->sms_driver,
            'key' => $request->key,
            'secret_key' => $request->secret_key,
        );

        Setting::updateSettingData($smsData);

        Cache::flush() && $this->cacheData();

        $response = [
            'message' => Lang::get('lang.sms_settings') . ' ' . Lang::get('lang.successfully_saved')
        ];
        return response()->json($response, 200);
    }

    public function getTimezone()
    {
        return $tzlist = DateTimeZone::listIdentifiers(DateTimeZone::ALL);
    }

    public function offdays(Request $request)
    {
        $offday_setting = $request->offday_setting;
        $offday = Setting::where('setting_name', '=', 'offday_setting')->first();

        if ($offday == null) {
            Setting::create(['setting_name' => 'offday_setting', 'setting_value' => '', 'setting_type' => 'app', 'created_by' => Auth::user()->id]);
        }
        $updateData = Setting::where('setting_name', 'offday_setting')->update(['setting_value' => implode(',', $offday_setting)]);

        Cache::flush() && $this->cacheData();

        if ($updateData) {
            $response = [
                'message' => Lang::get('lang.offday_successfully_saved')
            ];
            return response()->json($response, 200);
        } else {
            $response = [
                'message' => Lang::get('lang.error_update')
            ];
            return response()->json($response, 404);
        }
    }

    public function offdaysData()
    {
        return $this->cacheData();
    }

    public function dateFormat()
    {
        $settingAll = new AllSettingsFormat;
        return $settingAll->getDateFormat();
    }

    public function emailSettingSave(Request $request)
    {
        $this->validate($request, [
            'email_from_name' => 'required',
            'email_from_address' => 'required',
            'email_driver' => 'required',
        ]);

        $name = $request->email_from_name;
        $address = $request->email_from_address;
        $driver = $request->email_driver;
        $host = $request->email_smtp_host;
        $port = $request->email_port;
        $pass = $request->email_smtp_password;
        $type = $request->email_encryption_type;
        $mailgunDomain = $request->mailgun_domain;
        $mailgunApi = $request->mailgun_api;
        $mandrill = $request->mandrill;
        $sparkpost = $request->sparkpost;

        $emailData = array(
            'email_from_name' => $name,
            'email_from_address' => $address,
            'email_driver' => $driver,
            'email_smtp_host' => $host,
            'email_port' => $port,
            'email_smtp_password' => $pass,
            'email_encryption_type' => $type,
            'mailgun_domain' => $mailgunDomain,
            'mailgun_api' => $mailgunApi,
            'mandrill_api' => $mandrill,
            'sparkpost_api' => $sparkpost
        );
        $updateData = Setting::updateSettingData($emailData);

        if ($request->test_mail != '') {
            return $this->testMail($request->test_mail);
        }
        Cache::flush() && $this->cacheData();

        if ($updateData) {
            $response = [
                'message' => Lang::get('lang.email_settings_successfully_saved')
            ];
            return response()->json($response, 200);
        } else {
            $response = [
                'message' => Lang::get('lang.error_update')
            ];
            return response()->json($response, 404);
        }

    }


    public function emailSettingData()
    {
        return $this->cacheData();
    }

    public function basicSettingSave(Request $request)
    {
        $time_format = $request->time_format;
        $date_format = $request->date_format;
        $time_zone = $request->time_zone;
        $currency_symbol = $request->currency_symbol;
        $currency_format = $request->currency_format;
        $currency_code = trim($request->currency_code);
        $thousand_separator = $request->thousand_separator;
        $language_setting = $request->language_setting;
        $decimal_separator = $request->decimal_separator;
        $number_of_decimal = $request->number_of_decimal;
        $max_row_per_table = $request->max_row_per_table;
        $app_name = $request->app_name;
        $landing_page_message = $request->landing_page_message;
        $landing_page_header = $request->landing_page_header;
        $contact_us_email = $request->contact_us_email;
        $company_name = $request->company_name;
        $copyright_text = $request->copyright_text;
        $business_type = $request->business_type;
        $app_logo = '';

        Setting::where('setting_name', 'time_format')->update(['setting_value' => $time_format]);
        Setting::where('setting_name', 'date_format')->update(['setting_value' => trim($date_format)]);
        Setting::where('setting_name', 'time_zone')->update(['setting_value' => $time_zone]);
        Setting::where('setting_name', 'currency_symbol')->update(['setting_value' => $currency_symbol]);
        Setting::where('setting_name', 'currency_format')->update(['setting_value' => $currency_format]);
        Setting::where('setting_name', 'currency_code')->update(['setting_value' => $currency_code]);
        Setting::where('setting_name', 'thousand_separator')->update(['setting_value' => $thousand_separator]);
        Setting::where('setting_name', 'language_setting')->update(['setting_value' => $language_setting]);
        Setting::where('setting_name', 'decimal_separator')->update(['setting_value' => $decimal_separator]);
        Setting::where('setting_name', 'number_of_decimal')->update(['setting_value' => $number_of_decimal]);
        Setting::where('setting_name', 'max_row_per_table')->update(['setting_value' => $max_row_per_table]);
        Setting::where('setting_name', 'app_name')->update(['setting_value' => $app_name]);
        Setting::where('setting_name', 'landing_page_message')->update(['setting_value' => $landing_page_message]);
        Setting::where('setting_name', 'landing_page_header')->update(['setting_value' => $landing_page_header]);
        Setting::where('setting_name', 'contact_us_email')->update(['setting_value' => $contact_us_email]);
        Setting::where('setting_name', 'company_name')->update(['setting_value' => $company_name]);
        Setting::where('setting_name', 'copyright_text')->update(['setting_value' => $copyright_text]);
        Setting::where('setting_name', 'business_type')->update(['setting_value' => $business_type]);

        if ($request->invoice_logo) {
            if ($request->invoice_logo == $this->settingConfig->get('invoice_logo')) {
                $invoice_logo = $this->settingConfig->get('invoice_logo');
            } else {
                if ($file = $request->invoice_logo) {
                    list($type, $file) = explode(';', $request->invoice_logo);
                    list(, $extension) = explode('/', $type);
                    $fileName = uniqid() . '.' . $extension;
                    $source = fopen($request->invoice_logo, 'r');
                    $destination = fopen(public_path() . '/uploads/invoice/' . $fileName, 'w');
                    stream_copy_to_stream($source, $destination);
                    fclose($source);
                    fclose($destination);
                    $invoice_logo = $fileName;
                }

                if ($this->settingConfig->get('invoice_logo') != 'invoice.jpg' && $this->settingConfig->get('invoice_logo') != '' && file_exists(public_path() . '/uploads/invoice/' . $this->settingConfig->get('invoice_logo'))) {
                    unlink(public_path() . '/uploads/invoice/' . $this->settingConfig->get('invoice_logo'));
                }
            }
            Setting::where('setting_name', 'invoice_logo')->update(['setting_value' => $invoice_logo]);
        }

        if ($request->app_logo) {
            if ($request->app_logo == $this->settingConfig->get('app_logo')) {
                $app_logo = $this->settingConfig->get('app_logo');
            } else {
                if ($file = $request->app_logo) {
                    list($type, $file) = explode(';', $request->app_logo);
                    list(, $extension) = explode('/', $type);
                    $fileName = uniqid() . '.' . $extension;
                    $source = fopen($request->app_logo, 'r');
                    $destination = fopen(public_path() . '/uploads/logo/' . $fileName, 'w');
                    stream_copy_to_stream($source, $destination);
                    fclose($source);
                    fclose($destination);
                    $app_logo = $fileName;
                }

                if ($this->settingConfig->get('app_logo') != 'default-logo.png' && $this->settingConfig->get('app_logo') != '' && file_exists(public_path() . '/uploads/logo/' . $this->settingConfig->get('app_logo'))) {
                    unlink(public_path() . '/uploads/logo/' . $this->settingConfig->get('app_logo'));
                }
            }
            Setting::where('setting_name', 'app_logo')->update(['setting_value' => $app_logo]);
        }

        if ($request->background_image) {
            $File = new Filesystem;
            $path = public_path() . '/images/background/background-image.jpeg';
            if ($File->exists($path)) {
                unlink($path);
            }
            if ($file = $request->app_logo) {
                list($type, $file) = explode(';', $request->background_image);
                list(, $extension) = explode('/', $type);
                $fileName = 'background-image.jpeg';
                $source = fopen($request->background_image, 'r');
                $destination = fopen(public_path() . '/images/background/' . $fileName, 'w');
                stream_copy_to_stream($source, $destination);
                fclose($source);
                fclose($destination);
            }
        }

        session()->put('language_setting', $language_setting);

        //language cache clear
        Cache::flush() && $this->cacheData();
        
        $response = [
            'message' => Lang::get('lang.apps_successfully_update')
        ];

        return response()->json($response, 200);
    }

    public function getAppLogo()
    {
        return $this->settingConfig->get('app_logo');
    }

    public function basicSettingData()
    {
        return [
            'basicData' => $this->cacheData(),
            'language' => $this->getLanguageDirectory()
        ];
    }

    public function testMail($email)
    {
        $appName = Setting::select('setting_value')->where('setting_name', 'email_from_name')->first()->setting_value;
        $sub = Lang::get('lang.test_mail');
        $emailHeader = '<html>
                           <div style="width: 35%; color: #333333; font-family: Helvetica; margin:auto; font-size: 125%; padding-bottom: 10px;">
                               <div style="text-align:center; padding-top: 10px; padding-bottom: 10px;">
                                   <h1>' . $appName . '</h1>
                               </div>
                               <div style="padding: 35px;padding-left:20px; border-bottom: 1px solid #cccccc; border-top: 1px solid #cccccc;">';
        $emailFooter = '        </div>
                           </div>
                       </html>';
        $text = $emailHeader . Lang::get('lang.this_is_a_test_email') . $emailFooter;
        $eSend = new Email;

        if ($eSend->sendEmail($text, $email, $sub)) {
            return response()->json(['message' => Lang::get('lang.email_settings_successfully_saved')]);
        }
    }

    public function clientSetting()
    {
        $clientSetting = [
            'can_signup' => (int)Setting::select('setting_value')->where('setting_name', 'can_signup')->first()->setting_value,
            'can_login' => (int)Setting::select('setting_value')->where('setting_name', 'can_login')->first()->setting_value,
            'submit_booking_after_login' => (int)Setting::select('setting_value')->where('setting_name', 'submit_booking_after_login')->first()->setting_value,
        ];
        return $clientSetting;
    }

    public function updateClientSetting(Request $request)
    {
        if (count($request->all())) {

            $signup = Setting::where('setting_name', 'can_signup')->update(['setting_value' => $request->can_signup]);
            $login = Setting::where('setting_name', 'can_login')->update(['setting_value' => $request->can_login]);
            $submit_booking = Setting::where('setting_name', 'submit_booking_after_login')->update(['setting_value' => $request->submit_booking_after_login]);

            if ($signup && $login && $submit_booking) {
                $response = [
                    'message' => Lang::get('lang.client_setting_saved_successfully')
                ];
                return response()->json($response, 200);
            } else {
                $response = [
                    'message' => Lang::get('lang.error_update')
                ];
                return response()->json($response, 404);
            }
        }

    }

    public function knowDefaultRowSettings()
    {
        $data = Setting::select('setting_value')->where('setting_name', 'max_row_per_table')->first()->setting_value;
        return $data;
    }

    public function dateTimeFormat()
    {
        $settingAll = new AllSettingsFormat;
        return $dateTimeFormat = strtoupper($settingAll->getDateFormat()) . " " . $settingAll->getTimeFormat();
    }

    public function currentUserId()
    {
        return Auth::user()->id;
    }

    public function getPaypalClientId()
    {
        $data = PaymentMethod::where('type', 'paypal')->first();

        if ($data->option){
            $data = unserialize($data->option);
            return $data[1];
        }else{

           return null;
        }


    }

    public function getCurrencyCode()
    {
        return Setting::where('setting_name', 'currency_code')->first()->setting_value;
    }

    public function getLanguageDirectory()
    {
        $files2 = array_diff(scandir(resource_path('lang')), array('..', '.', '.DS_Store'));

        return $files2;
    }

    public function getSmsSettingStatus()
    {
        /* check if sms setting saved*/
        $isSmsSettingSet = false;
        $isSmsSetting = Setting::where('setting_name', 'sms_driver')->first()->setting_value;
        if ($isSmsSetting) $isSmsSettingSet = true;

        return ['isSmsSettingSet' => $isSmsSettingSet];
    }

}
