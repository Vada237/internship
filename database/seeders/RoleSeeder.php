<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
            'name' => 'Admin',
            'slug' => 'admin'
        ]);

        Role::create([
            'name' => 'User',
            'slug' => 'user'
        ]);

        Role::create([
            'name' => 'Organization supervisor',
            'slug' => 'organization-supervisor'
        ]);

        Role::create([
            'name' => 'Employee',
            'slug' => 'employee'
        ]);
    }
}
