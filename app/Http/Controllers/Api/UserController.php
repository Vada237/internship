<?php

namespace App\Http\Controllers\Api;
use App\Actions\User\UserDeleteByIdAction;
use App\Actions\User\UserGetAllAction;
use App\Actions\User\UserGetByIdAction;
use App\Http\Controllers\Controller;


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

    public function destroy(UserDeleteByIdAction $action,int $id)
    {
        return $action->handle($id);
    }
}
