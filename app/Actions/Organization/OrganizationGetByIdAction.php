<?php

namespace App\Actions\Organization;

use App\Http\Resources\OrganizationResource;
use App\Models\Organization;

class OrganizationGetByIdAction {
    public function handle(Organization $organization) {
        return $organization;
    }
}
