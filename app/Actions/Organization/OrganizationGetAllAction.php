<?php

namespace App\Actions\Organization;

use App\Http\Resources\OrganizationResource;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class OrganizationGetAllAction {
    public function handle(int $limit,int $offset) {
        return DB::table('organizations')
            ->limit($limit)
            ->offset($offset)
            ->get();
    }
}
