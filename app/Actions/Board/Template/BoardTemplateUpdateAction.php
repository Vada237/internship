<?php

namespace App\Actions\Board\Template;

use App\Models\BoardTemplate;

class BoardTemplateUpdateAction
{
    public function handle(BoardTemplate $boardTemplate, $params)
    {
        $boardTemplate->update($params);
        return $boardTemplate;
    }
}
