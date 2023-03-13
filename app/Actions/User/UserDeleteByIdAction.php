<?php

namespace App\Actions\User;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UserDeleteByIdAction
{
    public function handle(User $user)
    {
        if (isset($user->avatar))
        {
            Storage::delete($user->avatar);
        }
        $user->delete();
        return __('messages.user.delete.success');
    }
}
