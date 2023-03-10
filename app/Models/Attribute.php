<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attribute extends Model
{
    use HasFactory;

    public function subtasks()
    {
        return $this->belongsToMany(SubtaskTemplate::class, 'subtask_attributes',
            'task_attribute_id', 'subtask_id');
    }
}
