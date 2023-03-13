<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
       $this->call([
           RoleSeeder::class,
           PermissionSeeder::class,
           RolePermissionSeeder::class,
           UserSeeder::class,
           OrganizationSeeder::class,
           UserOrganizationRoleSeeder::class,
           ProjectSeeder::class,
           UserProjectRoleSeeder::class
       ]);
    }
}
