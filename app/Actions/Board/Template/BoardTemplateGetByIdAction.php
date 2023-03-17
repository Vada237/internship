<?php

namespace App\Actions\Board\Template;

use App\Models\BoardTemplate;

class BoardTemplateGetByIdAction
{
    public function handle(BoardTemplate $boardTemplate)
    {
        return $boardTemplate;
    }
}
