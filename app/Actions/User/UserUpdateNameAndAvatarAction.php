<?php

namespace App\Actions\User;

use App\Http\Requests\User\UserUpdateRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class UserUpdateNameAndAvatarAction
{
    public function handle($credentials, int $id)
    {
        $user = User::find($id);
        $user->name = $credentials['name'];

        if (array_key_exists('avatar', $credentials)) {

            if ($user->avatar != null) {
                Storage::delete($user->avatar);
                $user->avatar = null;
            }

            if ($credentials['avatar'] != null){
                $user->avatar = $credentials['avatar']->store('public/avatars');
            }
        }

        $user->save();
        return $user;
    }
}
