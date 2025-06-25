// Script pour le toggle du menu mobile (identique à index.html)
document.addEventListener('DOMContentLoaded', function() {
    // Toggle menu mobile
    const navbarToggle = document.getElementById('navbar-toggle');
    const navbarMenu = document.getElementById('navbar-menu');
    
    if (navbarToggle && navbarMenu) {
        navbarToggle.addEventListener('click', function() {
            navbarMenu.classList.toggle('show');
        });
    }

    // Gestion du bouton evil de connexion - CORRECTION
    const evilButton = document.getElementById('evil-button');
    const loginForm = document.getElementById('loginForm');
    
    if (evilButton && loginForm) {
        evilButton.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Vérifier que les champs sont remplis avant de soumettre
            const emailInput = document.getElementById('emailInput');
            const passwordInput = document.getElementById('passwordInput');
            
            if (!emailInput.value.trim() || !passwordInput.value.trim()) {
                alert('Veuillez remplir tous les champs');
                return;
            }
            
            // Soumettre le formulaire
            loginForm.submit();
        });
    }

    // Toggle thème
    const themeToggle = document.getElementById('themeToggle');
    if (themeToggle) {
        themeToggle.addEventListener('click', function() {
            document.body.classList.toggle('dark-mode');
            
            // Sauvegarder la préférence (éviter localStorage dans les artifacts)
            if (document.body.classList.contains('dark-mode')) {
                this.textContent = '☀️';
            } else {
                this.textContent = '🌙';
            }
        });
    }

    // Charger le thème par défaut
    // Vous pouvez supprimer cette partie si localStorage pose problème
    try {
        const savedTheme = localStorage.getItem('theme');
        if (savedTheme === 'dark') {
            document.body.classList.add('dark-mode');
            if (themeToggle) themeToggle.textContent = '☀️';
        }
    } catch(e) {
        // Ignorer les erreurs localStorage
        console.log('localStorage non disponible');
    }

    // Toggle langue
    const langToggle = document.getElementById('langToggle');
    const langIcon = document.getElementById('langIcon');
    
    if (langToggle && langIcon) {
        langToggle.addEventListener('click', function() {
            // Exemple de toggle FR/EN
            if (langIcon.textContent === '🇫🇷') {
                langIcon.textContent = '🇬🇧';
                changeLanguage('en');
            } else {
                langIcon.textContent = '🇫🇷';
                changeLanguage('fr');
            }
        });
    }
});

// Fonction pour changer la langue (exemple)
function changeLanguage(lang) {
    const translations = {
        fr: {
            title: '🔐 Connexion',
            emailLabel: 'Adresse email',
            passwordLabel: 'Mot de passe',
            emailPlaceholder: 'Entrez votre email',
            passwordPlaceholder: 'Entrez votre mot de passe',
            signupLink: 'Vous n\'avez pas de compte ? Inscrivez-vous',
            buttonText: 'Connexion'
        },
        en: {
            title: '🔐 Login',
            emailLabel: 'Email address',
            passwordLabel: 'Password',
            emailPlaceholder: 'Enter your email',
            passwordPlaceholder: 'Enter your password',
            signupLink: 'Don\'t have an account? Sign up',
            buttonText: 'Login'
        }
    };

    const t = translations[lang];
    if (t) {
        const titleElement = document.getElementById('title');
        const emailLabel = document.querySelector('label[for="emailInput"]');
        const passwordLabel = document.querySelector('label[for="passwordInput"]');
        const emailInput = document.getElementById('emailInput');
        const passwordInput = document.getElementById('passwordInput');
        const signupLink = document.querySelector('#signupLine a');
        const evilButton = document.getElementById('evil-button');

        // Vérifier que les éléments existent avant de les modifier
        if (titleElement) titleElement.textContent = t.title;
        if (emailLabel) emailLabel.textContent = t.emailLabel;
        if (passwordLabel) passwordLabel.textContent = t.passwordLabel;
        if (emailInput) emailInput.placeholder = t.emailPlaceholder;
        if (passwordInput) passwordInput.placeholder = t.passwordPlaceholder;
        if (signupLink) signupLink.textContent = t.signupLink;
        if (evilButton) evilButton.textContent = t.buttonText;
    }
}