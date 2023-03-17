<?php

namespace App\Actions\Task\User;

use App\Models\Task;

class AddUserTaskAction
{
    public function handle($params)
    {
        $task = Task::find($params['task_id']);
        $task->users()->attach($params['user_id']);
        return __('messages.task.users.add.success');
    }
}
