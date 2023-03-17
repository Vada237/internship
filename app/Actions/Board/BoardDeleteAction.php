<?php

namespace App\Actions\Board;

use App\Models\Board;

class BoardDeleteAction
{
    public function handle(Board $board)
    {
        $board->delete();
        return __('messages.board.delete.success');
    }
}
