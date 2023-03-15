<?php

namespace App\Actions\Organization;

use App\Http\Requests\Organization\OrganizationRequest;
use App\Http\Resources\OrganizationResource;
use App\Models\Organization;
use App\Models\Role;
use App\Models\User;
use App\Models\UserOrganization;
use Illuminate\Support\Facades\Auth;

class OrganizationCreateAction
{
    public function handle($params, User $user)
    {
        $organization = Organization::create($params);

        $user->organizations()->attach($organization->id,
            ['role_id' => Role::byName(Role::list['ORGANIZATION_SUPERVISOR'])->first()->id]);

        return $organization;
    }
}
