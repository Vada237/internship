<?php

namespace App\Actions\SubtaskTemplate;

use App\Models\Attribute;
use App\Models\SubtaskTemplate;

class SubtaskTemplateUpdateAttributeAction
{
    public function handle(SubtaskTemplate $subtaskTemplate, $params)
    {
        $subtaskTemplate->attributes()->updateExistingPivot($params['attribute_id'], [
            'value' => $params['value']
        ]);

        return __('messages.subtask.attributes.edit.success');
    }
}
