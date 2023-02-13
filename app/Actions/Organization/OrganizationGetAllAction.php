<?php

namespace App\Actions\Organization;

use App\Http\Resources\OrganizationResource;
use App\Models\Organization;

class OrganizationGetAllAction {
    public function handle() {
        return OrganizationResource::collection(Organization::paginate(5));
    }
}
