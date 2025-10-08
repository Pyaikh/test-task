<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ComfortCategory extends Model
{
    protected $fillable = ['name'];

    public function positions(): BelongsToMany
    {
        return $this->belongsToMany(Position::class);
    }
}
