<?php

namespace App\Actions\Organization;

use App\Http\Resources\OrganizationResource;
use App\Models\Organization;
use App\Models\UserOrganization;
use Illuminate\Support\Facades\Auth;

class OrganizationDeleteAction {
    public function handle(int $organization_id,int $userId) {

        $userOrganization = UserOrganization::where('user_id', $userId)->where('organization_id', $organization_id)->firstOrFail();

        if ($userOrganization != null) {
            $organization = Organization::find($organization_id);
            $organization->delete();
            return $organization;
        }
    }
}
