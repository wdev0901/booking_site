<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            EmailTemplatesTableSeeder::class,
            SmsTemplatesTableSeeder::class,
            RoleTableSeeder::class,
            ProdSettingsTableSeeder::class,
            PaymentMethodsTableSeeder::class,
            ProdServicePolicyTableSeeder::class,
            ProdContactInformationTableSeeder::class,
            ProdSocialLinkTableSeeder::class,
        ]);
    }
}
