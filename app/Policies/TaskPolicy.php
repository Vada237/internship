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
                Role::ADMIN, Role::ORGANIZATION_SUPERVISOR) ||
            $user->hasAnyProjectRole($board->project,
                Role::PROJECT_SUPERVISOR, Role::PROJECT_EXECUTOR, Role::PROJECT_PARTICIPANT));
    }

    public function create(User $user, Board $board)
    {
        return ($user->hasAnyOrganizationRole($board->project->organization,
                Role::ADMIN, Role::ORGANIZATION_SUPERVISOR) ||
            $user->hasAnyProjectRole($board->project, Role::PROJECT_SUPERVISOR) &&
            $board->status == Board::EDITED);
    }

    public function view(User $user, Task $task)
    {
        return ($user->hasAnyOrganizationRole($task->board->project->organization,
                Role::ADMIN, Role::ORGANIZATION_SUPERVISOR) ||
            $user->hasAnyProjectRole($task->board->project,
                Role::PROJECT_SUPERVISOR, Role::PROJECT_EXECUTOR, Role::PROJECT_PARTICIPANT));
    }

    public function delete(User $user, Task $task)
    {
        return ($user->hasAnyOrganizationRole($task->board->project->organization, Role::ADMIN, Role::ORGANIZATION_SUPERVISOR) ||
            $user->hasAnyProjectRole($task->board->project, Role::PROJECT_SUPERVISOR));
    }

    public function addUser(User $user, Task $task, User $invitedUser)
    {

        return (($user->hasAnyOrganizationRole(
            $task->board->project->organization, Role::ORGANIZATION_SUPERVISOR, Role::ADMIN)||
                $user->hasAnyProjectRole($task->board->project, Role::PROJECT_SUPERVISOR)) &&
            $invitedUser->projects()->find($task->board->project->id));
    }

    public function deleteUser(User $user, Task $task, User $deletedUser)
    {
        return (($user->hasAnyOrganizationRole($task->board->project->organization, Role::ORGANIZATION_SUPERVISOR, Role::ADMIN) ||
                $user->hasAnyProjectRole($task->board->project, Role::PROJECT_SUPERVISOR)) &&
            ($deletedUser->projects()->find($task->board->project->id) &&
                !($deletedUser->hasAnyProjectRole($task->board->project, Role::PROJECT_SUPERVISOR))));
    }
}
