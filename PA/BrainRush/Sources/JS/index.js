// index.js
function applyTheme(theme) {
  const body = document.body;
  const nav = document.querySelector(".custom-navbar");
  const header = document.querySelector("header");
  const themeToggle = document.getElementById('themeToggle');

  // Nettoyer toutes les classes de thème
  body.classList.remove("dark-mode", "light-background", "light-mode");

  if (theme === "dark") {
    body.classList.add("dark-mode");
    
    if (nav) nav.style.background = "linear-gradient(to right, #DA7B27, #D7572B)";
    if (header) header.style.background = "linear-gradient(to right, #DA7B27, #D7572B)";
    if (themeToggle) themeToggle.textContent = "☀️";
  } else {
    body.classList.add("light-background");
    
    if (nav) nav.style.background = "linear-gradient(to right, #4f46e5, #7c3aed)";
    if (header) header.style.background = "linear-gradient(to right, #4f46e5, #7c3aed)";
    if (themeToggle) themeToggle.textContent = "🌙";
  }

  // Appliquer le thème au chatbot si la fonction existe
  if (typeof window.applyChatbotTheme === 'function') {
    window.applyChatbotTheme(theme);
  }
  
  console.log('Theme applied:', theme, 'Body classes:', body.className);
}

function toggleTheme() {
  const currentTheme = localStorage.getItem('theme') || 'light';
  const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
  
  console.log('Toggling theme from', currentTheme, 'to', newTheme);
  
  localStorage.setItem('theme', newTheme);
  applyTheme(newTheme);
  
  // Mettre à jour le chatbot
  if (typeof window.displayChatbotWelcome === 'function') {
    const lang = localStorage.getItem('lang') || 'fr';
    const isConnected = localStorage.getItem('user_connected') === 'true';
    window.displayChatbotWelcome(lang, newTheme, isConnected);
  }
}

function toggleLanguage() {
  const currentLang = localStorage.getItem('lang') || 'fr';
  const newLang = currentLang === 'fr' ? 'en' : 'fr';
  
  console.log('Toggling language from', currentLang, 'to', newLang);
  
  localStorage.setItem('lang', newLang);
  updateLanguageButton(newLang);
  
  // Traduire la page
  if (typeof window.translatePage === 'function') {
    window.translatePage(newLang);
  }
}

function updateLanguageButton(lang) {
  const langIcon = document.getElementById('langIcon');
  if (langIcon) {
    if (lang === 'fr') {
      // En mode français, on montre le drapeau anglais pour basculer vers l'anglais
      langIcon.textContent = '🇬🇧';
      langIcon.setAttribute('title', 'Switch to English');
    } else {
      // En mode anglais, on montre le drapeau français pour basculer vers le français
      langIcon.textContent = '🇫🇷';
      langIcon.setAttribute('title', 'Passer au français');
    }
  }
}

function setupChatbotToggle() {
  const chatbotIcon = document.getElementById('chatbot-icon');
  const chatbotBox = document.getElementById('chatbot-box');
  const closeBtn = document.getElementById('close-chatbot');

  if (chatbotIcon && chatbotBox) {
    chatbotIcon.addEventListener('click', () => {
      chatbotBox.classList.toggle('active');
    });
  }

  if (closeBtn && chatbotBox) {
    closeBtn.addEventListener('click', () => {
      chatbotBox.classList.remove('active');
    });
  }
}

function handleResponsiveNavbar() {
  const navbarActions = document.querySelector('.navbar-actions');
  const navbarMenu = document.getElementById('navbar-menu');

  function updateNavbar() {
    const isMobile = window.innerWidth <= 768;
    
    if (isMobile) {
      navbarActions?.classList.add('show');
      navbarMenu?.classList.remove('show');
    } else {
      navbarActions?.classList.remove('show');
      navbarMenu?.classList.add('show');
    }
  }

  window.addEventListener('resize', updateNavbar);
  updateNavbar();
}

function setupButtonEffects() {
  const buttons = document.querySelectorAll('.navbar-btn');
  
  buttons.forEach(btn => {
    btn.addEventListener('mousedown', () => {
      btn.style.transform = 'translateY(1px)';
    });
    
    btn.addEventListener('mouseup', () => {
      btn.style.transform = '';
    });
    
    btn.addEventListener('mouseleave', () => {
      btn.style.transform = '';
    });
  });
}

