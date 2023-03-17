<?php

namespace App\Actions\SubtaskTemplate;

use App\Models\Attribute;
use App\Models\SubtaskTemplate;

class SubtaskTemplateDeleteAttributeAction
{
    public function handle(SubtaskTemplate $subtaskTemplate, Attribute $attribute)
    {
        $subtaskTemplate->attributes()->detach($attribute);
        return __('messages.subtask.attributes.delete.success');
    }
}
