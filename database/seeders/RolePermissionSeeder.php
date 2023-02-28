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
        $admin = Role::where('name', 'Admin')->first();
        $user = Role::where('name', 'User')->first();
        $organizationSupervisor = Role::where('name', 'OrganizationSupervisor')->first();

        $findUser = Permission::where('name', 'FindUsers')->first();
        $editUser = Permission::where('name', 'EditUser')->first();
        $deleteUser = Permission::where('name', 'DeleteUser')->first();
        $findCompany = Permission::where('name', 'FindCompany')->first();
        $createCompany = Permission::where('name', 'CreateCompany')->first();
        $editCompany = Permission::where('name', 'EditCompany')->first();
        $deleteCompany = Permission::where('name', 'DeleteCompany')->first();

        $admin->permissions()->saveMany(Permission::all());

        $user->permissions()->saveMany([
           $findUser,$editUser,$deleteUser,$findCompany,$createCompany
        ]);

        $organizationSupervisor->permissions()->saveMany([
            $findUser,$editUser,$deleteUser,$findCompany,$createCompany,$editCompany,$deleteCompany
        ]);
    }
}
