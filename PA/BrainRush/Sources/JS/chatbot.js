// chatbot.js
const translations = {
  fr: {
    welcomeTitle: "Bienvenue sur BrainRush !",
    welcomeSubtitle: "Testez vos connaissances, affrontez vos amis, grimpez dans le classement !",
    soloTitle: "🧠 Quizz Solo",
    soloDesc: "Jouez en solo sur des dizaines de thèmes !",
    soloBtn: "Commencer",
    vsTitle: "⚔️ Quizz VS",
    vsDesc: "Affrontez vos amis en temps réel.",
    vsBtn: "Défier",
    rankTitle: "🏆 Classement",
    rankDesc: "Découvrez les meilleurs joueurs.",
    rankBtn: "Voir",
    podiumTitle: "🏆 Podium des 3 MVP All-Time",
    footerText: "© 2025 BrainRush. Tous droits réservés.",
    navHome: "Accueil",
    navSolo: "Solo",
    navVS: "VS",
    navRank: "Classement",
    loginBtn: "Se connecter",
    signupBtn: "S'inscrire",
    welcomeConnected: "👋 Salut ! Comment puis-je t'aider aujourd'hui ?",
    welcomeDisconnected: "👋 Bonjour ! Connectez-vous pour accéder à toutes les fonctionnalités.",
    inputPlaceholder: "Écris ton message..."
  },
  en: {
    welcomeTitle: "Welcome to BrainRush!",
    welcomeSubtitle: "Test your knowledge, challenge your friends, climb the rankings!",
    soloTitle: "🧠 Solo Quiz",
    soloDesc: "Play solo on dozens of themes!",
    soloBtn: "Start",
    vsTitle: "⚔️ VS Quiz",
    vsDesc: "Challenge your friends in real time.",
    vsBtn: "Challenge",
    rankTitle: "🏆 Rankings",
    rankDesc: "Discover the best players.",
    rankBtn: "View",
    podiumTitle: "🏆 All-Time MVP Podium",
    footerText: "© 2025 BrainRush. All rights reserved.",
    navHome: "Home",
    navSolo: "Solo",
    navVS: "VS",
    navRank: "Rankings",
    loginBtn: "Login",
    signupBtn: "Sign Up",
    welcomeConnected: "👋 Hi! How can I help you today?",
    welcomeDisconnected: "👋 Hello! Please log in to access all features.",
    inputPlaceholder: "Type your message..."
  }
};

// Rendre les traductions disponibles globalement
window.translations = translations;

// Fonction pour appliquer le thème au chatbot
window.applyChatbotTheme = function(theme) {
  const chatbotBox = document.getElementById('chatbot-box');
  const chatbotIcon = document.getElementById('chatbot-icon');
  const buttons = document.querySelectorAll('.chatbot-button-container a');
  
  if (chatbotBox) {
    if (theme === 'dark') {
      chatbotBox.style.background = 'linear-gradient(135deg, #DA7B27, #D7572B)';
    } else {
      chatbotBox.style.background = 'linear-gradient(135deg, #4f46e5, #7c3aed)';
    }
  }
  
  if (chatbotIcon) {
    if (theme === 'dark') {
      chatbotIcon.style.background = 'linear-gradient(135deg, #DA7B27, #D7572B)';
    } else {
      chatbotIcon.style.background = 'linear-gradient(135deg, #4f46e5, #7c3aed)';
    }
  }
  
  buttons.forEach(btn => {
    if (theme === 'dark') {
      btn.style.color = '#DA7B27';
      btn.style.borderColor = '#DA7B27';
    } else {
      btn.style.color = '#4f46e5';
      btn.style.borderColor = '#4f46e5';
    }
  });
};

// Fonction pour afficher le message de bienvenue du chatbot
window.displayChatbotWelcome = function(lang = 'fr', theme = 'light', isConnected = false) {
  const chatbox = document.getElementById('chatbox');
  const userInput = document.getElementById('userInput');
  
  if (!chatbox) return;
  
  // Vider le chatbox
  chatbox.innerHTML = '';
  
  // Message de bienvenue
  const welcomeMsg = document.createElement('div');
  welcomeMsg.className = 'message bot';
  welcomeMsg.textContent = isConnected 
    ? translations[lang].welcomeConnected 
    : translations[lang].welcomeDisconnected;
  chatbox.appendChild(welcomeMsg);
  
  // Boutons de connexion si non connecté
  if (!isConnected) {
    const btnContainer = document.createElement('div');
    btnContainer.className = 'chatbot-button-container';
    
    const loginBtn = document.createElement('a');
    loginBtn.href = 'connexion.html';
    loginBtn.textContent = translations[lang].loginBtn;
    
    const signupBtn = document.createElement('a');
    signupBtn.href = 'inscription.html';
    signupBtn.textContent = translations[lang].signupBtn;
    
    btnContainer.appendChild(loginBtn);
    btnContainer.appendChild(signupBtn);
    chatbox.appendChild(btnContainer);
  }
  
  // Mettre à jour le placeholder de l'input
  if (userInput) {
    userInput.placeholder = translations[lang].inputPlaceholder;
  }
  
  // Appliquer le thème
  window.applyChatbotTheme(theme);
  
  // Scroll vers le bas
  chatbox.scrollTop = chatbox.scrollHeight;
};

// Fonction de traduction globale
window.translatePage = function(lang) {
  // Traduire tous les éléments avec un ID
  const elements = document.querySelectorAll('[id]');
  elements.forEach(el => {
    const key = el.id;
    if (translations[lang] && translations[lang][key]) {
      if (el.tagName === 'INPUT' && el.type !== 'button' && el.type !== 'submit') {
        if (el.placeholder !== undefined) {
          el.placeholder = translations[lang][key];
        }
      } else {
        el.textContent = translations[lang][key];
      }
    }
  });
  
  // Mettre à jour le chatbot
  const savedTheme = localStorage.getItem('theme') || 'light';
  const isConnected = localStorage.getItem('user_connected') === 'true';
  window.displayChatbotWelcome(lang, savedTheme, isConnected);
};

// Initialisation du chatbot
function initChatbot() {
  const theme = localStorage.getItem('theme') || 'light';
  const lang = localStorage.getItem('lang') || 'fr';
  const isConnected = localStorage.getItem('user_connected') === 'true';
  
  window.displayChatbotWelcome(lang, theme, isConnected);
  
  // Gestion de l'input utilisateur
  const userInput = document.getElementById('userInput');
  if (userInput) {
    userInput.addEventListener('keypress', (e) => {
      if (e.key === 'Enter') {
        if (userInput.value.trim() === '') return;
        
        const chatbox = document.getElementById('chatbox');
        const currentLang = localStorage.getItem('lang') || 'fr';
        
        // Ajouter le message de l'utilisateur
        const userMessage = document.createElement('div');
        userMessage.className = 'message user';
        userMessage.textContent = userInput.value;
        chatbox.appendChild(userMessage);
        
        // Réponse du bot
        const botMessage = document.createElement('div');
        botMessage.className = 'message bot';
        botMessage.textContent = translations[currentLang].welcomeConnected;
        chatbox.appendChild(botMessage);
        
        userInput.value = '';
        chatbox.scrollTop = chatbox.scrollHeight;
      }
    });
  }
}

// Initialisation au chargement de la page
document.addEventListener('DOMContentLoaded', () => {
  const lang = localStorage.getItem('lang') || 'fr';
  window.translatePage(lang);
  initChatbot();
});