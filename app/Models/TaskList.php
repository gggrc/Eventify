<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TaskList extends Model
{
    /**
     * Atribut yang dapat diisi selama mass assignment.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
    ];

    public function cards(): HasMany
    {
        return $this->hasMany(Card::class)->orderBy('position');
    }
}