@extends(auth()->user()->isSuperAdmin() ? 'layouts.admin' : 'layouts.guichet')

@section('title', 'Messagerie')

@push('styles')
<style>
    .chat-container {
        height: 70vh;
        display: flex;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 10px 40px rgba(0,0,0,0.1);
    }

    .contacts-panel {
        width: 280px;
        min-width: 280px;
        background: white;
        border-right: 1px solid #e9ecef;
        display: flex;
        flex-direction: column;
    }

    .contacts-header {
        padding: 20px;
        background: linear-gradient(135deg, #1a237e, #0d47a1);
        color: white;
    }

    .contact-item {
        padding: 15px 20px;
        cursor: pointer;
        border-bottom: 1px solid #f5f5f5;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .contact-item:hover, .contact-item.active {
        background: #e8f0fe;
    }

    .contact-avatar {
        width: 45px; height: 45px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 1.1rem;
        color: white;
        flex-shrink: 0;
    }

    .chat-panel {
        flex: 1;
        display: flex;
        flex-direction: column;
        background: #f8faff;
    }

    .chat-header {
        padding: 15px 20px;
        background: white;
        border-bottom: 1px solid #e9ecef;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .messages-area {
        flex: 1;
        overflow-y: auto;
        padding: 20px;
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .message-bubble {
        max-width: 65%;
        padding: 10px 15px;
        border-radius: 18px;
        font-size: 0.9rem;
        line-height: 1.5;
        position: relative;
    }

    .message-mine {
        background: linear-gradient(135deg, #1a237e, #0d47a1);
        color: white;
        align-self: flex-end;
        border-bottom-right-radius: 4px;
    }

    .message-other {
        background: white;
        color: #333;
        align-self: flex-start;
        border-bottom-left-radius: 4px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    }

    .message-time {
        font-size: 0.7rem;
        opacity: 0.7;
        margin-top: 4px;
        display: block;
    }

    .chat-input-area {
        padding: 15px 20px;
        background: white;
        border-top: 1px solid #e9ecef;
        display: flex;
        gap: 10px;
        align-items: flex-end;
    }

    .chat-input {
        flex: 1;
        border: 2px solid #e9ecef;
        border-radius: 25px;
        padding: 10px 20px;
        font-size: 0.9rem;
        resize: none;
        max-height: 120px;
        transition: border-color 0.2s;
        outline: none;
    }

    .chat-input:focus { border-color: #1a237e; }

    .btn-send {
        width: 45px; height: 45px;
        background: linear-gradient(135deg, #1a237e, #0d47a1);
        border: none;
        border-radius: 50%;
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s;
        flex-shrink: 0;
    }

    .btn-send:hover {
        transform: scale(1.1);
        box-shadow: 0 5px 15px rgba(26,35,126,0.4);
    }

    .emoji-bar {
        padding: 8px 20px;
        background: white;
        border-top: 1px solid #f0f0f0;
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }

    .emoji-btn {
        background: none;
        border: none;
        font-size: 1.3rem;
        cursor: pointer;
        padding: 2px;
        border-radius: 5px;
        transition: transform 0.1s;
    }

    .emoji-btn:hover { transform: scale(1.3); }

    .unread-badge {
        background: #f44336;
        color: white;
        border-radius: 50%;
        width: 20px; height: 20px;
        font-size: 0.7rem;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-left: auto;
    }

    .online-dot {
        width: 10px; height: 10px;
        background: #4caf50;
        border-radius: 50%;
        border: 2px solid white;
        position: absolute;
        bottom: 0; right: 0;
    }
</style>
@endpush

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">
            <i class="fas fa-comments text-primary me-2"></i>Messagerie
        </h4>
        <p class="text-muted mb-0">Communication interne BusTix</p>
    </div>
</div>

<div class="chat-container">

    <!-- ===== CONTACTS ===== -->
    <div class="contacts-panel">
        <div class="contacts-header">
            <h6 class="mb-0 fw-bold">
                <i class="fas fa-users me-2"></i>Contacts
            </h6>
        </div>
        @forelse($contacts as $contact)
        <div class="contact-item active" data-id="{{ $contact->id }}">
            <div style="position:relative">
                <div class="contact-avatar"
                     style="background:{{ $contact->isSuperAdmin() ? 'linear-gradient(135deg,#1a237e,#0d47a1)' : 'linear-gradient(135deg,#e65100,#ff6d00)' }}">
                    {{ strtoupper(substr($contact->name, 0, 1)) }}
                </div>
                <div class="online-dot"></div>
            </div>
            <div class="flex-grow-1 overflow-hidden">
                <div class="fw-bold small text-truncate">{{ $contact->name }}</div>
                <small class="text-muted">{{ $contact->isSuperAdmin() ? 'Super Admin' : 'Agent Guichet' }}</small>
            </div>
        </div>
        @empty
        <div class="p-4 text-center text-muted small">
            <i class="fas fa-user-slash fa-2x mb-2 d-block opacity-25"></i>
            Aucun contact disponible
        </div>
        @endforelse
    </div>

    <!-- ===== CHAT ===== -->
    <div class="chat-panel">
        @if($activeContact)

        <!-- Header -->
        <div class="chat-header">
            <div style="position:relative">
                <div class="contact-avatar"
                     style="width:40px;height:40px;background:{{ $activeContact->isSuperAdmin() ? 'linear-gradient(135deg,#1a237e,#0d47a1)' : 'linear-gradient(135deg,#e65100,#ff6d00)' }}">
                    {{ strtoupper(substr($activeContact->name, 0, 1)) }}
                </div>
                <div class="online-dot"></div>
            </div>
            <div>
                <div class="fw-bold">{{ $activeContact->name }}</div>
                <small class="text-success"><i class="fas fa-circle me-1" style="font-size:0.5rem"></i>En ligne</small>
            </div>
        </div>

        <!-- Messages -->
        <div class="messages-area" id="messagesArea">
            @foreach($messages as $message)
            <div class="d-flex {{ $message->sender_id === auth()->id() ? 'justify-content-end' : 'justify-content-start' }}">
                <div class="message-bubble {{ $message->sender_id === auth()->id() ? 'message-mine' : 'message-other' }}">
                    {{ $message->content }}
                    <span class="message-time">{{ $message->created_at->format('H:i') }}</span>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Emojis -->
        <div class="emoji-bar">
            @foreach(['😊','😂','👍','❤️','🙏','👋','✅','🚌','🎉','😎','🔥','💪'] as $emoji)
            <button class="emoji-btn" onclick="addEmoji('{{ $emoji }}')">{{ $emoji }}</button>
            @endforeach
        </div>

        <!-- Input -->
        <div class="chat-input-area">
            <textarea class="chat-input" id="messageInput"
                      placeholder="Écrivez votre message..."
                      rows="1"
                      onkeydown="handleKey(event)"></textarea>
            <button class="btn-send" onclick="sendMessage()">
                <i class="fas fa-paper-plane"></i>
            </button>
        </div>

        @else
        <div class="flex-grow-1 d-flex align-items-center justify-content-center">
            <div class="text-center text-muted">
                <i class="fas fa-comments fa-4x mb-3 d-block opacity-25"></i>
                <p>Aucune conversation disponible</p>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
const contactId = {{ $activeContact ? $activeContact->id : 'null' }};
let lastMessageId = {{ $messages->isNotEmpty() ? $messages->last()->id : 0 }};

// ===== Scroll bas =====
function scrollBottom() {
    const area = document.getElementById('messagesArea');
    if (area) area.scrollTop = area.scrollHeight;
}
scrollBottom();

// ===== Ajouter emoji =====
function addEmoji(emoji) {
    const input = document.getElementById('messageInput');
    input.value += emoji;
    input.focus();
}

// ===== Envoyer avec Enter =====
function handleKey(e) {
    if (e.key === 'Enter' && !e.shiftKey) {
        e.preventDefault();
        sendMessage();
    }
}

// ===== Envoyer message =====
function sendMessage() {
    const input = document.getElementById('messageInput');
    const content = input.value.trim();
    if (!content || !contactId) return;

    fetch('{{ route("messages.store") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ content, receiver_id: contactId })
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            input.value = '';
            fetchMessages();
        }
    });
}

// ===== Polling messages =====
function fetchMessages() {
    if (!contactId) return;

    fetch(`{{ route("messages.fetch") }}?contact_id=${contactId}&last_id=${lastMessageId}`)
    .then(r => r.json())
    .then(data => {
        const area = document.getElementById('messagesArea');
        const wasAtBottom = area.scrollHeight - area.scrollTop <= area.clientHeight + 50;

        data.messages.forEach(msg => {
            const div = document.createElement('div');
            div.className = `d-flex ${msg.is_mine ? 'justify-content-end' : 'justify-content-start'}`;
            div.innerHTML = `
                <div class="message-bubble ${msg.is_mine ? 'message-mine' : 'message-other'}">
                    ${msg.content}
                    <span class="message-time">${msg.time}</span>
                </div>`;
            area.appendChild(div);
            lastMessageId = msg.id;
        });

        if (wasAtBottom && data.messages.length > 0) scrollBottom();

        // Mettre à jour badge notification
        updateBadge(data.unread);
    });
}

// ===== Mettre à jour badge =====
function updateBadge(count) {
    const badges = document.querySelectorAll('.notification-badge');
    badges.forEach(b => {
        b.textContent = count;
        b.style.display = count > 0 ? 'flex' : 'none';
    });
}

// ===== Polling toutes les 3 secondes =====
setInterval(fetchMessages, 3000);
</script>
@endpush