<?php

namespace App\Actions\Organization;

use App\Http\Requests\Organization\OrganizationRequest;
use App\Http\Resources\OrganizationResource;
use App\Models\Organization;
use App\Models\User;
use App\Models\UserOrganization;
use Illuminate\Support\Facades\Auth;

class OrganizationCreateAction {
    public function handle($credentials) {

        $organization = new Organization($credentials);
        $organization->save();


        $user_organization = new UserOrganization([
            'user_id' => Auth::id(),
            'organization_id' => $organization->id
        ]);

        $user_organization->save();

        return new OrganizationResource($organization);
    }
}
