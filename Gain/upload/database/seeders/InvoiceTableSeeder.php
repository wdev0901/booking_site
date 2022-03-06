<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class InvoiceTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('invoices')->insert([

           [ 'user_id' => '1',
                'booking_id' => '1',
                'total' => 5,
                'due' => 5,
                'created_by'=>'',
                'comment'=>'',
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString()],
            [ 'user_id' => '2',
                'booking_id' => '2',
                'total' => 15,
                'due' => 15,
                'created_by'=>'',
                'comment'=>'',
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString()],
            [ 'user_id' => '3',
                'booking_id' => '3',
                'total' => 7.5,
                'due' => 7.5,
                'created_by'=>'',
                'comment'=>'',
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString()],
            [ 'user_id' => '4',
                'booking_id' => '4',
                'total' => 10,
                'due' => 10,
                'created_by'=>'',
                'comment'=>'',
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString()],

            //salon
            //Hair design
            [ 'user_id' => '4',
                'booking_id' => '5',
                'total' => 31,
                'due' => 31,
                'created_by'=>'',
                'comment'=>'',
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString()],

            //Spa
            [ 'user_id' => '4',
                'booking_id' => '6',
                'total' => 20.5,
                'due' => 20.5,
                'created_by'=>'',
                'comment'=>'',
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString()],

            //Hair Cut
            [ 'user_id' => '4',
                'booking_id' => '7',
                'total' => 5,
                'due' => 5,
                'created_by'=>'',
                'comment'=>'',
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString()],

            //Nail Service
            [ 'user_id' => '4',
                'booking_id' => '8',
                'total' => 9.5,
                'due' => 9.5,
                'created_by'=>'',
                'comment'=>'',
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString()],

            //Touch ups
            [ 'user_id' => '4',
                'booking_id' => '9',
                'total' => 4.5,
                'due' => 4.5,
                'created_by'=>'',
                'comment'=>'',
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString()],

            //Make up
            [ 'user_id' => '4',
                'booking_id' => '10',
                'total' => 15,
                'due' => 15,
                'created_by'=>'',
                'comment'=>'',
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString()],

            //Touch ups pending
            [ 'user_id' => '4',
                'booking_id' => '11',
                'total' => 4.5,
                'due' => 4.5,
                'created_by'=>'',
                'comment'=>'',
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString()]
        ]);
    }
}
