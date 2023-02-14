<?php

namespace App\Actions\Organization;

use App\Http\Resources\OrganizationResource;
use App\Models\Organization;
use App\Models\User;

class OrganizationGetAllAction {
    public function handle() {
        return Organization::paginate(5);
    }
}
