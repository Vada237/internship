<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;


class UserController extends Controller
{
    public function index()
    {
        return UserResource::collection(User::all());
    }

    public function show($id)
    {
        $user = User::find($id);
        return new UserResource($user);

    }

    public function destroy($id)
    {
        $user = User::find($id);
        if ($user->avatar) {
            Storage::delete($user->avatar);
        }
        $user->delete();
        return response($user,200);
    }
}
