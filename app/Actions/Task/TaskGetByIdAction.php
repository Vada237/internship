<?php

namespace App\Actions\Task;

use App\Models\Task;

class TaskGetByIdAction
{
    public function handle(Task $task)
    {
        return Task::with('subtasks.attributes')->get()->find($task);
    }
}
