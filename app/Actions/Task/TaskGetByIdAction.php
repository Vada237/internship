<?php

namespace App\Actions\Task;

use App\Models\Task;

class TaskGetByIdAction
{
    public function handle(Task $task)
    {
        return $task->with('subtasks.attributes')->first();
    }
}
