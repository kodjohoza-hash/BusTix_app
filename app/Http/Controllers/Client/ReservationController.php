<?php
namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Ticket_reservation;
use App\Models\Trip;
use App\Models\Seat;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Liste des réservations du client
     */
    public function index()
    {
        $customer = auth()->user()->customer;

        if (!$customer) {
            return redirect()->route('home')
                             ->with('error', 'Profil client introuvable !');
        }

        $reservations = Ticket_reservation::with(['trip.displacement', 'seat', 'payment'])
                                         ->where('customer_id', $customer->id)
                                         ->latest()
                                         ->get();

        return view('pages.reservations', compact('reservations'));
    }

    /**
     * Crée une réservation
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'trip_id' => 'required|exists:trips,id',
            'seat_id' => 'required|exists:seats,id',
        ]);

        $customer = auth()->user()->customer;

        // Vérifie si le siège est déjà réservé
        $exists = Ticket_reservation::where('trip_id', $validated['trip_id'])
                                    ->where('seat_id', $validated['seat_id'])
                                    ->where('status', '!=', 'annulée')
                                    ->exists();

        if ($exists) {
            return redirect()->back()
                             ->with('error', 'Ce siège est déjà réservé !');
        }

        // Génération du code billet
        $ticketCode = 'BT-' . now()->format('Ymd') . '-' . strtoupper(substr(uniqid(), -5));

        // Création de la réservation
        $reservation = Ticket_reservation::create([
            'customer_id'      => $customer->id,
            'trip_id'          => $validated['trip_id'],
            'seat_id'          => $validated['seat_id'],
            'ticket_code'      => $ticketCode,
            'status'           => 'en_attente',
            'reservation_date' => now(),
        ]);

        return redirect()->route('client.payment.create', $reservation->id)
                 ->withHeaders([
                     'Cache-Control' => 'no-store, no-cache, must-revalidate',
                     'Pragma'        => 'no-cache',
                     'Expires'       => '0',
                 ])
                 ->with('success', 'Réservation créée ! Procédez au paiement.');
    }

    /**
     * Annule une réservation
     */
    public function destroy(string $id)
    {
        $reservation = Ticket_reservation::where('id', $id)
                                         ->where('customer_id', auth()->user()->customer->id)
                                         ->firstOrFail();

        if ($reservation->payment) {
            return redirect()->route('reservations')
                             ->with('error', 'Impossible d\'annuler une réservation déjà payée !');
        }

        $reservation->update(['status' => 'annulée']);

        return redirect()->route('reservations')
                         ->with('success', 'Réservation annulée avec succès !');
    }
}