<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Traits\HasRoles;
use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function setPasswordAttribute($password)
    {
        $this->attributes['password'] = bcrypt($password);
    }

    public function organizations()
    {
        return $this->belongsToMany(Organization::class, 'user_organization_roles', 'user_id', 'organization_id')
            ->withPivot('role_id')
            ->withTimestamps();
    }

    public function projects()
    {
        return $this->belongsToMany(Project::class, 'user_project_roles', 'user_id', 'project_id')
            ->withPivot('role_id')
            ->withTimestamps();
    }

    public function boardTemplates() {
        return $this->belongsToMany(BoardTemplate::class);
    }

    public function scopeByEmail($query, string $email)
    {
        return $query->where('email', $email);
    }
}
