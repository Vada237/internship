<?php

namespace App\Http\Controllers\Api;
use App\Actions\User\UserDeleteByIdAction;
use App\Actions\User\UserGetAllAction;
use App\Actions\User\UserGetByIdAction;
use App\Actions\User\UserUpdateNameAndAvatarAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\UserUpdateRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class UserController extends Controller
{
    public function index(UserGetAllAction $action, Request $request)
    {
        return UserResource::collection($action->handle($request->query('limit'),$request->query('offset')));
    }

    public function show(UserGetByIdAction $action,int $id)
    {
        return new UserResource($action->handle($id));
    }

    public function update(UserUpdateNameAndAvatarAction $action, UserUpdateRequest $request,int $id) {
        $this->authorize('update', User::find($id));
        return new UserResource($action->handle($request->validated(),$id));
    }
    public function destroy(UserDeleteByIdAction $action,int $id)
    {
        $this->authorize('update', User::find($id));
        return $action->handle($id);
    }
}
