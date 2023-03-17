<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubtaskTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'task_template_id'
    ];

    public function taskTemplate()
    {
        return $this->belongsTo(TaskTemplate::class);
    }

    public function attributes()
    {
        return $this->belongsToMany(Attribute::class, 'subtask_template_attributes',
            'subtask_template_id', 'attribute_id')
            ->withPivot('value');
    }
}
