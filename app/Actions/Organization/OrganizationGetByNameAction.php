<?php

namespace App\Actions\Organization;

use App\Http\Resources\OrganizationResource;
use App\Models\Organization;

class OrganizationGetByNameAction {
    public function handle(string $name) {
        return Organization::where('name', $name)->firstOrFail();
    }
}
