<?php

namespace App\Actions\Board;

use App\Models\Board;
use App\Models\BoardTemplate;

class BoardUpdateAction
{
    public function handle($params, Board $board)
    {
        $boardTemplate = BoardTemplate::find($params['board_template_id']);

        $board->name = $boardTemplate->name;
        $board->save();

        return $board;
    }
}
