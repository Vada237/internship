<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    const list = [
        'ADMIN' => 'Admin',
        'USER' => 'User',
        'ORGANIZATION_SUPERVISOR' => 'OrganizationSupervisor',
        'EMPLOYEE' => 'Employee',
        'PROJECT_SUPERVISOR' => 'ProjectSupervisor',
        'PROJECT_EXECUTOR' => 'ProjectExecutor',
        'PROJECT_PARTICIPANT' => 'ProjectParticipant'
    ];

    public function organizations() {
        return $this->belongsToMany(Organization::class, 'user_organization_roles', 'role_id', 'organization_id')
            ->withTimestamps();
    }

    public function users() {
        return $this->belongsToMany(User::class, 'user_organization_roles', 'role_id', 'user_id')
            ->withTimestamps();
    }

    public function projects() {
        return $this->belongsToMany(Project::class, 'user_project_roles', 'role_id', 'project_id');
    }

    public function permissions() {
        return $this->belongsToMany(Permission::class, 'role_permissions', 'role_id', 'permission_id')
            ->withTimestamps();
    }

    public function scopeByName($query, $name) {
        return $query->where('name', $name);
    }
}
