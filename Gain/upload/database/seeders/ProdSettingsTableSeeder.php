<?php


namespace Database\Seeders;


use App\Models\Setting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProdSettingsTableSeeder extends Seeder
{
    public function run()
    {
        Setting::query()->truncate();

        DB::table('settings')->insert([
            ['setting_name'     => 'business_type',                 'setting_value'   =>'salon'],
            ['setting_name'     => 'time_format',                   'setting_value'   => '12h'],
            ['setting_name'     => 'time_zone',                     'setting_value'   => 'UTC'],
            ['setting_name'     => 'date_format',                   'setting_value'   => 'Y-m-d'],
            ['setting_name'     => 'currency_symbol',               'setting_value'   => '$'],
            ['setting_name'     => 'currency_format',               'setting_value'   => 'left'],
            ['setting_name'     => 'thousand_separator',            'setting_value'   => ','],
            ['setting_name'     => 'language_setting',              'setting_value'   => 'english'],
            ['setting_name'     => 'decimal_separator',             'setting_value'   => '.'],
            ['setting_name'     => 'number_of_decimal',             'setting_value'   => '2'],
            ['setting_name'     => 'offday_setting',                'setting_value'   => ''],
            ['setting_name'     => 'email_from_name',               'setting_value'   => ''],
            ['setting_name'     => 'email_from_address',            'setting_value'   => ''],
            ['setting_name'     => 'email_driver',                  'setting_value'   => ''],
            ['setting_name'     => 'email_smtp_host',               'setting_value'   => ''],
            ['setting_name'     => 'email_port',                    'setting_value'   => ''],
            ['setting_name'     => 'email_smtp_password',           'setting_value'   => ''],
            ['setting_name'     => 'email_encryption_type',         'setting_value'   => ''],
            ['setting_name'     => 'mandrill_api',                  'setting_value'   => ''],
            ['setting_name'     => 'sparkpost_api',                 'setting_value'   => ''],
            ['setting_name'     => 'mailgun_domain',                'setting_value'   => ''],
            ['setting_name'     => 'mailgun_api',                   'setting_value'   => ''],
            ['setting_name'     => 'max_row_per_table',             'setting_value'   => '11'],
            ['setting_name'     => 'app_name',                      'setting_value'   => 'Gain Salon Booking'],
            ['setting_name'     => 'app_logo',                      'setting_value'   => 'default-logo.png'],
            ['setting_name'     => 'currency_code',                 'setting_value'   => 'usd'],
//            ['setting_name'     => 'purchase_code',                 'setting_value'   => ''],
            ['setting_name'     => 'can_signup',                    'setting_value'   => '1'],
            ['setting_name'     => 'can_login',                     'setting_value'   => '1'],
            ['setting_name'     => 'invoice_logo',                   'setting_value'   => 'default-logo.jpg'],
            ['setting_name'     => 'submit_booking_after_login',    'setting_value'   => 0],
            ['setting_name'     => 'landing_page_message',          'setting_value'   =>"Let's make you an appointment to keep yourself attractive."],
            ['setting_name'     => 'landing_page_header',           'setting_value'   =>'Beauty & Salon'],
            ['setting_name'     => 'company_name',                  'setting_value'   =>'Demo Company Name'],
            ['setting_name'     => 'copyright_text',                'setting_value'   =>'Copyright @ 2020 by Gain Salon Booking'],
            ['setting_name'     => 'contact_us_email',              'setting_value'   =>'booking@demo.com'],
            ['setting_name'     => 'sms_from_name_phone_number',    'setting_value'   =>''],
            ['setting_name'     => 'sms_driver',                    'setting_value'   =>''],
            ['setting_name'     => 'key',                           'setting_value'   =>''],
            ['setting_name'     => 'secret_key',                    'setting_value'   =>''],
        ]);
    }
}