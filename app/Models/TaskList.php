<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TaskList extends Model
{
    protected $fillable = [
        'title',
        'board_id', 
        'position',
    ];

    public function cards(): HasMany
    {
        return $this->hasMany(Card::class)->orderBy('position');
    }
}