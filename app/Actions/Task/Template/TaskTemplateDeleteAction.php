<?php

namespace App\Actions\Task\Template;

use App\Models\TaskTemplate;

class TaskTemplateDeleteAction
{
    public function handle(TaskTemplate $taskTemplate)
    {
        $taskTemplate->delete();
        return __('messages.task.templates.delete.success');
    }
}

