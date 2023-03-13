<?php

namespace App\Traits;

use App\Models\Organization;
use App\Models\Permission;
use App\Models\Project;
use App\Models\Role;

trait HasRoles
{
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'user_organization_roles', 'user_id', 'role_id',)
            ->withPivot('organization_id')
            ->withTimestamps();
    }

    public function projectRoles()
    {
        return $this->belongsToMany(Role::class, 'user_project_roles', 'user_id', 'role_id')
            ->withPivot('project_id')
            ->withTimestamps();
    }

    public function hasAnyOrganizationRole(Organization $organization, ...$roles)
    {
        foreach ($roles as $role) {
            if ($this->roles()
                ->where('organization_id', $organization->id)
                ->get()->contains('name', Role::byName($role)->first()->name)) {
                return true;
            }
        }
        return false;
    }

    public
    function hasRole(string $role)
    {
        if ($this->roles()->where('name', $role)->first()) {
            return true;
        }
        return false;
    }

    public
    function hasProjectRole(string $role)
    {
        if ($this->projectRoles()->where('name', $role)->first()) {
            return true;
        }
        return false;
    }

    public
    function hasAnyProjectRole(Project $project, ...$projectRoles)
    {

        foreach ($projectRoles as $role) {
            if ($this->projectRoles()
                ->where('project_id', $project->id)->get()
                ->contains('name', Role::byName($role)->first()->name)) {
                return true;
            }
        }
        return false;
    }

    public
    function getRole(string $role)
    {
        return Role::where('name', $role)->first();
    }

    public
    function giveRole(string $role, int $organization_id)
    {
        $role = $this->getRole($role);
        $this->roles()->attach($role->id, ['organization_id' => $organization_id]);
        return $this;
    }

    public
    function deleteRole(string $role, int $organization_id)
    {
        $role = $this->getRole($role);
        $this->roles()->detach($role->id, ['organization_id' => $organization_id]);
        return __('messages.roles.delete');
    }
}
