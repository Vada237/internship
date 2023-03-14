<?php

namespace App\Http\Controllers\Api;

use App\Actions\User\UserDeleteByIdAction;
use App\Actions\User\UserGetAllAction;
use App\Actions\User\UserGetByIdAction;
use App\Actions\User\UserUpdateNameAndAvatarAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\LimitOffsetRequest;
use App\Http\Requests\User\UserUpdateRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class UserController extends Controller
{
    public function index(UserGetAllAction $action, LimitOffsetRequest $request)
    {
        $this->authorize('viewAny', User::class);
        return UserResource::collection($action->handle($request->validated()));
    }

    public function show(UserGetByIdAction $action, User $user)
    {
        return new UserResource($action->handle($user));
    }

    public function update(UserUpdateNameAndAvatarAction $action, UserUpdateRequest $request, User $user)
    {
        $this->authorize('update', [User::class, $user]);
        return new UserResource($action->handle($request->validated(), $user));
    }

    public function destroy(UserDeleteByIdAction $action, User $user)
    {
        $this->authorize('delete', [User::class, $user]);
        return $action->handle($user);
    }
}
