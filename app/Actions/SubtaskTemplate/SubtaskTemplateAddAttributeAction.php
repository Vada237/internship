<?php

namespace App\Actions\SubtaskTemplate;

use App\Models\SubtaskTemplate;

class SubtaskTemplateAddAttributeAction
{
    public function handle($params, SubtaskTemplate $subtaskTemplate)
    {
        $subtaskTemplate->attributes()->attach($params['attribute_id'], ['value' => $params['value']]);
        return __('messages.subtask.attributes.add.success');
    }
}
