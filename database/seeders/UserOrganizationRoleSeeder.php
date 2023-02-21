<?php

namespace Database\Seeders;

use App\Models\Organization;
use App\Models\Role;
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
        $users[0]->organizations()->attach(Organization::first()->id,[ 'role_id' => Role::where('slug', 'admin')->first()->id]);
        $users[1]->organizations()->attach(Organization::first()->id,[ 'role_id' => Role::where('slug', 'user')->first()->id]);
        $users[2]->organizations()->attach(Organization::first()->id,[ 'role_id' => Role::where('slug', 'user')->first()->id]);
        $users[2]->organizations()->attach(Organization::first()->id,[ 'role_id' => Role::where('slug', 'organization-supervisor')->first()->id]);
        $users[3]->organizations()->attach(Organization::skip(1)->first()->id,[ 'role_id' => Role::where('slug', 'organization-supervisor')->first()->id]);
    }
}
