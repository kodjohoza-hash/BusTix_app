<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Trip;
use App\Models\Displacement;

class ChatbotController extends Controller
{
    public function reply(Request $request)
{
    $message = strtolower(trim($request->input('message', '')));
    $lang    = $request->input('lang', 'en');
    $response = $this->getResponse($message, $lang);
    return response()->json(['reply' => $response]);
}

    private function getResponse(string $msg, string $lang = 'en'): string
{
    $fr = ($lang === 'fr');

    if (preg_match('/hello|hi|hey|bonjour|salut|bonsoir|good morning/', $msg)) {
        return $fr
            ? "Bonjour! 👋 Comment puis-je vous aider?\n\nJe peux vous aider avec:\n• 🚌 Voyages et horaires\n• 💰 Prix\n• 🎫 Réservations\n• 💳 Paiements"
            : "Hello! 👋 How can I help you today?\n\nI can assist with:\n• 🚌 Trips & schedules\n• 💰 Prices\n• 🎫 Reservations\n• 💳 Payments";
    }

    if (preg_match('/trip|schedule|voyage|horaire|departure|depart|available|dispo/', $msg)) {
    // Détecter si une ville est mentionnée
    $villes = ['yaounde', 'yaoundé', 'douala', 'bafoussam', 'bamenda', 'buea', 'limbe', 'kribi', 'bertoua', 'ebolowa', 'mbalmayo', 'garoua', 'ngaoundere', 'ngaoundéré'];
    $villeDetectee = null;
    foreach ($villes as $ville) {
        if (str_contains($msg, $ville)) {
            $villeDetectee = $ville;
            break;
        }
    }

    $query = Trip::with('displacement')
        ->where('travel_status', 'planifié')
        ->where('living_date_time', '>=', now())
        ->orderBy('living_date_time');

    // Filtrer par ville si détectée
    if ($villeDetectee) {
        $query->whereHas('displacement', function($q) use ($villeDetectee) {
            $q->whereRaw('LOWER(start_point) LIKE ?', ["%$villeDetectee%"])
              ->orWhereRaw('LOWER(destination_point) LIKE ?', ["%$villeDetectee%"]);
        });
    }

    $trips = $query->take(5)->get();

    if ($trips->isEmpty()) {
        return $fr
            ? ($villeDetectee
                ? "Aucun voyage disponible pour " . ucfirst($villeDetectee) . " pour le moment. 🚌"
                : "Aucun voyage disponible pour le moment. Revenez plus tard! 🚌")
            : ($villeDetectee
                ? "No trips available for " . ucfirst($villeDetectee) . " at the moment. 🚌"
                : "No upcoming trips available at the moment. Please check back later! 🚌");
    }

    $list = $fr
        ? ($villeDetectee ? "Voyages disponibles pour " . ucfirst($villeDetectee) . ": 🚌\n\n" : "Prochains voyages disponibles: 🚌\n\n")
        : ($villeDetectee ? "Available trips for " . ucfirst($villeDetectee) . ": 🚌\n\n" : "Next available trips: 🚌\n\n");

    foreach ($trips as $trip) {
        $list .= "• " . $trip->displacement->start_point . " → " . $trip->displacement->destination_point;
        $list .= "\n  📅 " . \Carbon\Carbon::parse($trip->living_date_time)->format('d/m/Y H:i');
        $list .= " | 💰 " . number_format($trip->price, 0, ',', ' ') . " FCFA\n\n";
    }
    return $list . ($fr ? "Visitez la page Displacements pour réserver! 🎫" : "Visit Displacements page to book! 🎫");
}

    if (preg_match('/price|prix|cost|how much|combien|tarif|fare|coût/', $msg)) {
        $displacements = Displacement::take(6)->get();
        if ($displacements->isEmpty()) {
            return $fr ? "Consultez la page Displacements pour les tarifs." : "Please visit Displacements page for prices.";
        }
        $list = $fr ? "Nos trajets et tarifs: 💰\n\n" : "Our routes and prices: 💰\n\n";
        foreach ($displacements as $d) {
            $list .= "• " . $d->start_point . " → " . $d->destination_point . ": " . number_format($d->prix, 0, ',', ' ') . " FCFA\n";
        }
        return $list;
    }

    if (preg_match('/reserv|book|ticket|billet|how to|comment|reserver|réserver/', $msg)) {
        return $fr
            ? "Comment réserver votre billet: 📋\n\n1️⃣ Allez sur Displacements\n2️⃣ Choisissez votre voyage\n3️⃣ Sélectionnez votre siège\n4️⃣ Connectez-vous ou créez un compte\n5️⃣ Choisissez le mode de paiement\n6️⃣ Recevez votre billet QR code! 🎫"
            : "How to book your ticket: 📋\n\n1️⃣ Go to Displacements page\n2️⃣ Choose your trip\n3️⃣ Select your seat\n4️⃣ Log in or create account\n5️⃣ Choose payment method\n6️⃣ Get your QR code ticket! 🎫";
    }

    if (preg_match('/pay|payment|paiement|mobile money|mtn|orange|card|cash|espece/', $msg)) {
        return $fr
            ? "Modes de paiement acceptés: 💳\n\n• 💵 Espèces — Payer au guichet\n• 📱 Mobile Money — MTN MoMo / Orange Money\n• 💳 Carte Bancaire — Visa / Mastercard\n\nTous les paiements sont sécurisés! 🔒"
            : "Payment methods accepted: 💳\n\n• 💵 Cash — Pay at the counter\n• 📱 Mobile Money — MTN MoMo / Orange Money\n• 💳 Bank Card — Visa / Mastercard\n\nAll payments are secure! 🔒";
    }

    if (preg_match('/city|cities|destination|where|yaounde|douala|bafoussam|bamenda|buea|limbe|kribi|ville/', $msg)) {
        $cities = Displacement::select('start_point')->distinct()->pluck('start_point')->toArray();
        $dests  = Displacement::select('destination_point')->distinct()->pluck('destination_point')->toArray();
        $all    = implode("\n• ", array_unique(array_merge($cities, $dests)));
        return $fr
            ? "Villes desservies: 🗺️\n\n• $all\n\nVoyez tous les trajets sur Displacements!"
            : "Cities we serve: 🗺️\n\n• $all\n\nSee all routes on Displacements page!";
    }

    if (preg_match('/cancel|annul/', $msg)) {
        return $fr
            ? "Pour annuler une réservation: 🔄\n\n1️⃣ Connectez-vous\n2️⃣ Allez dans Mes Réservations\n3️⃣ Cliquez Annuler sur les billets non payés\n\n⚠️ Billets payés: contactez notre guichet."
            : "To cancel a reservation: 🔄\n\n1️⃣ Log in to your account\n2️⃣ Go to My Reservations\n3️⃣ Click cancel on unpaid tickets\n\n⚠️ Paid tickets: contact our counter.";
    }

    if (preg_match('/thank|merci|thanks/', $msg)) {
        return $fr ? "De rien! 😊 Bon voyage avec BusTix! 🚌✨" : "You're welcome! 😊 Have a great trip with BusTix! 🚌✨";
    }

    if (preg_match('/contact|support|help|aide|assist/', $msg)) {
        return $fr
            ? "Besoin d'aide? 📞\n\n• Visitez notre guichet directement\n• Gérez tout depuis votre compte\n\nAutre chose que je peux faire?"
            : "Need help? 📞\n\n• Visit our counter directly\n• Manage everything in your account\n\nAnything else I can help with?";
    }

    return $fr
        ? "Je n'ai pas bien compris. 🤔\n\nJe peux vous aider avec:\n• 🚌 Voyages et horaires\n• 💰 Prix et trajets\n• 🎫 Comment réserver\n• 💳 Modes de paiement\n• 🗺️ Destinations disponibles"
        : "I didn't quite understand that. 🤔\n\nI can help you with:\n• 🚌 Trips & schedules\n• 💰 Prices & routes\n• 🎫 How to reserve\n• 💳 Payment methods\n• 🗺️ Available destinations";
}
}