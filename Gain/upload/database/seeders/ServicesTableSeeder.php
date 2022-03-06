<?php
namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ServicesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('services')->insert([
            'title' => 'Sample Hourly Service 1',
            'business_type' => 'general',
            'price' => '5',
            'allow_cancel' => '0',
            'auto_confirm' => '0',
            'allow_booking_max_day_ago' => '60',
            'service_duration' => '01:30:00',
            'multiple_bookings' => '1',
            'available_seat' => '100',
            'description' => 'This service will provide you a number of time slots. You can choose your preferred one or two if it allows. Here presenting the service calendar. Please select your date for booking.',
            'service_duration_type' => 'hourly',
            'service_starts' => '10:00:00',
            'service_ends' => '14:00:00',
            'max_booking' => '01:00:00',
            'alias' => 'sample-hourly-service-1',
            'created_by' => '1',
            'activation' => '1',
            'created_at' => Carbon::now()->toDateTimeString(),
            'updated_at' => Carbon::now()->toDateTimeString(),
        ]);
        DB::table('services')->insert([
            'title' => 'Sample Hourly Service 2',
            'business_type' => 'general',
            'price' => '7.5',
            'allow_cancel' => '0',
            'auto_confirm' => '0',
            'allow_booking_max_day_ago' => '60',
            'service_duration' => '02:00:00',
            'multiple_bookings' => '0',
            'available_seat' => '100',
            'description' => 'This service will provide you a number of time slots. You can choose your preferred one or two if it allows. Here presenting the service calendar. Please select your date for booking.',
            'service_duration_type' => 'hourly',
            'service_starts' => '11:00:00',
            'service_ends' => '17:00:00',
            'max_booking' => '01:00:00',
            'alias' => 'sample-hourly-service-2',
            'created_by' => '1',
            'activation' => '1',
            'created_at' => Carbon::now()->toDateTimeString(),
            'updated_at' => Carbon::now()->toDateTimeString(),

        ]);

        DB::table('services')->insert([
            'title' => 'Sample Daily Service 1',
            'business_type' => 'general',
            'price' => '15',
            'allow_cancel' => '0',
            'auto_confirm' => '0',
            'allow_booking_max_day_ago' => '60',
            'service_duration' => '02:00:00',
            'multiple_bookings' => '0',
            'available_seat' => '500',
            'description' => 'This service is for a whole day. The daily service starts at 12:00PM (Our Local Time) and ends at 11:59AM of followed by date. Here presenting the service calendar. Please select your date for booking.',
            'service_duration_type' => 'daily',
            'service_starts' => '00:00:00',
            'service_ends' => '00:00:00',
            'max_booking' => '0',
            'alias' => 'sample-daily-service-2',
            'created_by' => '1',
            'activation' => '1',
            'created_at' => Carbon::now()->toDateTimeString(),
            'updated_at' => Carbon::now()->toDateTimeString(),

        ]);



        //Salon Seed
        DB::table('services')->insert([
            'title' => 'Hair Design',
            'business_type' => 'salon',
            'price' => '15.5',
            'allow_cancel' => '0',
            'auto_confirm' => '0',
            'allow_booking_max_day_ago' => '60',
            'service_duration' => '02:30:00',
            'multiple_bookings' => '0',
            'available_seat' => '5',
            'description' => 'Dream your hair design and we will make your dream come true. Hair design that brings the elegance, confidence in you, are offered in our service.',
            'service_duration_type' => 'hourly',
            'service_starts' => '11:00:00',
            'service_ends' => '17:00:00',
            'max_booking' => '01:00:00',
            'alias' => 'hair-design',
            'consider_children_for_price' => 'Yes',
            'age_range' => '5',
            'percentage' => '0.5',

            'created_by' => '1',
            'activation' => '1',
            'created_at' => Carbon::now()->toDateTimeString(),
            'updated_at' => Carbon::now()->toDateTimeString(),

        ]);

        DB::table('services')->insert([
            'title' => 'Spa',
            'business_type' => 'salon',
            'price' => '10.25',
            'allow_cancel' => '0',
            'auto_confirm' => '0',
            'allow_booking_max_day_ago' => '60',
            'service_duration' => '01:00:00',
            'multiple_bookings' => '0',
            'available_seat' => '8',
            'description' => 'Get yourself relaxed with our spa service. Great spa manicures and pedicures, massages, facials body treatments like exfoliation are available for you.',
            'service_duration_type' => 'hourly',
            'service_starts' => '11:00:00',
            'service_ends' => '17:00:00',
            'max_booking' => '01:00:00',
            'alias' => 'spa',
            'consider_children_for_price' => 'Yes',
            'age_range' => '5',
            'percentage' => '0.5',

            'created_by' => '1',
            'activation' => '1',
            'created_at' => Carbon::now()->toDateTimeString(),
            'updated_at' => Carbon::now()->toDateTimeString(),

        ]);

        DB::table('services')->insert([
            'title' => 'Haircut',
            'business_type' => 'salon',
            'price' => '2.5',
            'allow_cancel' => '1',
            'auto_confirm' => '0',
            'allow_booking_max_day_ago' => '60',
            'service_duration' => '00:45:00',
            'multiple_bookings' => '1',
            'available_seat' => '10',
            'description' => 'The appearance can change when get a great haircut. A great looking haircut brings a great personality. Great customer service is a must for us, a friendly environment serving the best of your interest!',
            'service_duration_type' => 'hourly',
            'service_starts' => '11:00:00',
            'service_ends' => '17:00:00',
            'max_booking' => '01:00:00',
            'alias' => 'haircut',
            'consider_children_for_price' => 'Yes',
            'age_range' => '5',
            'percentage' => '0.5',
            'created_by' => '1',
            'activation' => '1',
            'created_at' => Carbon::now()->toDateTimeString(),
            'updated_at' => Carbon::now()->toDateTimeString(),

        ]);

        //New
        DB::table('services')->insert([
            'title' => 'Nail Service',
            'business_type' => 'salon',
            'price' => '4.75',
            'allow_cancel' => '1',
            'auto_confirm' => '1',
            'allow_booking_max_day_ago' => '60',
            'service_duration' => '02:00:00',
            'multiple_bookings' => '0',
            'available_seat' => '5',
            'description' => 'Want to have you nails done! This service will provide you manicures, pedicures, and nail enhancements.',
            'service_duration_type' => 'hourly',
            'service_starts' => '11:00:00',
            'service_ends' => '17:00:00',
            'max_booking' => '01:00:00',
            'alias' => 'nail-service',
            'consider_children_for_price' => 'Yes',
            'age_range' => '5',
            'percentage' => '0.5',
            'created_by' => '1',
            'activation' => '1',
            'created_at' => Carbon::now()->toDateTimeString(),
            'updated_at' => Carbon::now()->toDateTimeString(),

        ]);

        DB::table('services')->insert([
            'title' => 'Touch ups',
            'business_type' => 'salon',
            'price' => '2.25',
            'allow_cancel' => '0',
            'auto_confirm' => '0',
            'allow_booking_max_day_ago' => '60',
            'service_duration' => '01:00:00',
            'multiple_bookings' => '0',
            'available_seat' => '15',
            'description' => 'Get yourself touch ups whenever you want. Here presenting touch ups for you. Please select your date for booking and get the service.',
            'service_duration_type' => 'hourly',
            'service_starts' => '11:00:00',
            'service_ends' => '17:00:00',
            'max_booking' => '01:00:00',
            'alias' => 'touch-ups',
            'consider_children_for_price' => 'Yes',
            'age_range' => '5',
            'percentage' => '0.5',
            'created_by' => '1',
            'activation' => '1',
            'created_at' => Carbon::now()->toDateTimeString(),
            'updated_at' => Carbon::now()->toDateTimeString(),

        ]);

        DB::table('services')->insert([
            'title' => 'Makeup',
            'business_type' => 'salon',
            'price' => '7.5',
            'allow_cancel' => '0',
            'auto_confirm' => '0',
            'allow_booking_max_day_ago' => '60',
            'service_duration' => '02:00:00',
            'multiple_bookings' => '0',
            'available_seat' => '10',
            'description' => 'Get yourself an attractive make by professionals. Here presenting the service calendar. Book a date and get a make up service.',
            'service_duration_type' => 'hourly',
            'service_starts' => '11:00:00',
            'service_ends' => '17:00:00',
            'max_booking' => '01:00:00',
            'alias' => 'makeup',
            'consider_children_for_price' => 'Yes',
            'age_range' => '5',
            'percentage' => '0.5',
            'created_by' => '1',
            'activation' => '1',
            'created_at' => Carbon::now()->toDateTimeString(),
            'updated_at' => Carbon::now()->toDateTimeString(),

        ]);
    }
}