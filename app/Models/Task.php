<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = ['card_id', 'title', 'is_completed'];

    public function card() {
        return $this->belongsTo(Card::class);
    }
}