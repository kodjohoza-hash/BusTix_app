<?php
namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Ticket_reservation;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function create(string $reservationId)
    {
        $reservation = Ticket_reservation::with(['trip.displacement', 'seat'])
                                         ->where('id', $reservationId)
                                         ->where('customer_id', auth()->user()->customer->id)
                                         ->firstOrFail();

        if ($reservation->payment) {
            return redirect()->route('reservations')
                             ->with('info', 'Cette réservation est déjà payée !');
        }

        if ($reservation->status === 'annulée') {
            return redirect()->route('reservations')
                             ->with('error', 'Cette réservation a été annulée !');
        }

        return view('pages.paiement', compact('reservation'));
    }

    public function store(Request $request, string $reservationId)
    {
        $reservation = Ticket_reservation::where('id', $reservationId)
                                         ->where('customer_id', auth()->user()->customer->id)
                                         ->firstOrFail();

        // Vérifie si déjà payé - évite la double soumission
        if ($reservation->payment) {
            return redirect()->route('reservations')
                             ->with('info', 'Cette réservation est déjà payée !');
        }

        // Vérifie si déjà annulée
        if ($reservation->status === 'annulée') {
            return redirect()->route('reservations')
                             ->with('error', 'Cette réservation a été annulée !');
        }

        $validated = $request->validate([
            'payment_mode' => 'required|in:espèces,mobile_money,carte_bancaire',
        ]);

        // Génération référence transaction
        $reference = 'PAY-' . now()->format('Ymd') . '-' . strtoupper(substr(uniqid(), -8));

        // Création du paiement
        $payment = Payment::create([
            'reservation_id'        => $reservation->id,
            'amount'                => $reservation->trip->price,
            'payment_mode'          => $validated['payment_mode'],
            'transaction_reference' => $reference,
            'payment_date'          => now(),
        ]);

        // Confirmation automatique
        $reservation->update(['status' => 'confirmée']);

        return redirect()->route('client.billet', $payment->id)
                         ->with('success', 'Paiement confirmé ! Voici votre billet.');
    }
    public function history()
    {
        $customer = auth()->user()->customer;

        $payments = Payment::with([
            'ticketReservation.trip.displacement',
            'ticketReservation.seat'
        ])
        ->whereHas('ticketReservation', function($q) use ($customer) {
            $q->where('customer_id', $customer->id);
        })
        ->latest('payment_date')
        ->paginate(10);

        $totalDepense  = $payments->sum('amount');
        $totalPaiements = Payment::whereHas('ticketReservation', function($q) use ($customer) {
            $q->where('customer_id', $customer->id);
        })->count();

        return view('pages.mes-paiements', compact(
            'payments',
            'totalDepense',
            'totalPaiements'
        ));
    }
    public function billet(string $id)
{
    $payment = \App\Models\Payment::with([
        'ticketReservation.trip.displacement',
        'ticketReservation.seat',
        'ticketReservation.customer'
    ])->findOrFail($id);

    // Vérifier que c'est bien le client connecté
    $customer = auth()->user()->customer;
    if ($payment->ticketReservation->customer_id !== $customer->id) {
        abort(403);
    }

    return view('pages.billet', compact('payment'));
}
}