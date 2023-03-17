<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'board_id'
    ];

    public function subtasks()
    {
        return $this->hasMany(Subtask::class);
    }

    public function board()
    {
        return $this->belongsTo(Board::class);
    }

    public function users()
    {
        return $this
            ->belongsToMany(User::class, 'user_tasks', 'task_id', 'user_id')
            ->withTimestamps();
    }
}
