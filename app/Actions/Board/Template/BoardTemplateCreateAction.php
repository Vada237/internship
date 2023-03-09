<?php

namespace App\Actions\Board\Template;

use App\Models\BoardTemplate;

class BoardTemplateCreateAction
{
    public function handle($params)
    {
        $boardTemplate = BoardTemplate::create($params);

        return $boardTemplate;
    }
}
