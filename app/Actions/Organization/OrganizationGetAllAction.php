<?php

namespace App\Actions\Organization;

use App\Http\Resources\OrganizationResource;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class OrganizationGetAllAction {
    public function handle($params) {
        return Organization::limit($params['limit'])->offset($params['offset'])->get();
    }
}
