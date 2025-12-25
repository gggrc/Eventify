<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    protected $fillable = ['task_list_id', 'title', 'description', 'position'];

    public function taskList() {
        return $this->belongsTo(TaskList::class);
    }
}