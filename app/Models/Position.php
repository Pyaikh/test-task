<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Position extends Model
{
    protected $fillable = ['name'];

    public function comfortCategories(): BelongsToMany
    {
        return $this->belongsToMany(ComfortCategory::class,'position_comfort_category');
    }

    public function user()
    {
        return $this->hasMany(User::class);
    }
}
