<?php

namespace App\Actions\Board;

use App\Models\Board;

class BoardGetByIdAction {
    public function handle(Board $board) {
        return $board;
    }
}
