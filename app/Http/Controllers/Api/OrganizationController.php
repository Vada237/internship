<?php

namespace App\Http\Controllers\Api;

use App\Actions\Organization\OrganizationCreateAction;
use App\Actions\Organization\OrganizationDeleteAction;
use App\Actions\Organization\OrganizationGetAllAction;
use App\Actions\Organization\OrganizationGetByIdAction;
use App\Actions\Organization\OrganizationGetByNameAction;
use App\Actions\Organization\OrganizationUpdateAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\LimitOffsetRequest;
use App\Http\Requests\Organization\OrganizationRequest;
use App\Http\Resources\OrganizationResource;
use App\Models\Organization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrganizationController extends Controller
{
    public function index(OrganizationGetAllAction $action, LimitOffsetRequest $request)
    {
        $this->authorize('viewAny',Organization::class);
        return OrganizationResource::collection($action->handle($request->validated()));
    }

    public function store(OrganizationCreateAction $action, OrganizationRequest $request)
    {
        return new OrganizationResource($action->handle($request->validated(), Auth::user()));
    }

    public function show(OrganizationGetByIdAction $action, Organization $organization)
    {
        $this->authorize('view', $organization);
        return new OrganizationResource($action->handle($organization));
    }

    public function update(OrganizationRequest $request, OrganizationUpdateAction $action, Organization $organization)
    {
        $this->authorize('update', $organization);
        return new OrganizationResource($action->handle($request->validated(), $organization));
    }

    public function destroy(OrganizationDeleteAction $action, Organization $organization)
    {
        $this->authorize('delete',$organization);
        return $action->handle($organization);
    }
}