// Fonction pour traduire la page (optionnelle)
function translatePage(lang) {
  const translations = {
    fr: {
      welcomeTitle: "Bienvenue sur BrainRush !",
      welcomeSubtitle: "Testez vos connaissances, affrontez vos amis, grimpez dans le classement !",
      navHome: "Accueil",
      navSolo: "Solo",
      navVS: "VS",
      navRank: "Classement",
      soloTitle: "🧠 Quizz Solo",
      soloDesc: "Jouez en solo sur des dizaines de thèmes !",
      soloBtn: "Commencer",
      vsTitle: "⚔️ Quizz VS",
      vsDesc: "Affrontez vos amis en temps réel.",
      vsBtn: "Défier",
      rankTitle: "🏆 Classement",
      rankDesc: "Découvrez les meilleurs joueurs.",
      rankBtn: "Voir",
      tournoisTitle: "🏆 Tournois",
      tournoisDesc: "Découvrez un tout nouveau niveau.",
      tournoisBtn: "Participer",
      podiumTitle: "🏆 Podium des 3 MVP All-Time",
      footerText: "© 2025 BrainRush. Tous droits réservés.",
      loginBtn: "Se connecter",
      signupBtn: "S'inscrire"
    },
    en: {
      welcomeTitle: "Welcome to BrainRush!",
      welcomeSubtitle: "Test your knowledge, challenge your friends, climb the rankings!",
      navHome: "Home",
      navSolo: "Solo",
      navVS: "VS",
      navRank: "Rankings",
      soloTitle: "🧠 Solo Quiz",
      soloDesc: "Play solo on dozens of themes!",
      soloBtn: "Start",
      vsTitle: "⚔️ VS Quiz",
      vsDesc: "Challenge your friends in real time.",
      vsBtn: "Challenge",
      rankTitle: "🏆 Rankings",
      rankDesc: "Discover the best players.",
      rankBtn: "View",
      tournoisTitle: "🏆 Tournaments",
      tournoisDesc: "Discover a whole new level.",
      tournoisBtn: "Participate",
      podiumTitle: "🏆 Top 3 MVP All-Time Podium",
      footerText: "© 2025 BrainRush. All rights reserved.",
      loginBtn: "Login",
      signupBtn: "Sign up"
    }
  };

  const texts = translations[lang];
  if (texts) {
    Object.keys(texts).forEach(key => {
      const element = document.getElementById(key);
      if (element) {
        if (element.tagName === 'INPUT') {
          element.placeholder = texts[key];
        } else {
          element.textContent = texts[key];
        }
      }
    });
  }
}

// Initialisation au chargement de la page
document.addEventListener('DOMContentLoaded', () => {
  console.log('DOM Content Loaded - index.js');
  
  // Initialisation du thème
  const savedTheme = localStorage.getItem('theme') || 'light';
  console.log('Saved theme:', savedTheme);
  applyTheme(savedTheme);

  // Initialisation de la langue
  const savedLang = localStorage.getItem('lang') || 'fr';
  console.log('Saved language:', savedLang);
  updateLanguageButton(savedLang);
  translatePage(savedLang);

  // Écouteurs d'événements
  const themeToggle = document.getElementById('themeToggle');
  const langToggle = document.getElementById('langToggle');
  
  if (themeToggle) {
    // Supprimer les anciens listeners s'ils existent
    themeToggle.removeEventListener('click', toggleTheme);
    themeToggle.addEventListener('click', toggleTheme);
    console.log('Theme toggle listener added');
  }
  
  if (langToggle) {
    // Supprimer les anciens listeners s'ils existent
    langToggle.removeEventListener('click', toggleLanguage);
    langToggle.addEventListener('click', toggleLanguage);
    console.log('Language toggle listener added');
  }

  // Initialisation des autres fonctionnalités
  setupChatbotToggle();
  handleResponsiveNavbar();
  setupButtonEffects();
  
  // Exposer les fonctions globalement
  window.translatePage = translatePage;
});