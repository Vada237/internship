<?php

namespace App\Actions\Organization;

use App\Http\Resources\OrganizationResource;
use App\Models\Organization;
use App\Models\User;
use App\Models\UserOrganization;
use Illuminate\Support\Facades\Auth;

class OrganizationDeleteAction
{
    public function handle(int $organization_id, User $user)
    {
        $organization = $user->organizations()->find($organization_id);

        if ($organization != null) {
            $organization->delete();
            return __('messages.organizations.delete.success');
        } else return __('messages.organizations.notfound');
    }
}
