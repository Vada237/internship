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
        $admin = Role::byName(Role::list["ADMIN"])->first();
        $user = Role::byName(Role::list["USER"])->first();
        $organizationSupervisor = Role::byName(Role::list["ORGANIZATION_SUPERVISOR"])->first();
        $employee = Role::byName(Role::list["EMPLOYEE"])->first();
        $projectSupervisor = Role::byName(Role::list['PROJECT_SUPERVISOR'])->first();
        $projectExecutor = Role::byName(Role::list['PROJECT_EXECUTOR'])->first();
        $projectParticipant = Role::byName(Role::list['PROJECT_PARTICIPANT'])->first();

        $findUser = Permission::byName('FindUsers')->first();
        $editUser = Permission::byName('EditUser')->first();
        $deleteUser = Permission::byName('DeleteUser')->first();
        $findCompany = Permission::byName('FindCompany')->first();
        $createCompany = Permission::byName('CreateCompany')->first();
        $editCompany = Permission::byName('EditCompany')->first();
        $deleteCompany = Permission::byName('DeleteCompany')->first();
        $findProject = Permission::byName('FindProject')->first();
        $createProject = Permission::byName('CreateProject')->first();
        $editProject = Permission::byName('EditProject')->first();
        $deleteProject = Permission::byName('DeleteProject')->first();

        $admin->permissions()->saveMany(Permission::all());

        $user->permissions()->saveMany([
           $findUser,$editUser,$deleteUser,$findCompany,$createCompany
        ]);

        $organizationSupervisor->permissions()->saveMany([
            $findUser,$editUser,$deleteUser,$findCompany,$createCompany,$editCompany,$deleteCompany,
            $createProject,$editProject,$findProject,$deleteProject
        ]);

        $employee->permissions()->saveMany([
            $findUser,$editUser,$deleteUser,$findCompany,$createCompany,$createProject
        ]);

        $projectSupervisor->permissions()->saveMany([
            $findUser,$editUser,$deleteUser,$findCompany,$editProject,$deleteProject,$findProject
        ]);

        $projectExecutor->permissions()->saveMany([
            $findUser,$editUser,$deleteUser,$findCompany,$findProject
        ]);

        $projectParticipant->permissions()->saveMany([
            $findUser,$editUser,$deleteUser,$findCompany,$findProject
        ]);
    }
}
