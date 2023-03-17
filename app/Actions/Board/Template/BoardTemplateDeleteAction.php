<?php

namespace App\Actions\Board\Template;

use App\Models\BoardTemplate;

class BoardTemplateDeleteAction
{
    public function handle(BoardTemplate $boardTemplate)
    {
        $boardTemplate->delete();
        return __('messages.board.templates.delete.success');
    }
}
