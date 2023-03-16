<?php

namespace App\Actions\Task\Template;

use App\Models\TaskTemplate;

class TaskTemplateGetByIdAction
{
    public function handle(TaskTemplate $taskTemplate)
    {
        return $taskTemplate;
    }
}
