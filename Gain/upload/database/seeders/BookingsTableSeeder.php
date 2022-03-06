<?php
namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BookingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('bookings')->insert([
            [
                'service_id' => '1',
                'user_id' => '1',
                'phone_number' => '123465789',
                'status' => 'confirmed',
                'booking_date' => date("Y-m-d"),
                'booking_time' => 'a:1:{i:0;s:8:"10:00:00";}',
                'quantity' => '1',
                'comment' => '',
                'booking_bill'=>5,
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString()],
            [
                'service_id' => '2',
                'user_id' => '2',
                'phone_number' => '89785474',
                'status' => 'confirmed',
                'booking_date' => date("Y-m-d"),
                'booking_time' => 'a:1:{i:0;s:8:"11:00:00";}',
                'quantity' => '2',
                'comment' => '',
                'booking_bill'=>15,
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString()],
            [
                'service_id' => '2',
                'user_id' => '3',
                'phone_number' => '',
                'status' => 'confirmed',
                'booking_date' => date("Y-m-d"),
                'booking_time' => 'a:1:{i:0;s:8:"11:00:00";}',
                'quantity' => '1',
                'comment' => '',
                'booking_bill'=>7.5,
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString()],
            [
                'service_id' => '1',
                'user_id' => '4',
                'phone_number' => '897854',
                'status' => 'confirmed',
                'booking_date' => date("Y-m-d"),
                'booking_time' => 'a:1:{i:0;s:8:"10:00:00";}',
                'quantity' => '2',
                'comment' => '',
                'booking_bill'=>10,
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString()],

            //Salon
            //Hair design
            [
                'service_id' => '4',
                'user_id' => '4',
                'phone_number' => '897854',
                'status' => 'confirmed',
                'booking_date' => date("Y-m-d"),
                'booking_time' => 'a:1:{i:0;s:8:"11:00:00";}',
                'quantity' => '2',
                'comment' => '',
                'booking_bill'=>31,
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString()],

            //Spa
            [
                'service_id' => '5',
                'user_id' => '4',
                'phone_number' => '897854',
                'status' => 'confirmed',
                'booking_date' => date("Y-m-d"),
                'booking_time' => 'a:1:{i:0;s:8:"12:00:00";}',
                'quantity' => '2',
                'comment' => '',
                'booking_bill'=>20.5,
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString()],

            //Hair Cut
            [
                'service_id' => '6',
                'user_id' => '4',
                'phone_number' => '897854',
                'status' => 'confirmed',
                'booking_date' => date("Y-m-d"),
                'booking_time' => 'a:1:{i:0;s:8:"12:30:00";}',
                'quantity' => '2',
                'comment' => '',
                'booking_bill'=>5,
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString()],

            //Nail Service
            [
                'service_id' => '7',
                'user_id' => '4',
                'phone_number' => '897854',
                'status' => 'confirmed',
                'booking_date' => date("Y-m-d",strtotime("+3 months")),
                'booking_time' => 'a:1:{i:0;s:8:"11:00:00";}',
                'quantity' => '2',
                'comment' => '',
                'booking_bill'=>9.5,
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString()],

            //Touch ups
            [
                'service_id' => '8',
                'user_id' => '4',
                'phone_number' => '897854',
                'status' => 'confirmed',
                'booking_date' => date("Y-m-d",strtotime("+6 months")),
                'booking_time' => 'a:1:{i:0;s:8:"12:00:00";}',
                'quantity' => '2',
                'comment' => '',
                'booking_bill'=>4.5,
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString()],

            //Make up
            [
                'service_id' => '9',
                'user_id' => '4',
                'phone_number' => '897854',
                'status' => 'confirmed',
                'booking_date' => date("Y-m-d",strtotime("+9 months")),
                'booking_time' => 'a:1:{i:0;s:8:"11:00:00";}',
                'quantity' => '2',
                'comment' => '',
                'booking_bill'=>15,
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString()],

            //Touch ups pending
            [
                'service_id' => '8',
                'user_id' => '4',
                'phone_number' => '897854',
                'status' => 'pending',
                'booking_date' => date("Y-m-d"),
                'booking_time' => 'a:1:{i:0;s:8:"11:00:00";}',
                'quantity' => '2',
                'comment' => '',
                'booking_bill'=>4.5,
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString()],


        ]);


    }
}
