<?php

namespace App\Traits;

use App\Models\Organization;
use App\Models\Permission;
use App\Models\Role;

trait HasRoles
{
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'user_organization_roles', 'user_id', 'role_id')
            ->withTimestamps();
    }

    public function projectRoles()
    {
        return $this->belongsToMany(Role::class, 'user_project_roles', 'user_id', 'role_id')
            ->withTimestamps();
    }

    public function hasAnyRole(...$roles)
    {
        foreach ($roles as $role) {
            if ($this->roles->contains('name', $role)) {
                return true;
            }
        }
        return false;
    }

    public function hasRole(string $role)
    {
        if ($this->roles()->where('name', $role)->first()) {
            return true;
        }
        return false;
    }

    public function hasProjectRole(string $role)
    {
        if ($this->projectRoles()->where('name', $role)->first()) {
            return true;
        }
        return false;
    }

    public function hasAnyProjectRole(... $projectRoles)
    {
        foreach ($projectRoles as $role) {
            if ($this->projectRoles->contains('name', $role)) {
                return true;
            }
        }
        return false;
    }
    public function getRole(string $role)
    {
        return Role::where('name', $role)->first();
    }

    public function giveRole(string $role, int $organization_id)
    {
        $role = $this->getRole($role);
        $this->roles()->attach($role->id, ['organization_id' => $organization_id]);
        return $this;
    }

    public function deleteRole(string $role, int $organization_id)
    {
        $role = $this->getRole($role);
        $this->roles()->detach($role->id, ['organization_id' => $organization_id]);
        return __('messages.roles.delete');
    }
}
