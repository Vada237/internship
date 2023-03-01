<?php

namespace App\Http\Controllers\Api;

use App\Actions\Project\ProjectCreateAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Project\ProjectRequest;
use App\Http\Resources\ProjectResource;
use App\Models\Organization;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{
    public function index()
    {
        //
    }

    public function store(ProjectCreateAction $action, ProjectRequest $request)
    {
        $this->authorize('create', [Project::class, Organization::find($request->only('organizationId'))->first()]);
        return new ProjectResource($action->handle($request->validated(),Auth::user()));
    }

    public function show(Project $project)
    {
        //
    }

    public function edit(Project $project)
    {
        //
    }

    public function update(Request $request, Project $project)
    {
        //
    }

    public function destroy(Project $project)
    {
        //
    }
}
