<?php

namespace App\Actions\Task\User;

use App\Models\Task;
use App\Models\User;

class DeleteUserTaskAction
{
    public function handle(Task $task, User $user)
    {
        $task->users()->detach($user->id);
        return __('messages.task.users.delete.success');
    }
}
