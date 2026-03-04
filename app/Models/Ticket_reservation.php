<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket_reservation extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'customer_id',
        'payment_id',
        'trip_id',
        'user_id',
        'reservation',
        'status',
        'code',
    ];

    // Define relationships
    public function customer()
    {
        return $this->belongsTo(\App\Models\Customer::class);
    }
}
