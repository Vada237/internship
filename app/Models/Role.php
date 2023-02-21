<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    public function organizations() {
        return $this->belongsToMany(Organization::class, 'user_organization_roles', 'role_id', 'organization_id')
            ->withTimestamps();
    }

    public function users() {
        return $this->belongsToMany(User::class, 'user_organization_roles', 'role_id', 'user_id')
            ->withTimestamps();
    }

    public function permissions() {
        return $this->belongsToMany(Permission::class, 'role_permissions', 'role_id', 'permission_id')
            ->withTimestamps();
    }
}
