<?php


namespace Database\Seeders;
use Illuminate\Database\Seeder;

class DBDemoSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            EmailTemplatesTableSeeder::class,
            SmsTemplatesTableSeeder::class,
            RoleTableSeeder::class,
            UsersTableSeeder::class,
            ServicesTableSeeder::class,
            BookingsTableSeeder::class,
            SettingsTableSeeder::class,
            PaymentMethodsTableSeeder::class,
            PaymentTableSeeder::class,
            InvoiceTableSeeder::class,
            InvoiceItemsTableSeeder::class,
            ServicePolicyTableSeeder::class,
            SocialLinkTableSeeder::class,
            ContactInformationTableSeeder::class,
            ServiceImagesTableSeeder::class,
        ]);
    }
}