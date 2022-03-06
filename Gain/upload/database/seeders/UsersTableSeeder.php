<?php
namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     *
     * Insert demo data Users table
     */
    public function run()
    {
        // seed dev mode
        DB::table('users')->insert([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'admin@demo.com',
            'phone' => '123456789',
            'password' => Hash::make('123456'),
            'verified' => 1,
            'is_admin' => 1,
            'token' => '',
            'created_at' => Carbon::now()->toDateTimeString(),
            'updated_at' => Carbon::now()->toDateTimeString(),
        ]);

        DB::table('users')->insert([
            'first_name' => 'Jony',
            'last_name' => 'English',
            'email' => 'jony@demo.com',
            'phone' => '223456781',
            'password' => Hash::make('123456'),
            'verified' => 1,
            'is_admin' => 0,
            'token' => '',
            'created_at' => Carbon::now()->toDateTimeString(),
            'updated_at' => Carbon::now()->toDateTimeString(),
        ]);

        DB::table('users')->insert([
            'first_name' => 'Sergio',
            'last_name' => 'Ramos',
            'email' => 'ramos@demo.com',
            'phone' => '223456782',
            'password' => Hash::make('123456'),
            'verified' => 1,
            'is_admin' => 0,
            'token' => '',
            'role_id' => 2,
            'created_at' => Carbon::now()->toDateTimeString(),
            'updated_at' => Carbon::now()->toDateTimeString(),
        ]);

        DB::table('users')->insert([
            'first_name' => 'Ronaldo De Lima',
            'last_name' => 'Nazario',
            'email' => 'ronaldo@demo.com',
            'phone' => '223456783',
            'password' => Hash::make('123456'),
            'verified' => 1,
            'is_admin' => 0,
            'token' => '',
            'role_id' => 1,
            'created_at' => Carbon::now()->toDateTimeString(),
            'updated_at' => Carbon::now()->toDateTimeString(),
        ]);

        DB::table('users')->insert([
            'first_name' => 'Marco',
            'last_name' => 'Marquis',
            'email' => 'marco@demo.com',
            'phone' => '223456784',
            'password' => Hash::make('123456'),
            'verified' => 1,
            'is_admin' => 0,
            'token' => '',
            'created_at' => Carbon::now()->toDateTimeString(),
            'updated_at' => Carbon::now()->toDateTimeString(),
        ]);

        DB::table('users')->insert([
            'first_name' => 'Alex',
            'last_name' => 'Rins',
            'email' => 'alex@demo.com',
            'phone' => '223456785',
            'password' => Hash::make('123456'),
            'verified' => 1,
            'is_admin' => 0,
            'token' => '',
            'created_at' => Carbon::now()->toDateTimeString(),
            'updated_at' => Carbon::now()->toDateTimeString(),
        ]);

        DB::table('users')->insert([
            'first_name' => 'Valentino',
            'last_name' => 'Rossi',
            'email' => 'rossi@demo.com',
            'phone' => '223456786',
            'password' => Hash::make('123456'),
            'verified' => 1,
            'is_admin' => 0,
            'token' => '',
            'created_at' => Carbon::now()->toDateTimeString(),
            'updated_at' => Carbon::now()->toDateTimeString(),
        ]);

        DB::table('users')->insert([
            'first_name' => 'Joan',
            'last_name' => 'Toy',
            'email' => 'user1@demo.com',
            'phone' => '223456787',
            'password' => Hash::make('123456'),
            'verified' => 1,
            'is_admin' => 0,
            'token' => '',
            'created_at' => Carbon::now()->toDateTimeString(),
            'updated_at' => Carbon::now()->toDateTimeString(),
        ]);

        DB::table('users')->insert([
            'first_name' => 'Kade',
            'last_name' => 'Kiehn',
            'email' => 'user2@demo.com',
            'phone' => '323456789',
            'password' => Hash::make('123456'),
            'verified' => 1,
            'is_admin' => 0,
            'token' => '',
            'created_at' => Carbon::now()->toDateTimeString(),
            'updated_at' => Carbon::now()->toDateTimeString(),
        ]);

        DB::table('users')->insert([
            'first_name' => 'Francis',
            'last_name' => 'Casper',
            'email' => 'user3@demo.com',
            'phone' => '423456789',
            'password' => Hash::make('123456'),
            'verified' => 1,
            'is_admin' => 0,
            'token' => '',
            'created_at' => Carbon::now()->toDateTimeString(),
            'updated_at' => Carbon::now()->toDateTimeString(),
        ]);

        DB::table('users')->insert([
            'first_name' => 'Elvie',
            'last_name' => 'Hamill',
            'email' => 'client@demo.com',
            'phone' => '523456789',
            'password' => Hash::make('123456'),
            'verified' => 1,
            'is_admin' => 0,
            'token' => '',
            'created_at' => Carbon::now()->toDateTimeString(),
            'updated_at' => Carbon::now()->toDateTimeString(),
        ]);

        // install for customer
        /* $first_name = session('first_name');
         $last_name = session('last_name');
         $email = session('email');
         $password = session('password');

         DB::table('users')->insert([
             'first_name' => $first_name,
             'last_name' => $last_name,
             'email' => $email,
             'password' => Hash::make($password),
             'verified' => 1,
             'is_admin' => 1,
             'token' => ''
         ]);*/
    }
}
