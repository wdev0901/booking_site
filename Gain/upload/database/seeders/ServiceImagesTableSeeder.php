<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ServiceImagesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('service_images')->insert([
            [
                'image' => 'hair-design.jpg',
                'service_id' => 4,
                'is_default' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'image' => 'spa.jpg',
                'service_id' => 5,
                'is_default' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'image' => 'haircut.jpg',
                'service_id' => 6,
                'is_default' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],

            //new
            [
                'image' => 'pedicure.jpg',
                'service_id' => 7,
                'is_default' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'image' => 'touchup.jpg',
                'service_id' => 8,
                'is_default' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'image' => 'makeup.jpg',
                'service_id' => 9,
                'is_default' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);
    }
}
