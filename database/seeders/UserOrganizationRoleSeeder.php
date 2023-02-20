<?php

namespace Database\Seeders;

use App\Models\Organization;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserOrganizationRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = User::all();
        $organization = Organization::find(1);
        $users[0]->organizations()->attach($organization->id,[ 'role_id' => 1]);
        $users[1]->organizations()->attach($organization->id,[ 'role_id' => 2]);
        $users[2]->organizations()->attach($organization->id,[ 'role_id' => 2]);
        $users[2]->organizations()->attach($organization->id,[ 'role_id' => 3]);
    }
}
