/**
 * BusTix - Main JavaScript File
 * Handles interactions, animations, and functionality
 */

document.addEventListener('DOMContentLoaded', function() {
    initializeAnimations();
    initializeFormHandlers();
    initializePaymentHandlers();
    initializeSeatSelector();
    initializeThemeToggle();
    initializeSmothScroll();
});

/* ============================================
   ANIMATIONS
   ============================================ */

function initializeAnimations() {
    // Intersection Observer for fade-in animations
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('fadeInUp');
                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);

    // Observe all elements with animation classes
    document.querySelectorAll('.trip-card, .feature-box, .testimonial-card, .stat-card, .card').forEach(el => {
        if (!el.classList.contains('fadeInUp')) {
            observer.observe(el);
        }
    });

    // Navbar animation on scroll
    const navbar = document.querySelector('.navbar');
    if (navbar) {
        window.addEventListener('scroll', function() {
            if (window.scrollY > 100) {
                navbar.classList.add('shadow-lg');
            } else {
                navbar.classList.remove('shadow-lg');
            }
        });
    }

    // Hero section animations
    const heroElements = document.querySelectorAll('.fadeInLeft, .fadeInRight');
    heroElements.forEach((el, index) => {
        el.style.animation = `${el.classList.contains('fadeInLeft') ? 'fadeInLeft' : 'fadeInRight'} 0.8s ease-out ${index * 0.2}s forwards`;
    });
}

/* ============================================
   FORM HANDLERS
   ============================================ */

function initializeFormHandlers() {
    // Form validation
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            if (!form.checkValidity()) {
                e.preventDefault();
                e.stopPropagation();
                form.classList.add('was-validated');
            }
        });
    });

    // Input focus effect
    const inputs = document.querySelectorAll('.form-control, .form-select');
    inputs.forEach(input => {
        input.addEventListener('focus', function() {
            this.parentElement.classList.add('is-focused');
        });

        input.addEventListener('blur', function() {
            this.parentElement.classList.remove('is-focused');
        });
    });

    // Real-time form validation
    inputs.forEach(input => {
        input.addEventListener('change', function() {
            validateInput(this);
        });
    });
}

function validateInput(input) {
    if (input.type === 'email') {
        const isValid = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(input.value);
        input.classList.toggle('is-invalid', !isValid && input.value);
        input.classList.toggle('is-valid', isValid);
    }

    if (input.type === 'tel') {
        const isValid = /^[\d\s+-]+$/.test(input.value) && input.value.length >= 9;
        input.classList.toggle('is-invalid', !isValid && input.value);
        input.classList.toggle('is-valid', isValid);
    }
}

/* ============================================
   PAYMENT HANDLERS
   ============================================ */

function initializePaymentHandlers() {
    const paymentOptions = document.querySelectorAll('input[name="payment"]');
    
    paymentOptions.forEach(option => {
        option.addEventListener('change', function() {
            // Remove selected class from all parent labels
            document.querySelectorAll('.payment-option').forEach(el => {
                el.classList.remove('selected');
            });

            // Add selected class to clicked option
            this.closest('.payment-option').classList.add('selected');

            // Show/hide card form
            const cardForm = document.getElementById('card-form');
            if (cardForm) {
                if (this.id === 'card') {
                    cardForm.style.display = 'block';
                    cardForm.classList.add('fadeInUp');
                } else {
                    cardForm.style.display = 'none';
                }
            }
        });
    });

    // Card number formatting
    const cardInput = document.querySelector('input[placeholder="1234 5678 9012 3456"]');
    if (cardInput) {
        cardInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\s/g, '');
            let formattedValue = value.match(/.{1,4}/g)?.join(' ') || value;
            e.target.value = formattedValue;
        });
    }

    // CVV formatting
    const cvvInput = document.querySelector('input[placeholder="123"]');
    if (cvvInput) {
        cvvInput.addEventListener('input', function(e) {
            e.target.value = e.target.value.replace(/[^0-9]/g, '');
        });
    }

    // Expiration date formatting
    const dateInput = document.querySelector('input[placeholder="MM/AA"]');
    if (dateInput) {
        dateInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length >= 2) {
                value = value.substring(0, 2) + '/' + value.substring(2, 4);
            }
            e.target.value = value;
        });
    }

    // Update total price
    updateTotalPrice();
}

