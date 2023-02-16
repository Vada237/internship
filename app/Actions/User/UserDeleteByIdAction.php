<?php

namespace App\Actions\User;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UserDeleteByIdAction {
    public function handle(int $id) {

        $user = User::find($id);

        if ($user != null) {
            if ($user->avatar) {
                Storage::delete($user->avatar);
            }
            $user->delete();
            return $user;
        } else
        return response('Пользователь не найден', 204);
    }
}
