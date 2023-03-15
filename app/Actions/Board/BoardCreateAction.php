<?php

namespace App\Actions\Board;

use App\Models\Board;
use App\Models\BoardTemplate;

class BoardCreateAction
{
    public function handle($params)
    {
        $board = Board::create([
            'name' => BoardTemplate::find($params['board_template_id'])->name,
            'project_id' => $params['project_id'],
            'status' => Board::statuses['EDITED']
        ]);
        return $board;
    }
}
