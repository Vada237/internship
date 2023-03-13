<?php

namespace App\Actions\SubtaskTemplate;

use App\Models\SubtaskTemplate;

class SubtaskTemplateDeleteAction
{
    public function handle(SubtaskTemplate $subtaskTemplate)
    {
        $subtaskTemplate->delete();
        return __('messages.subtask.templates.delete.success');
    }
}
