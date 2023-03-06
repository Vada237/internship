<?php

namespace App\Actions\User;

use App\Http\Requests\User\UserUpdateRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class UserUpdateNameAndAvatarAction
{
    public function handle($params, User $user)
    {
        $user->name = $params['name'];

        if (array_key_exists('avatar', $params)) {

            if (isset($user->avatar)) {
                Storage::delete($user->avatar);
                $user->avatar = null;
            }

            if (isset($params['avatar'])) {
                $user->avatar = $params['avatar']->store('public/avatars');
            }
        }

        $user->save();
        return $user;
    }
}
