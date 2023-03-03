<?php

namespace App\Http\Controllers\Api;

use App\Actions\Project\ProjectCreateAction;
use App\Actions\Project\ProjectDeleteAction;
use App\Actions\Project\ProjectGetAllAction;
use App\Actions\Project\ProjectGetByIdAction;
use App\Actions\Project\ProjectGetByOrganizationAction;
use App\Actions\Project\ProjectUpdateAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\LimitOffsetRequest;
use App\Http\Requests\Project\ProjectCreateRequest;
use App\Http\Requests\Project\ProjectGetRequest;
use App\Http\Requests\Project\ProjectUpdateRequest;
use App\Http\Resources\ProjectResource;
use App\Models\Organization;
use App\Models\Project;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{
    public function index(ProjectGetAllAction $action, LimitOffsetRequest $request)
    {
        $this->authorize('viewAny', Project::class);
        return ProjectResource::collection($action->handle($request->validated()));
    }

    public function getByOrganization(ProjectGetByOrganizationAction $action, Organization $organization)
    {
        $this->authorize('viewByOrganization', [Project::class, $organization]);
        return ProjectResource::collection($action->handle($organization));
    }

    public function store(ProjectCreateAction $action, ProjectCreateRequest $request)
    {
        $this->authorize('create', [Project::class, Organization::findOrFail($request->only('organizationId'))->first()]);
        return new ProjectResource($action->handle($request->validated(), Auth::user()));
    }

    public function show(ProjectGetByIdAction $action, Project $project)
    {
        $this->authorize('view', $project);
        return new ProjectResource($action->handle($project));
    }

    public function update(ProjectUpdateAction $action, ProjectUpdateRequest $request, Project $project)
    {
        $this->authorize('update', [Project::class, $project]);
        return new ProjectResource($action->handle($request->validated(), $project));
    }

    public function destroy(ProjectDeleteAction $action, Project $project)
    {
        $this->authorize('delete', [Project::class, $project]);
        return $action->handle($project);
    }
}
