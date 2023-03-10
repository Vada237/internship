<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubtaskTemplate extends Model
{
    use HasFactory;

    public function attributes()
    {
        return $this->belongsToMany(Attribute::class, 'subtask_template_attributes',
            'subtask_template_id', 'attribute_id');
    }
}
