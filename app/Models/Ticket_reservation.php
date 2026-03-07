<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model Ticket_reservation - Représente une réservation de billet
 */
class Ticket_reservation extends Model
{
    use HasFactory;

    protected $table = 'ticket_reservations';

    protected $fillable = [
        'customer_id',
        'trip_id',
        'seat_id',
        'reservation_date',
        'status',
        'ticket_code',
    ];

    protected $casts = [
        'reservation_date' => 'datetime',
    ];

    const STATUS_PENDING   = 'en_attente';
    const STATUS_CONFIRMED = 'confirmée';
    const STATUS_CANCELLED = 'annulée';

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function trip()
    {
        return $this->belongsTo(Trip::class);
    }

    public function seat()
    {
        return $this->belongsTo(Seat::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class, 'reservation_id');
    }

    public function scopeConfirmed($query)
    {
        return $query->where('status', self::STATUS_CONFIRMED);
    }

    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    public static function generateTicketCode()
    {
        return 'BT-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -5));
    }
}
