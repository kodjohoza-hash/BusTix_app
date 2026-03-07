<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model Trip - Représente un voyage planifié
 */
class Trip extends Model
{
    use HasFactory;

    protected $table = 'trips';

    protected $fillable = [
        'displacement_id',
        'living_date_time',
        'price',
        'travel_status',
    ];

    protected $casts = [
        'living_date_time' => 'datetime',
        'price'            => 'decimal:2',
    ];

    public function displacement()
    {
        return $this->belongsTo(Displacement::class);
    }

    public function ticketReservations()
    {
        return $this->hasMany(Ticket_reservation::class);
    }

    public function scopeAvailable($query)
    {
        return $query->where('travel_status', 'planifié')
                     ->where('living_date_time', '>', now());
    }
}
