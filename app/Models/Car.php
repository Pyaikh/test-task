<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Car extends Model
{
    protected $fillable = ['model_id', 'driver_id', 'license_plate'];

    public function model(): BelongsTo
    {
        return $this->belongsTo(CarModel::class);
    }

    // comfortCategory теперь на уровне CarModel

    public function driver(): BelongsTo
    {
        return $this->belongsTo(Driver::class);
    }

    public function trips()
    {
        return $this->hasMany(Trip::class);
    }

    public function scopeAvailable($query, $start, $end)
    {
        return $query->whereDoesntHave('trips', function ($q) use ($start, $end) {
            $q->where(function ($q) use ($start, $end) {
                $q->where('start_time', '<', $end)
                    ->where('end_time', '>', $start);
            });
        });
    }
}