function updateTotalPrice() {
    const insuranceCheckbox = document.getElementById('insurance');
    const luggageCheckbox = document.getElementById('luggage');
    const totalPriceElement = document.getElementById('total-price');

    if (!totalPriceElement) return;

    function calculateTotal() {
        let total = 29; // Base price
        if (insuranceCheckbox && insuranceCheckbox.checked) total += 3;
        if (luggageCheckbox && luggageCheckbox.checked) total += 5;
        totalPriceElement.textContent = total + '€';
    }

    if (insuranceCheckbox) {
        insuranceCheckbox.addEventListener('change', calculateTotal);
    }
    if (luggageCheckbox) {
        luggageCheckbox.addEventListener('change', calculateTotal);
    }
}

/* ============================================
   SEAT SELECTOR
   ============================================ */

function initializeSeatSelector() {
    const seatCheckboxes = document.querySelectorAll('.seat-selector .btn-check');
    const selectedSeatsDiv = document.getElementById('selected-seats');

    seatCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            updateSelectedSeats();
        });
    });

    function updateSelectedSeats() {
        const selectedSeats = Array.from(seatCheckboxes)
            .filter(cb => cb.checked)
            .map(cb => cb.value);

        if (selectedSeatsDiv) {
            if (selectedSeats.length === 0) {
                selectedSeatsDiv.innerHTML = '<span class="badge bg-secondary">Aucune place</span>';
            } else {
                selectedSeatsDiv.innerHTML = selectedSeats
                    .map(seat => `<span class="badge bg-primary me-1">${seat}</span>`)
                    .join('');
            }
        }
    }
}

/* ============================================
   THEME TOGGLE (Dark Mode)
   ============================================ */

function initializeThemeToggle() {
    const themeToggle = document.getElementById('theme-toggle');
    const htmlElement = document.documentElement;

    // Check for saved theme preference or default to light mode
    const savedTheme = localStorage.getItem('theme') || 'light';
    htmlElement.setAttribute('data-bs-theme', savedTheme);

    if (themeToggle) {
        themeToggle.addEventListener('click', function() {
            const currentTheme = htmlElement.getAttribute('data-bs-theme');
            const newTheme = currentTheme === 'light' ? 'dark' : 'light';

            htmlElement.setAttribute('data-bs-theme', newTheme);
            localStorage.setItem('theme', newTheme);

            // Update button icon
            const icon = themeToggle.querySelector('i');
            if (icon) {
                icon.classList.toggle('fa-moon');
                icon.classList.toggle('fa-sun');
            }
        });
    }
}

/* ============================================
   SMOOTH SCROLL
   ============================================ */

function initializeSmothScroll() {
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
}

/* ============================================
   UTILITY FUNCTIONS
   ============================================ */

// Debounce function
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

// Show toast notification
function showToast(message, type = 'info') {
    const toast = document.createElement('div');
    toast.className = `alert alert-${type} position-fixed`;
    toast.style.cssText = `
        top: 20px;
        right: 20px;
        z-index: 9999;
        max-width: 400px;
        animation: slideDown 0.3s ease-out;
    `;
    toast.textContent = message;

    document.body.appendChild(toast);

    setTimeout(() => {
        toast.remove();
    }, 3000);
}

// Format price
function formatPrice(price) {
    return new Intl.NumberFormat('fr-FR', {
        style: 'currency',
        currency: 'EUR'
    }).format(price);
}

// Get URL parameters
function getUrlParameter(name) {
    name = name.replace(/[\[]/, '\\[').replace(/[\]]/, '\\]');
    const regex = new RegExp('[\\?&]' + name + '=([^&#]*)');
    const results = regex.exec(location.search);
    return results === null ? '' : decodeURIComponent(results[1].replace(/\+/g, ' '));
}

/* ============================================
   EXPORT FUNCTIONS
   ============================================ */

window.BusTix = {
    showToast,
    formatPrice,
    getUrlParameter,
    debounce
};

console.log('BusTix initialized successfully!');
