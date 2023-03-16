<?php

namespace App\Actions\Task\Template;

use App\Models\TaskTemplate;

class TaskTemplateUpdateAction
{
    public function handle($params, TaskTemplate $taskTemplate)
    {
        $taskTemplate->update($params);
        return $taskTemplate;
    }
}
