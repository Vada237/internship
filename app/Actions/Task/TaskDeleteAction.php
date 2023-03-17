<?php

namespace App\Actions\Task;

use App\Models\Task;

class TaskDeleteAction
{
    public function handle(Task $task)
    {
        return $task->delete();
    }
}
