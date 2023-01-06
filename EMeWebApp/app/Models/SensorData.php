<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SensorData extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'value',
        'measured_at'
    ];

    public function sensorUnit(): BelongsTo
    {
        return $this->belongsTo(SensorUnit::class);
    }
}
