<?php

namespace App\Actions\Task;

use App\Http\Resources\twp;
use App\Models\Board;

class TaskGetByBoardIdAction
{
    public function handle(Board $board)
    {
        return $board->tasks()->with('subtasks.attributes')->get();
    }
}
