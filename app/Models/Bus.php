<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model Bus - Représente un bus dans l'application
 * 
 * Un bus possède plusieurs sièges et peut effectuer
 * plusieurs déplacements et voyages.
 */
class Bus extends Model
{
    use HasFactory;

    protected $table = 'buses';

    protected $fillable = [
        'bus_number',
        'mack',
        'capacity',
        'bus_status',
    ];

    public function seats()
    {
        return $this->hasMany(Seat::class);
    }

    public function displacements()
    {
        return $this->hasMany(Displacement::class);
    }

    public function ticketReservations()
    {
        return $this->hasMany(Ticket_reservation::class);
    }
}
