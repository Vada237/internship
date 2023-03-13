<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskTemplate extends Model
{
    use HasFactory;

    const ATTRIBUTES = [
        'DESCRIPTION' => 'Description',
        'DEADLINE' => 'Deadline',
        'IMAGE' => 'Image'
    ];

    protected $fillable = [
        'name',
        'board_template_id'
    ];

    public function boardTemplate()
    {
        return $this->belongsTo(BoardTemplate::class);
    }

    public function subtasks()
    {
        return $this->belongsToMany(SubtaskTemplate::class, 'subtasks');
    }
}
