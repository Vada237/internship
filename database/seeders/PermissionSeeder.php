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
            'name' => 'Find users',
            'slug' => 'find-users'
        ]);

        Permission::create([
            'name' => 'Edit user',
            'slug' => 'edit-user'
        ]);

        Permission::create([
            'name' => 'Delete user',
            'slug' => 'delete-user'
        ]);

        Permission::create([
            'name' => 'Find company',
            'slug' => 'find-company'
        ]);

        Permission::create([
            'name' => 'Create company',
            'slug' => 'create-company'
        ]);

        Permission::create([
            'name' => 'Edit company',
            'slug' => 'edit-company'
        ]);

        Permission::create([
            'name' => 'Delete company',
            'slug' => 'delete-company'
        ]);
    }
}
