<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'organization_id'
        ];

    public function users() {
        return $this->belongsToMany(User::class, 'users', 'user_id', 'project_id');
    }

    public function roles() {
        return $this->belongsToMany(Role::class, 'roles', 'role_id', 'project_id');
    }
}
