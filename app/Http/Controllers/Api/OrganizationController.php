<?php

namespace App\Http\Controllers\Api;

use App\Actions\Organization\OrganizationCreateAction;
use App\Actions\Organization\OrganizationDeleteAction;
use App\Actions\Organization\OrganizationGetAllAction;
use App\Actions\Organization\OrganizationGetByIdAction;
use App\Actions\Organization\OrganizationGetByNameAction;
use App\Actions\Organization\OrganizationUpdateAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Organization\OrganizationRequest;
use App\Http\Resources\OrganizationResource;
use App\Models\Organization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrganizationController extends Controller
{
    public function index(OrganizationGetAllAction $action, Request $request)
    {
        return OrganizationResource::collection($action->handle($request->query('limit'),$request->query('offset')));
    }

    public function store(OrganizationCreateAction $action, OrganizationRequest $request)
    {
        return new OrganizationResource($action->handle($request->validated(),Auth::user()));
    }

    public function show(OrganizationGetByIdAction $action,int $id)
    {
        return new OrganizationResource($action->handle($id));
    }

    public function getByName(OrganizationGetByNameAction $action,string $name)
    {
        return new OrganizationResource($action->handle($name));
    }

    public function update(OrganizationRequest $request, OrganizationUpdateAction $action,int $organization_id)
    {
        return new OrganizationResource($action->handle($request->validated(), $organization_id,Auth::user()));
    }

    public function destroy(OrganizationDeleteAction $action,int $id)
    {
        return $action->handle($id, Auth::user());
    }
}
