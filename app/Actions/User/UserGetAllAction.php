<?php

namespace App\Actions\User;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserGetAllAction {
    public function handle($params) {
        return User::offset($params['offset'])->limit($params['limit'])->get();
    }
}
