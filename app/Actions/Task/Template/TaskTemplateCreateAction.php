<?php

namespace App\Actions\Task\Template;

use App\Models\TaskTemplate;

class TaskTemplateCreateAction
{
    public function handle($params)
    {
        return TaskTemplate::create($params);
    }
}
