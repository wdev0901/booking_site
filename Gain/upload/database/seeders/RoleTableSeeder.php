<?php
namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleTableSeeder extends Seeder
{
    public function run()
    {
        Role::query()->truncate();

        DB::table('roles')->insert([
            [
                'title' => 'Manager',
                'permissions' => 'a:5:{i:0;s:28:"can_edit_application_setting";i:1;s:22:"can_edit_email_setting";i:2;s:15:"can_add_service";i:3;s:15:"can_add_booking";i:4;s:18:"can_manage_booking";}',
                'created_by' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'title' => 'Service Assistant',
                'permissions' => 'a:2:{i:0;s:15:"can_add_service";i:1;s:15:"can_add_booking";}',
                'created_by' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);
    }
}