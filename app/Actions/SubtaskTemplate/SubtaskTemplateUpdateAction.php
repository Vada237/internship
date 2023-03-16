<?php

namespace App\Actions\SubtaskTemplate;

use App\Models\SubtaskTemplate;

class SubtaskTemplateUpdateAction
{
    public function handle(SubtaskTemplate $subtaskTemplate, $params)
    {
        $subtaskTemplate->update($params);
        return $subtaskTemplate;
    }
}
