<footer class="bg-dark text-light py-5 mt-5">
    <div class="container">
        <div class="row mb-4">
            <!-- About -->
            <div class="col-md-4 mb-4">
                <h5 class="text-gradient mb-3">
                    <i class="fas fa-bus me-2"></i>BusTix
                </h5>
                <p class="text-muted">Votre plateforme de réservation de bus en ligne. Facile, rapide et sécurisé.</p>
                <div class="social-links">
                    <a href="#" class="text-light me-3 hover-effect"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="text-light me-3 hover-effect"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="text-light me-3 hover-effect"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="text-light hover-effect"><i class="fab fa-linkedin-in"></i></a>
                </div>
            </div>
            
            <!-- Quick Links -->
            <div class="col-md-2 mb-4">
                <h6 class="text-white mb-3">Navigation</h6>
                <ul class="list-unstyled">
                    <li><a href="{{ route('home') }}" class="text-muted hover-effect">Accueil</a></li>
                    <li><a href="{{ route('voyages') }}" class="text-muted hover-effect">Voyages</a></li>
                    <li><a href="{{ route('search') }}" class="text-muted hover-effect">Rechercher</a></li>
                    <li><a href="#" class="text-muted hover-effect">Contact</a></li>
                </ul>
            </div>
            
            <!-- Useful Links -->
            <div class="col-md-2 mb-4">
                <h6 class="text-white mb-3">Utile</h6>
                <ul class="list-unstyled">
                    <li><a href="#" class="text-muted hover-effect">Conditions</a></li>
                    <li><a href="#" class="text-muted hover-effect">Politique</a></li>
                    <li><a href="#" class="text-muted hover-effect">FAQ</a></li>
                    <li><a href="#" class="text-muted hover-effect">Support</a></li>
                </ul>
            </div>
            
            <!-- Contact -->
            <div class="col-md-4 mb-4">
                <h6 class="text-white mb-3">Contact</h6>
                <p class="text-muted">
                    <i class="fas fa-map-marker-alt me-2"></i>123 Rue de la Gare, Ville, Pays
                </p>
                <p class="text-muted">
                    <i class="fas fa-phone me-2"></i>+33 1 23 45 67 89
                </p>
                <p class="text-muted">
                    <i class="fas fa-envelope me-2"></i>support@bustix.com
                </p>
            </div>
        </div>
        
        <hr class="bg-secondary">
        
        <!-- Copyright -->
        <div class="row">
            <div class="col-md-6">
                <p class="text-muted mb-0">&copy; 2026 BusTix. Tous droits réservés.</p>
            </div>
            <div class="col-md-6 text-md-end">
                <p class="text-muted mb-0">Développé avec <i class="fas fa-heart text-danger"></i> par BusTix Team</p>
            </div>
        </div>
    </div>
</footer>
