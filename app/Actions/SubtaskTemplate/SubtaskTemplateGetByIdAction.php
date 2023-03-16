<?php

namespace App\Actions\SubtaskTemplate;

use App\Models\SubtaskTemplate;

class SubtaskTemplateGetByIdAction
{
    public function handle(SubtaskTemplate $subtaskTemplate)
    {
        return $subtaskTemplate;
    }
}
