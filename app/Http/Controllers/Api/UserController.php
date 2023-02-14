<?php

namespace App\Http\Controllers\Api;
use App\Actions\User\UserDeleteByIdAction;
use App\Actions\User\UserGetAllAction;
use App\Actions\User\UserGetByIdAction;
use App\Actions\User\UserUpdateNameAndAvatarAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\UserUpdateRequest;
use App\Http\Resources\UserResource;


class UserController extends Controller
{
    public function index(UserGetAllAction $action)
    {
        return UserResource::collection($action->handle());
    }

    public function show(UserGetByIdAction $action, int $id)
    {
        return new UserResource($action->handle($id));
    }

    public function update(UserUpdateNameAndAvatarAction $action, UserUpdateRequest $request) {
        return new UserResource($action->handle($request->all()));
    }
    public function destroy(UserDeleteByIdAction $action,int $id)
    {
        return new UserResource($action->handle($id));
    }
}
