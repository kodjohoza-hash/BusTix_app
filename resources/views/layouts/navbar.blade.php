<nav class="navbar navbar-expand-lg navbar-light bg-white sticky-top shadow-sm">
    <div class="container">
        <!-- Logo -->
        <a class="navbar-brand fw-bold" href="{{ route('home') }}">
            <i class="fas fa-bus text-primary me-2"></i>
            <span class="text-gradient">BusTix</span>
        </a>
        <!-- Toggler -->
        <button class="navbar-toggler" type="button"
                data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto gap-2 align-items-center">
                @if(!request()->routeIs('home'))
                <li class="nav-item">
                    <button onclick="goBack('{{ route('home') }}')"
                            class="btn btn-sm rounded-pill px-3 py-2"
                            style="background:linear-gradient(135deg,#1a237e,#0d47a1);
                                   color:white;border:none;
                                   box-shadow:0 3px 10px rgba(26,35,126,0.3);">
                        <i class="fas fa-arrow-left me-1"></i>Retour
                    </button>
                </li>
                @endif
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('home') ? 'fw-bold text-primary' : '' }}"
                       href="{{ route('home') }}">
                        <i class="fas fa-home me-1"></i>Accueil
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('voyages') ? 'fw-bold text-primary' : '' }}"
                       href="{{ route('voyages') }}">
                        <i class="fas fa-route me-1"></i>Displacements
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('search') ? 'fw-bold text-primary' : '' }}"
                       href="{{ route('search') }}">
                        <i class="fas fa-search me-1"></i>Rechercher
                    </a>
                </li>

                <!-- ===== CHATBOT NAVBAR BUTTON ===== -->
                <li class="nav-item">
                    <button onclick="toggleChat()" id="chatToggleBtn"
                            class="btn btn-sm rounded-pill px-3 py-2 position-relative"
                            style="background:linear-gradient(135deg,#1a237e,#0d47a1);
                                   color:white;border:none;
                                   box-shadow:0 3px 10px rgba(26,35,126,0.3);">
                        <i class="fas fa-robot me-1" id="chatIcon"></i>
                        Assistant
                        <span id="chatBadge"
                              style="position:absolute;top:-5px;right:-5px;
                                     background:#f44336;color:white;border-radius:50%;
                                     width:16px;height:16px;font-size:0.6rem;
                                     display:flex;align-items:center;justify-content:center;">1</span>
                    </button>
                </li>

                @auth
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#"
                       id="userDropdown" role="button" data-bs-toggle="dropdown">
                        <div class="d-inline-flex align-items-center justify-content-center
                                    rounded-circle text-white fw-bold me-1"
                             style="width:28px;height:28px;font-size:0.75rem;
                                    background:linear-gradient(135deg,#1a237e,#0d47a1);">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                        {{ auth()->user()->name }}
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end shadow border-0 rounded-3">
                        <li>
                            <a class="dropdown-item py-2" href="{{ route('profile') }}">
                                <i class="fas fa-id-card me-2 text-primary"></i>Profil
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item py-2" href="{{ route('reservations') }}">
                                <i class="fas fa-ticket-alt me-2 text-primary"></i>Mes Réservations
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item py-2" href="{{ route('client.payments.history') }}">
                                <i class="fas fa-receipt me-2 text-primary"></i>Mes Paiements
                            </a>
                        </li>
                        @if(auth()->user()->isSuperAdmin())
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item py-2" href="{{ route('admin.dashboard') }}">
                                <i class="fas fa-tachometer-alt me-2 text-primary"></i>Super Admin
                            </a>
                        </li>
                        @elseif(auth()->user()->isGuichet())
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item py-2" href="{{ route('guichet.dashboard') }}">
                                <i class="fas fa-tachometer-alt me-2 text-warning"></i>Guichet
                            </a>
                        </li>
                        @endif
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <input type="hidden" name="redirect" value="{{ route('home') }}">
                                <button type="submit" class="dropdown-item py-2 text-danger">
                                    <i class="fas fa-sign-out-alt me-2"></i>Déconnexion
                                </button>
                            </form>
                        </li>
                    </ul>
                </li>
                @else
                <li class="nav-item">
                    <a class="btn btn-outline-primary btn-sm rounded-pill px-3"
                       href="{{ route('login') }}">
                        <i class="fas fa-sign-in-alt me-1"></i>Connexion
                    </a>
                </li>
                <li class="nav-item">
                    <a class="btn btn-primary btn-sm rounded-pill px-3"
                       href="{{ route('register') }}">
                        <i class="fas fa-user-plus me-1"></i>Inscription
                    </a>
                </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>

<!-- ===== CHAT WINDOW ===== -->
<div id="chatWindow" style="
    display:none;
    position:fixed;
    top:65px;
    right:20px;
    width:360px;
    height:520px;
    background:white;
    border-radius:20px;
    box-shadow:0 15px 50px rgba(0,0,0,0.18);
    flex-direction:column;
    z-index:9999;
    overflow:hidden;
    border:1px solid #e8f0fe;
">
    <!-- Header -->
    <div style="background:linear-gradient(135deg,#1a237e,#0d47a1);padding:14px 18px;
                display:flex;align-items:center;gap:10px;">
        <div style="width:38px;height:38px;min-width:38px;background:white;border-radius:50%;
                    display:flex;align-items:center;justify-content:center;">
            <i class="fas fa-robot text-primary"></i>
        </div>
        <div class="flex-grow-1">
            <div style="color:white;font-weight:700;font-size:0.9rem;">BusTix Assistant</div>
            <small style="color:rgba(255,255,255,0.75);">🟢 Online — EN / FR</small>
        </div>
        <!-- Language toggle -->
        <div class="d-flex gap-1 me-2">
            <button onclick="setLang('en')" id="btnEN"
                    style="border:none;border-radius:10px;padding:3px 8px;font-size:0.7rem;
                           background:white;color:#1a237e;font-weight:700;cursor:pointer;">EN</button>
            <button onclick="setLang('fr')" id="btnFR"
                    style="border:none;border-radius:10px;padding:3px 8px;font-size:0.7rem;
                           background:rgba(255,255,255,0.2);color:white;font-weight:700;cursor:pointer;">FR</button>
        </div>
        <button onclick="toggleChat()"
                style="background:none;border:none;color:white;font-size:1rem;cursor:pointer;padding:0;">
            <i class="fas fa-times"></i>
        </button>
    </div>

    <!-- Messages -->
    <div id="chatMessages" style="flex:1;overflow-y:auto;padding:15px;
                                   display:flex;flex-direction:column;gap:10px;">
    </div>

    <!-- Quick replies -->
    <div id="quickReplies" style="padding:8px 12px;display:flex;gap:6px;flex-wrap:wrap;
                                   border-top:1px solid #f0f0f0;">
        <button class="quick-btn" onclick="quickSend('trips')">🚌 Trips</button>
        <button class="quick-btn" onclick="quickSend('prices')">💰 Prices</button>
        <button class="quick-btn" onclick="quickSend('reserve')">🎫 Reserve</button>
        <button class="quick-btn" onclick="quickSend('payment')">💳 Payment</button>
    </div>

    <!-- Input -->
    <div style="padding:10px 12px;border-top:1px solid #e9ecef;display:flex;gap:8px;">
        <input type="text" id="chatInput"
               style="flex:1;border:2px solid #e9ecef;border-radius:20px;
                      padding:8px 15px;font-size:0.85rem;outline:none;"
               placeholder="Ask me anything... / Posez votre question..."
               onkeydown="if(event.key==='Enter') sendChat()"
               onfocus="this.style.borderColor='#1a237e'"
               onblur="this.style.borderColor='#e9ecef'">
        <button onclick="sendChat()"
                style="width:38px;height:38px;border-radius:50%;
                       background:linear-gradient(135deg,#1a237e,#0d47a1);
                       border:none;color:white;cursor:pointer;flex-shrink:0;">
            <i class="fas fa-paper-plane" style="font-size:0.85rem;"></i>
        </button>
    </div>
</div>

<style>
.quick-btn {
    background:#f0f4ff;color:#1a237e;border:none;
    border-radius:20px;padding:4px 12px;font-size:0.75rem;
    cursor:pointer;transition:all 0.2s;white-space:nowrap;
}
.quick-btn:hover { background:#1a237e;color:white; }
.msg-bot {
    background:#f0f4ff;color:#1a237e;align-self:flex-start;
    border-radius:16px 16px 16px 4px;padding:10px 14px;
    font-size:0.85rem;line-height:1.5;white-space:pre-line;max-width:82%;
}
.msg-user {
    background:linear-gradient(135deg,#1a237e,#0d47a1);color:white;
    align-self:flex-end;border-radius:16px 16px 4px 16px;
    padding:10px 14px;font-size:0.85rem;line-height:1.5;max-width:82%;
}
.typing { display:flex;gap:4px;padding:10px 14px;background:#f0f4ff;
          border-radius:16px;width:fit-content;align-self:flex-start; }
.typing span { width:7px;height:7px;background:#1a237e;border-radius:50%;
               animation:bounce 1s infinite; }
.typing span:nth-child(2) { animation-delay:0.2s; }
.typing span:nth-child(3) { animation-delay:0.4s; }
@keyframes bounce { 0%,60%,100%{transform:translateY(0)} 30%{transform:translateY(-7px)} }
</style>

<script>
let chatLang = 'en';

const t = {
    en: {
        welcome: "Hello! 👋 I'm the BusTix assistant.\nI can help you with trips, prices, reservations and more.\n\nWhat would you like to know?",
        trips: "Here are the next available trips: 🚌\n\n",
        noTrips: "No upcoming trips available at the moment. Please check back later! 🚌",
        tripLine: (from, to, date, price) => `• ${from} → ${to}\n  📅 ${date} | 💰 ${price} FCFA\n`,
        prices: "Here are some of our routes and prices: 💰\n\n",
        noPrices: "Please visit our Displacements page for current prices.",
        priceLine: (from, to, price) => `• ${from} → ${to}: ${price} FCFA\n`,
        howToBook: "Here's how to book your ticket: 📋\n\n1️⃣ Go to Displacements\n2️⃣ Choose your trip\n3️⃣ Select your seat\n4️⃣ Log in or create account\n5️⃣ Choose payment method\n6️⃣ Get your QR code ticket! 🎫",
        payment: "BusTix accepts: 💳\n\n• 💵 Cash — Pay at the counter\n• 📱 Mobile Money — MTN / Orange\n• 💳 Bank Card — Visa / Mastercard\n\nAll payments are secure! 🔒",
        cities: (list) => `We serve these cities: 🗺️\n\n• ${list}\n\nSee all routes on Displacements page!`,
        cancel: "To cancel a reservation: 🔄\n\n1️⃣ Log in to your account\n2️⃣ Go to My Reservations\n3️⃣ Click cancel on unpaid tickets\n\n⚠️ Paid tickets: contact our counter.",
        contact: "Need help? 📞\n\n• Visit our counter directly\n• Manage everything in your account\n\nAnything else I can help with?",
        thanks: "You're welcome! 😊 Have a great trip with BusTix! 🚌✨",
        greet: "Hello! 👋 How can I help you today?\n\nI can assist with trips, prices, reservations and payments!",
        default: "I didn't quite understand that. 🤔\n\nI can help you with:\n• 🚌 Trips & schedules\n• 💰 Prices\n• 🎫 Reservations\n• 💳 Payments\n• 🗺️ Destinations",
        quickTrips: "trips", quickPrices: "prices", quickReserve: "how to reserve", quickPayment: "payment",
        placeholder: "Ask me anything...",
    },
    fr: {
        welcome: "Bonjour! 👋 Je suis l'assistant BusTix.\nJe peux vous aider avec les voyages, prix, réservations et plus encore.\n\nQue souhaitez-vous savoir?",
        trips: "Voici les prochains voyages disponibles: 🚌\n\n",
        noTrips: "Aucun voyage disponible pour le moment. Revenez plus tard! 🚌",
        tripLine: (from, to, date, price) => `• ${from} → ${to}\n  📅 ${date} | 💰 ${price} FCFA\n`,
        prices: "Voici quelques trajets et leurs prix: 💰\n\n",
        noPrices: "Consultez notre page Displacements pour les tarifs actuels.",
        priceLine: (from, to, price) => `• ${from} → ${to}: ${price} FCFA\n`,
        howToBook: "Comment réserver votre billet: 📋\n\n1️⃣ Allez sur Displacements\n2️⃣ Choisissez votre voyage\n3️⃣ Sélectionnez votre siège\n4️⃣ Connectez-vous ou créez un compte\n5️⃣ Choisissez le mode de paiement\n6️⃣ Recevez votre billet QR code! 🎫",
        payment: "BusTix accepte: 💳\n\n• 💵 Espèces — Payer au guichet\n• 📱 Mobile Money — MTN / Orange\n• 💳 Carte Bancaire — Visa / Mastercard\n\nTous les paiements sont sécurisés! 🔒",
        cities: (list) => `Nous desservons ces villes: 🗺️\n\n• ${list}\n\nVoyez tous les trajets sur la page Displacements!`,
        cancel: "Pour annuler une réservation: 🔄\n\n1️⃣ Connectez-vous à votre compte\n2️⃣ Allez dans Mes Réservations\n3️⃣ Cliquez Annuler sur les billets non payés\n\n⚠️ Billets payés: contactez notre guichet.",
        contact: "Besoin d'aide? 📞\n\n• Visitez notre guichet directement\n• Gérez tout depuis votre compte\n\nAutre chose que je peux faire?",
        thanks: "De rien! 😊 Bon voyage avec BusTix! 🚌✨",
        greet: "Bonjour! 👋 Comment puis-je vous aider?\n\nJe peux vous aider avec les voyages, prix, réservations et paiements!",
        default: "Je n'ai pas bien compris. 🤔\n\nJe peux vous aider avec:\n• 🚌 Voyages et horaires\n• 💰 Prix\n• 🎫 Réservations\n• 💳 Paiements\n• 🗺️ Destinations",
        quickTrips: "voyages", quickPrices: "prix", quickReserve: "comment réserver", quickPayment: "paiement",
        placeholder: "Posez votre question...",
    }
};

function setLang(lang) {
    chatLang = lang;
    document.getElementById('btnEN').style.background = lang==='en' ? 'white' : 'rgba(255,255,255,0.2)';
    document.getElementById('btnEN').style.color = lang==='en' ? '#1a237e' : 'white';
    document.getElementById('btnFR').style.background = lang==='fr' ? 'white' : 'rgba(255,255,255,0.2)';
    document.getElementById('btnFR').style.color = lang==='fr' ? '#1a237e' : 'white';
    document.getElementById('chatInput').placeholder = t[lang].placeholder;
    // Update quick reply buttons
    const btns = document.querySelectorAll('.quick-btn');
    btns[0].textContent = '🚌 ' + (lang==='en' ? 'Trips' : 'Voyages');
    btns[1].textContent = '💰 ' + (lang==='en' ? 'Prices' : 'Prix');
    btns[2].textContent = '🎫 ' + (lang==='en' ? 'Reserve' : 'Réserver');
    btns[3].textContent = '💳 ' + (lang==='en' ? 'Payment' : 'Paiement');
}

function quickSend(type) {
    const map = {
        trips:   { en: 'trips', fr: 'voyages' },
        prices:  { en: 'prices', fr: 'prix' },
        reserve: { en: 'how to reserve', fr: 'comment réserver' },
        payment: { en: 'payment', fr: 'paiement' },
    };
    document.getElementById('chatInput').value = map[type][chatLang];
    sendChat();
}

function toggleChat() {
    const w = document.getElementById('chatWindow');
    const icon = document.getElementById('chatIcon');
    const badge = document.getElementById('chatBadge');
    const isOpen = w.style.display === 'flex';

    if (!isOpen) {
        w.style.display = 'flex';
        icon.className = 'fas fa-times me-1';
        badge.style.display = 'none';
        // Show welcome on first open
        const msgs = document.getElementById('chatMessages');
        if (msgs.children.length === 0) {
            addMessage(t[chatLang].welcome, 'bot');
        }
    } else {
        w.style.display = 'none';
        icon.className = 'fas fa-robot me-1';
    }
    scrollChat();
}

function scrollChat() {
    const m = document.getElementById('chatMessages');
    if (m) m.scrollTop = m.scrollHeight;
}

function addMessage(text, type) {
    const m = document.getElementById('chatMessages');
    const div = document.createElement('div');
    div.className = type === 'user' ? 'msg-user' : 'msg-bot';
    div.textContent = text;
    m.appendChild(div);
    scrollChat();
}

function showTyping() {
    const m = document.getElementById('chatMessages');
    const div = document.createElement('div');
    div.className = 'typing';
    div.id = 'typingIndicator';
    div.innerHTML = '<span></span><span></span><span></span>';
    m.appendChild(div);
    scrollChat();
}

function removeTyping() {
    const el = document.getElementById('typingIndicator');
    if (el) el.remove();
}

function sendChat() {
    const input = document.getElementById('chatInput');
    const msg = input.value.trim();
    if (!msg) return;
    addMessage(msg, 'user');
    input.value = '';
    showTyping();

    fetch('{{ route("chatbot") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ message: msg, lang: chatLang })
    })
    .then(r => r.json())
    .then(data => {
        removeTyping();
        addMessage(data.reply, 'bot');
    })
    .catch(() => {
        removeTyping();
        addMessage(chatLang==='en' ? 'Sorry, something went wrong! 😕' : 'Désolé, une erreur est survenue! 😕', 'bot');
    });
}
</script>