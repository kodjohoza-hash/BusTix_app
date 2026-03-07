<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model Seat - Représente un siège dans un bus
 */
class Seat extends Model
{
    use HasFactory;

    protected $table = 'seats';

    protected $fillable = [
        'bus_id',
        'seat_number',
    ];

    public function bus()
    {
        return $this->belongsTo(Bus::class);
    }

    public function ticketReservation()
    {
        return $this->hasOne(Ticket_reservation::class);
    }
}
