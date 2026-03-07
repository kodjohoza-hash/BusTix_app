<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model Displacement - Représente un trajet fixe
 */
class Displacement extends Model
{
    use HasFactory;

    protected $table = 'displacements';

    protected $fillable = [
        'bus_id',
        'start_point',
        'destination_point',
        'prix',
        'distance_km',
    ];

    public function bus()
    {
        return $this->belongsTo(Bus::class);
    }

    public function trips()
    {
        return $this->hasMany(Trip::class);
    }

    public function ticketReservations()
    {
        return $this->hasMany(Ticket_reservation::class);
    }
}
