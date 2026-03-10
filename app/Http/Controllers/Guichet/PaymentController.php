<?php

namespace App\Http\Controllers\Guichet;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Ticket_reservation;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index()
    {
        $payments = Payment::with(['ticketReservation.trip.displacement', 'ticketReservation.customer'])
                           ->latest()
                           ->paginate(15);

        return view('guichet.payments', compact('payments'));
    }

    public function create(string $reservationId)
    {
        $reservation = Ticket_reservation::with(['trip.displacement', 'seat', 'customer'])
                                         ->findOrFail($reservationId);

        if ($reservation->payment) {
            return redirect()->route('guichet.reservations')
                             ->with('error', 'Cette réservation est déjà payée !');
        }

        return view('guichet.payment_create', compact('reservation'));
    }

    public function store(Request $request, string $reservationId)
    {
        $reservation = Ticket_reservation::findOrFail($reservationId);

        $validated = $request->validate([
            'payment_mode' => 'required|in:espèces,mobile_money,carte_bancaire',
        ]);

        $reference = 'PAY-' . now()->format('Ymd') . '-' . strtoupper(substr(uniqid(), -8));

        Payment::create([
            'reservation_id'        => $reservation->id,
            'amount'                => $reservation->trip->price,
            'payment_mode'          => $validated['payment_mode'],
            'transaction_reference' => $reference,
            'payment_date'          => now(),
        ]);

        $reservation->update(['status' => 'confirmée']);

        return redirect()->route('guichet.reservations')
                         ->with('success', 'Paiement enregistré ! Billet : ' . $reservation->ticket_code);
    }
}