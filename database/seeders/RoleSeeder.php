<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB; 

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('roles')->insert([
            ['id' => 1, 'name' => 'super_admin', 'token' => 'SUPER_ADMIN_TOKEN'],
            ['id' => 2, 'name' => 'admin', 'token' => 'ADMIN_TOKEN'],
            ['id' => 3, 'name' => 'employee', 'token' => 'EMPLOYEE_TOKEN'],
        ]);
    }
}
