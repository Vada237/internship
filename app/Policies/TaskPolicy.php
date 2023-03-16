<?php

namespace App\Policies;

use App\Models\Board;
use App\Models\Role;
use App\Models\Task;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TaskPolicy
{
    use HandlesAuthorization;

    public function viewByBoard(User $user, Board $board)
    {
        return ($user->hasAnyOrganizationRole($board->project->organization,
                Role::list['ADMIN'], Role::list['ORGANIZATION_SUPERVISOR']) ||
            $user->hasAnyProjectRole($board->project,
                Role::list['PROJECT_SUPERVISOR'], Role::list['PROJECT_EXECUTOR'], Role::list['PROJECT_PARTICIPANT']));
    }

    public function create(User $user, Board $board)
    {
        return ($user->hasAnyOrganizationRole($board->project->organization,
                Role::list['ADMIN'], Role::list['ORGANIZATION_SUPERVISOR']) ||
            $user->hasAnyProjectRole($board->project, Role::list['PROJECT_SUPERVISOR']) &&
            $board->status == Board::statuses['EDITED']);
    }

    public function view(User $user, Task $task)
    {
       return ($user->hasAnyOrganizationRole($task->board->project->organization,
                Role::list['ADMIN'], Role::list['ORGANIZATION_SUPERVISOR']) ||
            $user->hasAnyProjectRole($task->board->project,
                Role::list['PROJECT_SUPERVISOR'], Role::list['PROJECT_EXECUTOR'], Role::list['PROJECT_PARTICIPANT']));
    }

    public function update(User $user, Task $task)
    {
        //
    }

    public function delete(User $user, Task $task)
    {
        //
    }

    public function restore(User $user, Task $task)
    {
        //
    }
}
