<?php
namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ContactInformationTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('contact_infos')->insert([
            'title' => 'Contact Us',
            'sub_title' => 'We\'d love to hear from you and stay with us.',
            'address' => '888 53rd St. Los Angeles, CA 90026',
            'email' => 'demo@email.com',
            'phone' => '+0123456789',
            'created_at' => Carbon::now()->toDateTimeString(),
            'updated_at' => Carbon::now()->toDateTimeString(),
        ]);
    }
}
