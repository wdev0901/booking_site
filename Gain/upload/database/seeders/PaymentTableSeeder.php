<?php
namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaymentTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('payments')->insert([

            ['booking_id' => 1,
                'currency_code' => 'usd',
                'paid_amount' => 0,
                'method_id' => 1,
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString()],
            ['booking_id' => 2,
                'currency_code' => 'usd',
                'paid_amount' => 0,
                'method_id' => 1,
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString()],
            ['booking_id' => 3,
                'currency_code' => 'usd',
                'paid_amount' => 0,
                'method_id' => 1,
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString()],
            ['booking_id' => 4,
                'currency_code' => 'usd',
                'paid_amount' => 0,
                'method_id' => 1,
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString()],

            //Salon
            //Hair
            ['booking_id' => 5,
            'currency_code' => 'usd',
            'paid_amount' => 0,
            'method_id' => 1,
            'created_at' => Carbon::now()->toDateTimeString(),
            'updated_at' => Carbon::now()->toDateTimeString()],

            ['booking_id' => 6,
                'currency_code' => 'usd',
                'paid_amount' => 0,
                'method_id' => 1,
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString()],

            ['booking_id' => 7,
                'currency_code' => 'usd',
                'paid_amount' => 0,
                'method_id' => 1,
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString()],

            //

            ['booking_id' => 8,
                'currency_code' => 'usd',
                'paid_amount' => 0,
                'method_id' => 1,
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString()],

            ['booking_id' => 9,
                'currency_code' => 'usd',
                'paid_amount' => 0,
                'method_id' => 1,
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString()],

            ['booking_id' => 10,
                'currency_code' => 'usd',
                'paid_amount' => 0,
                'method_id' => 1,
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString()],

            //Pending payment
            ['booking_id' => 11,
                'currency_code' => 'usd',
                'paid_amount' => 0,
                'method_id' => 1,
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString()],

        ]);
    }
}
