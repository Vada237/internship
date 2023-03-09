<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskTemplate extends Model
{
    use HasFactory;

    const ATTRIBUTES = [
        'TITLE' => 'Title',
        'DESCRIPTION' => 'Description',
        'DEADLINE' => 'Deadline',
        'IMAGE' => 'Image'
    ];

    public function boardTemplate()
    {
        return $this->belongsTo(BoardTemplate::class);
    }

    public function taskAttributes()
    {
        return $this->belongsToMany(TaskAttribute::class, 'task_template_attributes',
            'task_template_id', 'task_attribute_id');
    }
}
