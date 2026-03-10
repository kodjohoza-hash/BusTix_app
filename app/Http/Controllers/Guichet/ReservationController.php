<?php

namespace App\Http\Controllers\Guichet;

use App\Http\Controllers\Controller;
use App\Models\Ticket_reservation;
use App\Models\Trip;
use App\Models\Seat;
use App\Models\Customer;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    public function index()
    {
        $reservations = Ticket_reservation::with(['trip.displacement', 'seat', 'customer', 'payment'])
                                         ->latest()
                                         ->paginate(15);

        return view('guichet.reservations', compact('reservations'));
    }

    public function create()
    {
        $trips     = Trip::with(['displacement.bus'])
                         ->where('travel_status', 'planifié')
                         ->where('living_date_time', '>', now())
                         ->orderBy('living_date_time')
                         ->get();
        $customers = Customer::all();

        return view('guichet.reservation_create', compact('trips', 'customers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'trip_id'     => 'required|exists:trips,id',
            'seat_id'     => 'required|exists:seats,id',
        ]);

        // Vérifie si le siège est déjà réservé
        $exists = Ticket_reservation::where('trip_id', $validated['trip_id'])
                                    ->where('seat_id', $validated['seat_id'])
                                    ->where('status', '!=', 'annulée')
                                    ->exists();

        if ($exists) {
            return redirect()->back()->with('error', 'Ce siège est déjà réservé !');
        }

        $ticketCode = 'BT-' . now()->format('Ymd') . '-' . strtoupper(substr(uniqid(), -5));

        $reservation = Ticket_reservation::create([
            'customer_id'      => $validated['customer_id'],
            'trip_id'          => $validated['trip_id'],
            'seat_id'          => $validated['seat_id'],
            'ticket_code'      => $ticketCode,
            'status'           => 'en_attente',
            'reservation_date' => now(),
        ]);

        return redirect()->route('guichet.payment.create', $reservation->id)
                         ->with('success', 'Réservation créée ! Procédez au paiement.');
    }

    public function updateStatus(Request $request, string $id)
    {
        $reservation = Ticket_reservation::findOrFail($id);
        $reservation->update(['status' => $request->status]);

        return redirect()->back()->with('success', 'Statut mis à jour !');
    }

    public function destroy(string $id)
    {
        Ticket_reservation::findOrFail($id)->delete();
        return redirect()->route('guichet.reservations')
                         ->with('success', 'Réservation supprimée !');
    }

    public function getSeats(string $tripId)
{
    $trip = Trip::with(['displacement.bus.seats', 'ticketReservations'])
                ->findOrFail($tripId);

    $reservedSeats = $trip->ticketReservations()
                          ->where('status', '!=', 'annulée')
                          ->pluck('seat_id')
                          ->toArray();

    $seats = $trip->displacement->bus->seats->map(function($seat) use ($reservedSeats) {
        return [
            'id'          => $seat->id,
            'seat_number' => $seat->seat_number,
            'reserved'    => in_array($seat->id, $reservedSeats),
        ];
    });

    return response()->json(['seats' => $seats]);
}
}