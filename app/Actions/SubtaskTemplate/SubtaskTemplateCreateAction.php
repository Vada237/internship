<?php

namespace App\Actions\SubtaskTemplate;

use App\Models\SubtaskTemplate;

class SubtaskTemplateCreateAction
{
    public function handle($params)
    {
        return SubtaskTemplate::create($params);
    }
}
