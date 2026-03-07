<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model Payment - Représente un paiement effectué
 */
class Payment extends Model
{
    use HasFactory;

    protected $table = 'payments';

    protected $fillable = [
        'reservation_id',
        'amount',
        'payment_mode',
        'transaction_reference',
        'payment_date',
    ];

    protected $casts = [
        'payment_date' => 'datetime',
        'amount'       => 'decimal:2',
    ];

    const MODE_CASH         = 'espèces';
    const MODE_MOBILE_MONEY = 'mobile_money';
    const MODE_CARD         = 'carte_bancaire';

    public function ticketReservation()
    {
        return $this->belongsTo(Ticket_reservation::class, 'reservation_id');
    }

    public function scopeByMode($query, $mode)
    {
        return $query->where('payment_mode', $mode);
    }

    public static function generateTransactionReference()
    {
        return 'PAY-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -8));
    }
}
