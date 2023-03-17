<?php

namespace App\Actions\Task;

use App\Models\Subtask;
use App\Models\Task;
use App\Models\TaskTemplate;

class TaskCreateAction
{
    public function handle($params)
    {
        $taskTemplate = TaskTemplate::find($params['task_template_id']);

        $task = Task::create([
            'name' => $taskTemplate->name,
            'board_id' => $params['board_id'],
            'status' => Task::EDITED
        ]);

        foreach ($taskTemplate->subtaskTemplates()->get() as $subtaskTemplate) {
            $subtask = $task->subtasks()->create([
                'name' => $subtaskTemplate->name,
                'task_id' => $task->id
            ]);

            foreach ($subtaskTemplate->attributes()->get() as $subtaskAttributeTemplate) {
                $subtask->attributes()->attach($subtaskAttributeTemplate->id, [
                    'value' => $subtaskAttributeTemplate->pivot->value
                ]);
            }
        }
        return $task;
    }
}
