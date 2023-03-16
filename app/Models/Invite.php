<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Invite extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'invitable_id',
        'invitable_type',
        'token'
    ];

    const PROJECT = 'project';
    const ORGANIZATION = 'organization';

    public function invitable(): MorphTo
    {
        return $this->morphTo();
    }
}
