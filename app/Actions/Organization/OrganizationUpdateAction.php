<?php

namespace App\Actions\Organization;

use App\Http\Requests\Organization\OrganizationRequest;
use App\Http\Resources\OrganizationResource;
use App\Models\Organization;
use App\Models\UserOrganization;
use Illuminate\Support\Facades\Auth;

class OrganizationUpdateAction {
    public function handle($credentials, int $organization_id) {

        $user_organization = UserOrganization::where('user_id', Auth::id())->where('organization_id', $organization_id)->firstOrFail();

        if ($user_organization != null) {
            $organization = Organization::find($organization_id);
            $organization->update($credentials);
            return $organization;
        } else return __("Организация не найдена");

    }
}
