<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserProjectRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = User::offset(1)->limit(3)->get();
        $projects = Project::limit(3)->get();

        $users[0]->projects()->attach($projects[0]->id, ['role_id' => Role::byName(Role::list['PROJECT_SUPERVISOR'])->first()->id]);
        $users[1]->projects()->attach($projects[0]->id, ['role_id' => Role::byName(Role::list['PROJECT_EXECUTOR'])->first()->id]);
        $users[1]->projects()->attach($projects[1]->id, ['role_id' => Role::byName(Role::list['PROJECT_SUPERVISOR'])->first()->id]);
        $users[2]->projects()->attach($projects[0]->id, ['role_id' => Role::byName(Role::list['PROJECT_PARTICIPANT'])->first()->id]);
    }
}
