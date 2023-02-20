<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = Role::where('slug', 'admin')->first();
        $user = Role::where('slug', 'user')->first();
        $organizationSupervisor = Role::where('slug', 'organization-supervisor')->first();

        $findUser = Permission::where('slug', 'find-users')->first();
        $editUser = Permission::where('slug', 'edit-user')->first();
        $deleteUser = Permission::where('slug', 'delete-user')->first();
        $findCompany = Permission::where('slug', 'find-company')->first();
        $createCompany = Permission::where('slug', 'create-company')->first();
        $editCompany = Permission::where('slug', 'edit-company')->first();
        $deleteCompany = Permission::where('slug', 'delete-company')->first();

        $admin->permissions()->saveMany(Permission::all());

        $user->permissions()->saveMany([
           $findUser,$editUser,$deleteUser,$findCompany,$createCompany
        ]);

        $organizationSupervisor->permissions()->saveMany([
            $findUser,$editUser,$deleteUser,$findCompany,$createCompany,$editCompany,$deleteCompany
        ]);
    }
}
