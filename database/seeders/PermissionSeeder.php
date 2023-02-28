<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Permission::create([
            'name' => 'FindUsers'
        ]);

        Permission::create([
            'name' => 'EditUser'
        ]);

        Permission::create([
            'name' => 'DeleteUser'
        ]);

        Permission::create([
            'name' => 'FindCompany'
        ]);

        Permission::create([
            'name' => 'CreateCompany'
        ]);

        Permission::create([
            'name' => 'EditCompany'
        ]);

        Permission::create([
            'name' => 'DeleteCompany'
        ]);
    }
}
