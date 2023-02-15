<?php

namespace App\Actions\User;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserGetAllAction {
    public function handle(int $limit,int $offset) {
        return DB::table('users')
            ->offset($offset)
            ->limit($limit)
            ->get();
    }
}
