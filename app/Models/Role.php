<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    public function organizations() {
        return $this->belongsToMany(Organization::class, 'user_organizations_roles', 'role_id', 'organization_id')
            ->withTimestamps();
    }

    public function users() {
        return $this->belongsToMany(User::class, 'user_organizations_roles', 'role_id', 'user_id')
            ->withTimestamps();
    }
}
