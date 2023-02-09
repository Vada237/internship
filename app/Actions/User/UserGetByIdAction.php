<?php

namespace App\Actions\User;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;

class UserGetByIdAction {
    public function handle(int $id) {
        $user = User::find($id);
        return new UserResource($user);
    }
}
