<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Controller;
use App\Models\Trip;
use Illuminate\Http\Request;

/**
 * TripController - Consultation des voyages (Client)
 * 
 * Ce controller permet aux clients de rechercher
 * et consulter les voyages disponibles dans BusTix.
 * Un client peut rechercher par ville de départ,
 * destination et date de voyage.
 */
class TripController extends Controller
{
    /**
     * Affiche la liste de tous les voyages disponibles
     * GET /api/client/trips
     */
    public function index()
    {
        try {
            // Récupère uniquement les voyages disponibles
            // grâce au scope défini dans le model Trip
            $trips = Trip::available()
                         ->with(['displacement.bus'])
                         ->get();

            return response()->json([
                'success' => true,
                'message' => 'Liste des voyages disponibles',
                'data'    => $trips
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des voyages',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Recherche des voyages par critères
     * GET /api/client/trips/search
     */
    public function search(Request $request)
    {
        try {
            // Validation des critères de recherche
            $validated = $request->validate([
                'start_point'       => 'required|string',
                'destination_point' => 'required|string',
                'date'              => 'nullable|date|after_or_equal:today',
            ]);

            // Recherche des voyages selon les critères
            $trips = Trip::available()
                ->with(['displacement.bus'])
                ->whereHas('displacement', function ($query) use ($validated) {
                    // Filtre par ville de départ
                    $query->where('start_point', 'like', '%' . $validated['start_point'] . '%')
                          // Filtre par ville de destination
                          ->where('destination_point', 'like', '%' . $validated['destination_point'] . '%');
                })
                ->when(isset($validated['date']), function ($query) use ($validated) {
                    // Filtre par date si fournie
                    $query->whereDate('living_date_time', $validated['date']);
                })
                ->get();

            // Calcule les sièges disponibles pour chaque voyage
            $trips = $trips->map(function ($trip) {
                $totalSeats    = (int) $trip->displacement->bus->capacity;
                $reservedSeats = $trip->ticketReservations()
                                      ->where('status', '!=', 'annulée')
                                      ->count();
                $trip->available_seats = $totalSeats - $reservedSeats;
                return $trip;
            });

            return response()->json([
                'success'      => true,
                'message'      => 'Résultats de la recherche',
                'total_found'  => $trips->count(),
                'data'         => $trips
            ], 200);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur de validation',
                'errors'  => $e->errors()
            ], 422);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la recherche',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Affiche les détails d'un voyage avec sièges disponibles
     * GET /api/client/trips/{id}
     */
    public function show(string $id)
    {
        try {
            // Récupère le voyage avec toutes ses relations
            $trip = Trip::with(['displacement.bus', 'displacement.bus.seats'])
                        ->findOrFail($id);

            // Vérifie que le voyage est disponible
            if ($trip->travel_status !== 'planifié') {
                return response()->json([
                    'success' => false,
                    'message' => 'Ce voyage n\'est plus disponible'
                ], 409);
            }

            // Récupère les sièges déjà réservés pour ce voyage
            $reservedSeatIds = $trip->ticketReservations()
                                    ->where('status', '!=', 'annulée')
                                    ->pluck('seat_id')
                                    ->toArray();

            // Récupère tous les sièges du bus
            $allSeats = $trip->displacement->bus->seats->map(function ($seat) use ($reservedSeatIds) {
                // Marque chaque siège comme disponible ou réservé
                $seat->is_available = !in_array($seat->id, $reservedSeatIds);
                return $seat;
            });

            return response()->json([
                'success' => true,
                'message' => 'Détails du voyage récupérés avec succès',
                'data'    => [
                    'trip'            => $trip,
                    'seats'           => $allSeats,
                    'total_seats'     => $allSeats->count(),
                    'available_seats' => $allSeats->where('is_available', true)->count(),
                    'reserved_seats'  => count($reservedSeatIds),
                ]
            ], 200);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Voyage non trouvé'
            ], 404);
        }
    }
}
