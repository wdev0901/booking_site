<?php
namespace Database\Seeders;

use App\Models\SmsTemplate;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SmsTemplatesTableSeeder extends Seeder
{
    public function run()
    {
        SmsTemplate::query()->truncate();

        DB::table("sms_templates")->insert([
            'template_type' => 'verification_code',
            'template_subject' => 'Verification code',
            'default_content' =>
                'Your verification code is {verification_code}'
        ]);
    }
}