<?php
namespace Database\Seeders;

use App\Models\Payment;
use App\Models\PaymentMethod;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaymentMethodsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        PaymentMethod::query()->truncate();

        DB::table('payment_methods')->insert([
            'title' => 'Stripe',
            'type' => 'stripe',
            'available_to_client' => '0',
            'enable' => '0',
            'created_at' => Carbon::now()->toDateTimeString(),
            'updated_at' => Carbon::now()->toDateTimeString(),
        ]);

        DB::table('payment_methods')->insert([
            'title' => 'PayPal',
            'type' => 'paypal',
            'available_to_client' => '0',
            'enable' => '0',
            'created_at' => Carbon::now()->toDateTimeString(),
            'updated_at' => Carbon::now()->toDateTimeString(),
        ]);

        DB::table('payment_methods')->insert([
            'title' => 'Cash',
            'type' => 'custom',
            'available_to_client' => '0',
            'enable' => '1',
            'created_at' => Carbon::now()->toDateTimeString(),
            'updated_at' => Carbon::now()->toDateTimeString(),
        ]);
    }
}
