<?php

namespace App\Http\Controllers\Api;
use App\Actions\User\UserDeleteByIdAction;
use App\Actions\User\UserGetAllAction;
use App\Actions\User\UserGetByIdAction;
use App\Actions\User\UserUpdateNameAndAvatarAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\UserUpdateRequest;


class UserController extends Controller
{
    public function index(UserGetAllAction $action)
    {
        return $action->handle();
    }

    public function show(UserGetByIdAction $action, int $id)
    {
        return $action->handle($id);
    }

    public function update(UserUpdateNameAndAvatarAction $action, UserUpdateRequest $request) {
        return $action->handle($request->all());
    }
    public function destroy(UserDeleteByIdAction $action,int $id)
    {
        return $action->handle($id);
    }
}
