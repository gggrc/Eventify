<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    protected $fillable = ['task_list_id', 'title', 'description', 'priority', 'position']; 

    public function tasks() {
        return $this->hasMany(Task::class);
    }

    public function taskList() {
        return $this->belongsTo(TaskList::class);
    }
}