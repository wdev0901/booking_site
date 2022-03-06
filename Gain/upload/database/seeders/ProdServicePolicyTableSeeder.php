<?php


namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class ProdServicePolicyTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('service_policies')->insert([
            'custom_content' => '',
            'created_at' => Carbon::now()->toDateTimeString(),
            'updated_at' => Carbon::now()->toDateTimeString(),
        ]);
    }
}