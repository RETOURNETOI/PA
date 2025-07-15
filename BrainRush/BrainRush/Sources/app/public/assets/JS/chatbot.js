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
    navForum: "Forum",
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
    navForum: "Forum",
    loginBtn: "Login",
    signupBtn: "Sign Up",
    welcomeConnected: "👋 Hi! How can I help you today?",
    welcomeDisconnected: "👋 Hello! Please log in to access all features.",
    inputPlaceholder: "Type your message..."
  }
};

const chatbotResponses = {
  fr: {
    help: "Je peux vous aider avec :\n- Règles du jeu\n- Navigation\n- Informations générales\nPosez-moi une question !",
    rules: "Les règles sont simples :\n1. Répondez aux questions le plus vite possible\n2. Chaque bonne réponse rapporte des points\n3. Plus vous répondez vite, plus vous gagnez de points !",
    navigation: "Vous pouvez naviguer vers :\n- Solo : pour jouer seul\n- VS : pour défier un ami\n- Classement : pour voir votre position",
    default: "Désolé, je n'ai pas compris. Tapez 'aide' pour voir ce que je peux faire."
  },
  en: {
    help: "I can help with:\n- Game rules\n- Navigation\n- General information\nAsk me anything!",
    rules: "The rules are simple:\n1. Answer questions as fast as you can\n2. Each correct answer gives you points\n3. The faster you answer, the more points you get!",
    navigation: "You can navigate to:\n- Solo: to play alone\n- VS: to challenge a friend\n- Rankings: to see your position",
    default: "Sorry, I didn't understand that. Type 'help' to see what I can do."
  }
};

function initChatbot() {
    const chatbotIcon = document.getElementById('chatbot-icon');
    const chatbotBox = document.getElementById('chatbot-box');
    const closeBtn = document.getElementById('close-chatbot');
    const userInput = document.getElementById('userInput');

    if (!chatbotIcon || !chatbotBox) return;

    displayWelcomeMessage();

    chatbotIcon.addEventListener('click', () => {
        chatbotBox.classList.toggle('hidden');
        if (!chatbotBox.classList.contains('hidden')) {
            userInput?.focus();
        }
    });

    if (closeBtn) {
        closeBtn.addEventListener('click', () => {
            chatbotBox.classList.add('hidden');
        });
    }

    if (userInput) {
        userInput.addEventListener('keypress', (e) => {
            if (e.key === 'Enter' && userInput.value.trim()) {
                handleUserMessage(userInput.value.trim());
                userInput.value = '';
            }
        });
    }
}

function displayWelcomeMessage() {
    const chatbox = document.getElementById('chatbox');
    const lang = localStorage.getItem('lang') || 'fr';
    const isConnected = checkUserConnection();

    if (!chatbox) return;

    chatbox.innerHTML = '';

    const welcomeMsg = document.createElement('div');
    welcomeMsg.className = 'message bot';
    welcomeMsg.textContent = isConnected 
        ? translations[lang].welcomeConnected 
        : translations[lang].welcomeDisconnected;
    chatbox.appendChild(welcomeMsg);

    if (!isConnected) {
        const btnContainer = document.createElement('div');
        btnContainer.className = 'chatbot-button-container';
        
        const loginBtn = document.createElement('a');
        loginBtn.href = '/BrainRush/BrainRush/auth/login';
        loginBtn.textContent = translations[lang].loginBtn;
        
        const signupBtn = document.createElement('a');
        signupBtn.href = '/BrainRush/BrainRush/auth/register';
        signupBtn.textContent = translations[lang].signupBtn;
        
        btnContainer.appendChild(loginBtn);
        btnContainer.appendChild(signupBtn);
        chatbox.appendChild(btnContainer);
    }

    chatbox.scrollTop = chatbox.scrollHeight;
}

function handleUserMessage(message) {
    const chatbox = document.getElementById('chatbox');
    const lang = localStorage.getItem('lang') || 'fr';

    const userMessage = document.createElement('div');
    userMessage.className = 'message user';
    userMessage.textContent = message;
    chatbox.appendChild(userMessage);

    const botResponse = processUserMessage(message, lang);
    const botMessage = document.createElement('div');
    botMessage.className = 'message bot';
    botMessage.textContent = botResponse;
    chatbox.appendChild(botMessage);

    chatbox.scrollTop = chatbox.scrollHeight;
}

function processUserMessage(message, lang) {
    const lowerMsg = message.toLowerCase();
    const responses = chatbotResponses[lang];
    
    if (lowerMsg.includes('aide') || lowerMsg.includes('help')) {
        return responses.help;
    } else if (lowerMsg.includes('règle') || lowerMsg.includes('rule')) {
        return responses.rules;
    } else if (lowerMsg.includes('navigation') || lowerMsg.includes('navigate')) {
        return responses.navigation;
    } else {
        return responses.default;
    }
}

function checkUserConnection() {
    return document.querySelector('.navbar-btn[href*="compte"]') !== null;
}

function translatePage(lang) {
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

    displayWelcomeMessage();
}

document.addEventListener('DOMContentLoaded', () => {
    initChatbot();
    
    const savedLang = localStorage.getItem('lang') || 'fr';
    if (typeof translatePage === 'function') {
        translatePage(savedLang);
    }
});

window.translatePage = translatePage;