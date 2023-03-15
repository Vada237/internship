<?php

namespace App\Actions\Board;

use App\Models\Board;

class BoardGetAllAction
{
    public function handle($params)
    {
        return Board::offset($params['offset'])->limit($params['limit'])->get();
    }
}
