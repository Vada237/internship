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
use App\Models\Organization;
use Illuminate\Http\Request;

class OrganizationController extends Controller
{
    public function index(OrganizationGetAllAction $action)
    {
        return $action->handle();
    }

    public function store(OrganizationCreateAction $action, OrganizationRequest $request)
    {
        return $action->handle($request->all());
    }

    public function show(OrganizationGetByIdAction $action, int $id)
    {
        return $action->handle($id);
    }

    public function getByName(OrganizationGetByNameAction $action, string $name)
    {
        return $action->handle($name);
    }

    public function update(OrganizationRequest $request, OrganizationUpdateAction $action, int $organization_id)
    {
        return $action->handle($request->all(), $organization_id);
    }

    public function destroy(OrganizationDeleteAction $action, int $id)
    {
        return $action->handle($id);
    }
}
