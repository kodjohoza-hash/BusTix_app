<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Displacement extends Model
{
    use HasFactory;
      protected $fillable = [
        'start_point',
        'destination_point',
        'price',
        'distance_km',
    ];

    //Define relationships
    public function trips()
    {
        return $this->hasMany(\App\Models\Trip::class);
    }
}
