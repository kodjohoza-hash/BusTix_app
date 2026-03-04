<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SeatController extends Controller
{
    use HasFactory;
     protected $fillable = [
        'bus_id',
        'customer_id',
        'ticket_reservation_id',
        'seat_number',
    ];

    // Define relationships
    public function bus()
    {
        return $this->belongsTo(\App\Models\Bus::class);
    }

}