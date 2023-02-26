<?php

namespace App\Actions\Organization;

use App\Http\Requests\Organization\OrganizationRequest;
use App\Http\Resources\OrganizationResource;
use App\Models\Organization;
use App\Models\User;
use App\Models\UserOrganization;
use Illuminate\Support\Facades\Auth;

class OrganizationUpdateAction {
    public function handle($params, int $organization_id,User $user) {
        $organization = $user->organizations()->findOrFail($organization_id);
        $organization->update($params);
        return $organization;
    }
}
