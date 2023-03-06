<?php

namespace App\Actions\User;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UserDeleteByIdAction
{
    public function handle(int $id)
    {
        $user = User::find($id);

        if ($user != null) {
            if ($user->avatar != null) {
                Storage::delete($user->avatar);
            }
            $user->delete();
            return __('messages.user.delete.success');
        } else
            return __('messages.user.notfound');
    }
}
