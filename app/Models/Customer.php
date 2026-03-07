<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model Customer - Représente un client de l'application
 */
class Customer extends Model
{
    use HasFactory;

    protected $table = 'customers';

    protected $fillable = [
        'user_id',
        'name',
        'surname',
        'telephone',
        'email',
        'id_card',
    ];

    protected $hidden = [
        'id_card',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function ticketReservations()
    {
        return $this->hasMany(Ticket_reservation::class);
    }

    public function payments()
    {
        return $this->hasManyThrough(
            Payment::class,
            Ticket_reservation::class,
            'customer_id',
            'reservation_id',
        );
    }
}
