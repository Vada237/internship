<?php

namespace App\Actions\User;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;

class UserGetByIdAction {
    public function handle(User $user) {
        return $user;
    }
}
