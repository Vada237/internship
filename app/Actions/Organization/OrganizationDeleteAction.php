<?php

namespace App\Actions\Organization;

use App\Http\Resources\OrganizationResource;
use App\Models\Organization;
use App\Models\User;
use App\Models\UserOrganization;
use Illuminate\Support\Facades\Auth;

class OrganizationDeleteAction
{
    public function handle(Organization $organization){
        $organization->delete();
        return __('messages.organizations.delete.success');
    }
}
