<?php

namespace App\Actions\Board\Template;

use App\Models\BoardTemplate;
use App\Models\User;

class BoardTemplateCreateAction
{
    public function handle($params, int $userId)
    {
        $boardTemplate = BoardTemplate::create([
            'name' => $params['name'],
            'user_id' => $userId
        ]);

        return $boardTemplate;
    }
}
