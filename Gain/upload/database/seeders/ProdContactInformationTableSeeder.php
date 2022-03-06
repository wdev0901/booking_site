<?php


namespace Database\Seeders;


use App\Models\ContactInfo;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProdContactInformationTableSeeder extends Seeder
{
    public function run()
    {
        ContactInfo::query()->truncate();

        DB::table('contact_infos')->insert([
            'title' => 'Contact Us',
            'sub_title' => 'We\'d love to hear from you and stay with us.',
            'address' => '',
            'email' => '',
            'phone' => '',
            'created_at' => Carbon::now()->toDateTimeString(),
            'updated_at' => Carbon::now()->toDateTimeString(),
        ]);

    }

}