<?php

namespace app\Http\Controllers\Api;

use App\Actions\Task\Template\TaskTemplateCreateAction;
use App\Actions\Task\Template\TaskTemplateDeleteAction;
use App\Actions\Task\Template\TaskTemplateGetAllAction;
use App\Actions\Task\Template\TaskTemplateGetByIdAction;
use App\Actions\Task\Template\TaskTemplateUpdateAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\LimitOffsetRequest;
use App\Http\Requests\Task\Template\TaskTemplateCreateRequest;
use App\Http\Requests\Task\Template\TaskTemplateUpdateRequest;
use App\Http\Resources\TaskTemplateResource;
use App\Models\BoardTemplate;
use App\Models\TaskTemplate;

class TaskTemplateController extends Controller
{
    public function index(TaskTemplateGetAllAction $action, LimitOffsetRequest $request)
    {
        return TaskTemplateResource::collection($action->handle($request->validated()));
    }

    public function store(TaskTemplateCreateAction $action, TaskTemplateCreateRequest $request)
    {
        return new TaskTemplateResource($action->handle($request->validated()));
    }

    public function show(TaskTemplateGetByIdAction $action, TaskTemplate $taskTemplate)
    {
        return new TaskTemplateResource($action->handle($taskTemplate));
    }

    public function update(TaskTemplateUpdateAction $action, TaskTemplateUpdateRequest $request, TaskTemplate $taskTemplate)
    {
        $this->authorize('update', [BoardTemplate::class, BoardTemplate::find($taskTemplate->board_template_id)]);
        return new TaskTemplateResource($action->handle($request->validated(), $taskTemplate));
    }

    public function destroy(TaskTemplateDeleteAction $action, TaskTemplate $taskTemplate)
    {
        $this->authorize('update', [BoardTemplate::class, BoardTemplate::find($taskTemplate->board_template_id)]);
        return $action->handle($taskTemplate);
    }
}
