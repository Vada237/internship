<?php

namespace App\Actions\Organization;

use App\Http\Requests\Organization\OrganizationRequest;
use App\Http\Resources\OrganizationResource;
use App\Models\Organization;
use App\Models\Role;
use App\Models\User;
use App\Models\UserOrganization;
use Illuminate\Support\Facades\Auth;

class OrganizationCreateAction {
    public function handle($credentials,User $user) {
        $organization = new Organization($credentials);
        $organization->save();
        $user->organizations()->attach($organization->id,['role_id' => Role::where('slug', 'organization-supervisor')->first()->id]);
        return $organization;
    }
}
