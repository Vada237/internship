<?php

namespace App\Actions\Task\Template;

use App\Models\TaskTemplate;

class TaskTemplateGetAllAction
{
    public function handle($params)
    {
        return TaskTemplate::offset($params['offset'])->limit($params['limit'])->get();
    }
}
