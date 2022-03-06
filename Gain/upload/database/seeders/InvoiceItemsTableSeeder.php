<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class InvoiceItemsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('invoice_items')->insert([

           [ 'invoice_id' => '1',
            'service_title' => 'Sample Hourly Service 1',
            'booking_date' => date("Y-m-d"),
            'booking_time' => 'a:1:{i:0;s:8:"10:00:00";}',
            'unit_price' => 5,
            'quantity' => 1,
            'total' => 5,
            'created_at' => Carbon::now()->toDateTimeString(),
            'updated_at' => Carbon::now()->toDateTimeString()],

            [ 'invoice_id' => '2',
                'service_title' => 'Sample Hourly Service 2',
                'booking_date' => date("Y-m-d"),
                'booking_time' => 'a:1:{i:0;s:8:"11:00:00";}',
                'unit_price' => 7.5,
                'quantity' => 2,
                'total' => 15,
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString()],
            [ 'invoice_id' => '3',
                'service_title' => 'Sample Hourly Service 2',
                'booking_date' => date("Y-m-d"),
                'booking_time' => 'a:1:{i:0;s:8:"11:00:00";}',
                'unit_price' => 7.5,
                'quantity' => 1,
                'total' => 7.5,
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString()],
            [ 'invoice_id' => '4',
                'service_title' => 'Sample Hourly Service 1',
                'booking_date' => date("Y-m-d"),
                'booking_time' => 'a:1:{i:0;s:8:"10:00:00";}',
                'unit_price' => 5,
                'quantity' => 2,
                'total' => 10,
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString()],

            //Salon
            //Hair design
            [ 'invoice_id' => '5',
                'service_title' => 'Hair Design',
                'booking_date' => date("Y-m-d"),
                'booking_time' => 'a:1:{i:0;s:8:"11:00:00";}',
                'unit_price' => 15.5,
                'quantity' => 2,
                'total' => 31,
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString()],

            //Spa
            [ 'invoice_id' => '6',
                'service_title' => 'Spa',
                'booking_date' => date("Y-m-d"),
                'booking_time' => 'a:1:{i:0;s:8:"12:00:00";}',
                'unit_price' => 10.25,
                'quantity' => 2,
                'total' => 20.5,
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString()],

            //Hair Cut
            [ 'invoice_id' => '7',
                'service_title' => 'Haircut',
                'booking_date' => date("Y-m-d"),
                'booking_time' => 'a:1:{i:0;s:8:"12:30:00";}',
                'unit_price' => 2.5,
                'quantity' => 2,
                'total' => 5,
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString()],

            //Nail Service
            [ 'invoice_id' => '8',
                'service_title' => 'Nail Service',
                'booking_date' => date("Y-m-d",strtotime("+3 months")),
                'booking_time' => 'a:1:{i:0;s:8:"11:00:00";}',
                'unit_price' => 4.75,
                'quantity' => 2,
                'total' => 9.5,
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString()],

            //Touch ups
            [ 'invoice_id' => '9',
                'service_title' => 'Touch ups',
                'booking_date' => date("Y-m-d",strtotime("+6 months")),
                'booking_time' => 'a:1:{i:0;s:8:"12:00:00";}',
                'unit_price' => 2.25,
                'quantity' => 2,
                'total' => 4.5,
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString()],

            //Make up
            [ 'invoice_id' => '10',
                'service_title' => 'Makeup',
                'booking_date' => date("Y-m-d",strtotime("+9 months")),
                'booking_time' => 'a:1:{i:0;s:8:"11:00:00";}',
                'unit_price' => 7.5,
                'quantity' => 2,
                'total' => 15,
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString()],

            //Touch ups pending
            [ 'invoice_id' => '11',
                'service_title' => 'Touch ups',
                'booking_date' => date("Y-m-d"),
                'booking_time' => 'a:1:{i:0;s:8:"11:00:00";}',
                'unit_price' => 2.25,
                'quantity' => 2,
                'total' => 4.5,
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString()],
        ]);
    }
}
