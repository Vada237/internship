<?php

namespace App\Actions\User;

use App\Http\Requests\User\UserUpdateRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class UserUpdateNameAndAvatarAction
{
    public function handle($credentials)
    {
        $user = User::find(Auth::id());
        $user->name = $credentials['name'];
        if ($user->avatar != null || $credentials['avatar'] == null) {
            Storage::delete($user->avatar);
            $user->avatar = null;
        }

        if (array_key_exists('avatar', $credentials)) {
            $user->avatar = $credentials['avatar']->store('public/avatars');
        }

        $user->save();

        return new UserResource($user);
    }
}
