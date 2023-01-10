<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SensorUnit extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'type',
        'description',
        'lat',
        'long',
        'auth_key',
        'threshold'
    ];


    public function sensorData(): HasMany
    {
        return $this->hasMany(SensorData::class);
    }
}
