<?php

namespace Database\Seeders\Permission;

use App\Models\Permission;
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

        Permission::create([
            'name' => 'CreateProject'
        ]);

        Permission::create([
            'name' => 'EditProject'
        ]);

        Permission::create([
            'name' => 'FindProject'
        ]);

        Permission::create([
            'name' => 'DeleteProject'
        ]);
    }
}
