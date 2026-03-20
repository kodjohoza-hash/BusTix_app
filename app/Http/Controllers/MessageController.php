<?php
namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // Page messagerie
    public function index()
    {
        $user = auth()->user();

        // Trouver l'interlocuteur
        if ($user->isSuperAdmin()) {
            // Super Admin voit tous les guichets
            $contacts = User::where('role', 'admin')
                            ->where('admin_type', 'guichet')
                            ->get();
        } else {
            // Guichet voit le Super Admin
            $contacts = User::where('role', 'admin')
                            ->where('admin_type', 'super_admin')
                            ->get();
        }

        // Marquer les messages reçus comme lus
        Message::where('receiver_id', $user->id)
               ->where('is_read', false)
               ->update(['is_read' => true]);

        $messages = collect();
        $activeContact = null;

        if ($contacts->isNotEmpty()) {
            $activeContact = $contacts->first();
            $messages = Message::where(function($q) use ($user, $activeContact) {
                $q->where('sender_id', $user->id)->where('receiver_id', $activeContact->id);
            })->orWhere(function($q) use ($user, $activeContact) {
                $q->where('sender_id', $activeContact->id)->where('receiver_id', $user->id);
            })->orderBy('created_at', 'asc')->get();
        }

        return view('messages.index', compact('contacts', 'messages', 'activeContact'));
    }

    // Envoyer un message
    public function store(Request $request)
    {
        $request->validate(['content' => 'required|string|max:1000', 'receiver_id' => 'required|exists:users,id']);

        Message::create([
            'sender_id'   => auth()->id(),
            'receiver_id' => $request->receiver_id,
            'content'     => $request->content,
        ]);

        return response()->json(['success' => true]);
    }

    // Récupérer les nouveaux messages (polling)
    public function fetch(Request $request)
    {
        $user = auth()->user();
        $contactId = $request->contact_id;
        $lastId = $request->last_id ?? 0;

        $messages = Message::where('id', '>', $lastId)
            ->where(function($q) use ($user, $contactId) {
                $q->where(function($q2) use ($user, $contactId) {
                    $q2->where('sender_id', $user->id)->where('receiver_id', $contactId);
                })->orWhere(function($q2) use ($user, $contactId) {
                    $q2->where('sender_id', $contactId)->where('receiver_id', $user->id);
                });
            })->orderBy('created_at', 'asc')->get()
            ->map(function($m) use ($user) {
                return [
                    'id'         => $m->id,
                    'content'    => $m->content,
                    'is_mine'    => $m->sender_id === $user->id,
                    'time'       => $m->created_at->format('H:i'),
                    'sender'     => $m->sender->name,
                ];
            });

        // Marquer comme lus
        Message::where('receiver_id', $user->id)
               ->where('sender_id', $contactId)
               ->where('is_read', false)
               ->update(['is_read' => true]);

        // Compter non lus total
        $unread = Message::where('receiver_id', $user->id)->where('is_read', false)->count();

        return response()->json(['messages' => $messages, 'unread' => $unread]);
    }

    // Compter les non lus (pour la cloche)
    public function unread()
    {
        $count = Message::where('receiver_id', auth()->id())
                        ->where('is_read', false)
                        ->count();
        return response()->json(['count' => $count]);
    }
}