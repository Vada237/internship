<?php

namespace App\Http\Controllers\Api;

use App\Actions\SubtaskTemplate\SubtaskTemplateAddAttributeAction;
use App\Actions\SubtaskTemplate\SubtaskTemplateCreateAction;
use App\Actions\SubtaskTemplate\SubtaskTemplateDeleteAction;
use App\Actions\SubtaskTemplate\SubtaskTemplateDeleteAttributeAction;
use App\Actions\SubtaskTemplate\SubtaskTemplateGetByIdAction;
use App\Actions\SubtaskTemplate\SubtaskTemplateUpdateAction;
use App\Actions\SubtaskTemplate\SubtaskTemplateUpdateAttributeAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\SubtaskTemplate\SubtaskAttributeRequest;
use App\Http\Requests\SubtaskTemplate\SubtaskTemplateRequest;
use App\Http\Requests\SubtaskTemplate\SubtaskTemplateUpdateRequest;
use App\Http\Resources\SubtaskTemplateResource;
use App\Models\Attribute;
use App\Models\BoardTemplate;
use App\Models\SubtaskTemplate;
use App\Models\Task;
use App\Models\TaskTemplate;
use Illuminate\Http\Request;

class SubtaskTemplateController extends Controller
{
    public function store(SubtaskTemplateCreateAction $action, SubtaskTemplateRequest $request)
    {
        $this->authorize('create', [BoardTemplate::class,
            TaskTemplate::find($request->task_template_id)->boardTemplate()->first()]);

        return new SubtaskTemplateResource($action->handle($request->validated()));
    }

    public function show(SubtaskTemplateGetByIdAction $action, SubtaskTemplate $subtaskTemplate)
    {
        return new SubtaskTemplateResource($action->handle($subtaskTemplate));
    }

    public function update(SubtaskTemplateUpdateAction $action,
                           SubtaskTemplateUpdateRequest $request, SubtaskTemplate $subtaskTemplate)
    {
        $this->authorize('create', [BoardTemplate::class,
            $subtaskTemplate->taskTemplate->boardTemplate()->first()]);

        return new SubtaskTemplateResource($action->handle($subtaskTemplate, $request->validated()));
    }

    public function destroy(SubtaskTemplateDeleteAction $action, SubtaskTemplate $subtaskTemplate)
    {
        $this->authorize('create', [BoardTemplate::class,
            $subtaskTemplate->taskTemplate->boardTemplate()->first()]);
        return $action->handle($subtaskTemplate);
    }

    public function addAttribute(SubtaskTemplateAddAttributeAction $action,
                                 SubtaskAttributeRequest $request, SubtaskTemplate $subtaskTemplate)
    {
        $this->authorize('create', [BoardTemplate::class,
            $subtaskTemplate->taskTemplate->boardTemplate()->first()]);
        return $action->handle($request->validated(), $subtaskTemplate);
    }

    public function updateAttribute(SubtaskTemplateUpdateAttributeAction $action,
                                    SubtaskTemplate $subtaskTemplate, SubtaskAttributeRequest $request)
    {
        $this->authorize('update', [BoardTemplate::class,
            $subtaskTemplate->taskTemplate->boardTemplate()->first()]);
        return $action->handle($subtaskTemplate, $request->validated());
    }

    public function deleteAttribute(SubtaskTemplateDeleteAttributeAction $action,
                                    SubtaskTemplate $subtaskTemplate, Attribute $attribute)
    {
        $this->authorize('delete', [BoardTemplate::class,
            $subtaskTemplate->taskTemplate->boardTemplate()->first()]);
        return $action->handle($subtaskTemplate, $attribute);
    }
}
