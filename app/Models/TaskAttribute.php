<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskAttribute extends Model
{
    use HasFactory;

    public function taskTemplates()
    {
        return $this->belongsToMany(TaskTemplate::class, 'task_template_attributes',
            'task_attribute_id', 'task_template_id');
    }
}
