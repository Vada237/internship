<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subtask extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'task_id'
    ];

    public function attributes()
    {
        return $this->belongsToMany(
            Attribute::class,'subtask_attributes', 'subtask_id', 'attribute_id')
            ->withPivot('value');
    }
}
