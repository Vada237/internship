<?php

namespace App\Actions\User;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;

class UserGetAllAction {
    public function handle() {
        return UserResource::collection(User::paginate(5));
    }
}
