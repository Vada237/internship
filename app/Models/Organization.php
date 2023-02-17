<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    use HasFactory;

    protected $fillable = [
        'name'
    ];

    public function users() {
        return $this->belongsToMany(User::class, 'user_organizations_roles', 'organization_id', 'user_id')
            ->withTimestamps();
    }

    public function roles() {
        return $this->belongsToMany(Role::class, 'user_organizations_roles', 'organization_id', 'role_id')
            ->withTimestamps();
    }
}
