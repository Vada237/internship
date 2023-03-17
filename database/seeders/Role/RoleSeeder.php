<?php

namespace Database\Seeders\Role;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::create([
            'name' => 'Admin'
        ]);

        Role::create([
            'name' => 'User'
        ]);

        Role::create([
            'name' => 'OrganizationSupervisor'
        ]);

        Role::create([
            'name' => 'Employee'
        ]);

        Role::create([
            'name' => 'ProjectSupervisor'
        ]);

        Role::create([
            'name' => 'ProjectExecutor'
        ]);

        Role::create([
            'name' => 'ProjectParticipant'
        ]);
    }
}
