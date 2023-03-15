<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Board extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'project_id',
        'status'
    ];

    const statuses = [
        'EDITED' => 'edited',
        'ACTIVE' => 'active',
        'COMPLETED' => 'completed',
        'CLOSED' => 'closed'
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
