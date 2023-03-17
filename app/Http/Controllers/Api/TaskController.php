<?php

namespace App\Http\Controllers\Api;

use App\Actions\Task\TaskCreateAction;
use App\Actions\Task\TaskDeleteAction;
use App\Actions\Task\TaskGetByBoardIdAction;
use App\Actions\Task\TaskGetByIdAction;
use App\Actions\Task\TaskUpdateAction;
use App\Actions\Task\User\AddUserTaskAction;
use App\Actions\Task\User\DeleteUserTaskAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Task\TaskCreateRequest;
use App\Http\Requests\Task\User\UserTaskRequest;
use App\Http\Resources\TaskResource;
use App\Models\Board;
use App\Models\Task;
use App\Models\User;

class TaskController extends Controller
{
    public function findByBoard(TaskGetByBoardIdAction $action, Board $board)
    {
        $this->authorize('viewByBoard', [Task::class, $board]);
        return TaskResource::collection($action->handle($board));
    }

    public function store(TaskCreateAction $action, TaskCreateRequest $request)
    {
        $this->authorize('create', [Task::class, Board::find($request->validated('board_id'))]);
        return new TaskResource($action->handle($request->validated()));
    }

    public function show(TaskGetByIdAction $action, Task $task)
    {
        $this->authorize('view', [Task::class, $task]);
        return new TaskResource($action->handle($task));
    }

    public function destroy(TaskDeleteAction $action, Task $task)
    {
        $this->authorize('delete', [Task::class, $task]);
        return $action->handle($task);
    }

    public function addUser(AddUserTaskAction $action, UserTaskRequest $request)
    {
        $this->authorize('addUser', [Task::class,
            Task::find($request->validated('task_id')), User::find($request->validated('user_id'))]);
        return $action->handle($request->validated());
    }

    public function deleteUser(DeleteUserTaskAction $action, Task $task, User $user)
    {
        $this->authorize('addUser', [Task::class, $task, $user]);
        return $action->handle($task, $user);
    }
}
