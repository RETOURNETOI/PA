let lang = 'fr';

const translations = {
  fr: {
    welcomeTitle: "Bienvenue sur BrainRush !",
    welcomeSubtitle: "Testez vos connaissances...",
    soloTitle: "🧠 Quizz Solo",
    soloDesc: "Jouez en solo...",
    soloBtn: "Commencer",
    vsTitle: "⚔️ Quizz VS",
    vsDesc: "Affrontez vos amis...",
    vsBtn: "Défier",
    rankTitle: "🏆 Classement",
    rankDesc: "Découvrez...",
    rankBtn: "Voir",
    leaderboardTitle: "🏅 Les 5 MVP all time",
    chatWelcome: "👋 Salut et bienvenue sur BrainRush ! Je suis ton assistant. Tu peux me demander des choses comme :\n- Ajouter un ami\n- Envoyer un message\n- Voir de l'aide\nTape simplement ce que tu veux faire ✨"
  },
  en: {
    welcomeTitle: "Welcome to BrainRush!",
    welcomeSubtitle: "Test your knowledge...",
    soloTitle: "🧠 Solo Quiz",
    soloDesc: "Play solo...",
    soloBtn: "Start",
    vsTitle: "⚔️ VS Quiz",
    vsDesc: "Challenge your friends...",
    vsBtn: "Challenge",
    rankTitle: "🏆 Rankings",
    rankDesc: "Discover...",
    rankBtn: "View",
    leaderboardTitle: "🏅 Top 5 MVPs of all time",
    chatWelcome: "👋 Hi and welcome to BrainRush! I'm your assistant. You can ask me things like:\n- Add a friend\n- Send a message\n- Get help\nJust type what you want to do ✨"
  }
};

document.addEventListener('DOMContentLoaded', () => {
  const themeToggle = document.getElementById('themeToggle');
  const header = document.querySelector('header');
  const body = document.body;
  const cards = document.querySelectorAll('#rubriques .card');

  // 🌍 Réinitialisation automatique du message de bienvenue tous les 3 jours
  const now = Date.now();
  const expiryDays = 3;
  const chatbotTimestamp = localStorage.getItem('chatbot_welcome_date');

  if (!chatbotTimestamp || now - parseInt(chatbotTimestamp) > expiryDays * 24 * 60 * 60 * 1000) {
    localStorage.removeItem('chatbot_welcome_shown');
    localStorage.setItem('chatbot_welcome_date', now.toString());
  }

  function applyTheme(theme) {
    const sections = document.querySelectorAll('section.container.text-center.py-5');

    if (theme === 'dark') {
      body.classList.add('dark-mode');
      body.classList.remove('light-background');
      header.style.background = 'linear-gradient(to right, #DA7B27, #D7572B)';
      cards.forEach(card => card.classList.add('bg-dark', 'text-white'));
      sections.forEach(section => {
        section.style.backgroundColor = '#384454';
        section.style.color = 'white';
      });
    } else {
      body.classList.remove('dark-mode');
      body.classList.add('light-background');
      header.style.background = 'linear-gradient(to right, #007bff, #6610f2)';
      cards.forEach(card => card.classList.remove('bg-dark', 'text-white'));
      sections.forEach(section => {
        section.style.backgroundColor = '';
        section.style.color = '';
      });
    }
  }

  let currentTheme = localStorage.getItem('theme') || 'light';
  applyTheme(currentTheme);

  themeToggle?.addEventListener('click', () => {
    currentTheme = currentTheme === 'dark' ? 'light' : 'dark';
    localStorage.setItem('theme', currentTheme);
    applyTheme(currentTheme);
  });

  const chatIcon = document.getElementById('chatbot-icon');
  const chatBox = document.getElementById('chatbot-box');
  const closeBtn = document.getElementById('close-chatbot');

  if (chatIcon && chatBox && closeBtn) {
    chatIcon.addEventListener('click', () => {
      const wasHidden = chatBox.classList.contains('hidden');
      chatBox.classList.toggle('hidden');

      if (wasHidden && !localStorage.getItem('chatbot_welcome_shown')) {
        addMessage(translations[lang].chatWelcome, 'bot');
        localStorage.setItem('chatbot_welcome_shown', 'true');
        localStorage.setItem('chatbot_welcome_date', Date.now().toString());
      }
    });

    closeBtn.addEventListener('click', () => chatBox.classList.add('hidden'));
  }

  const input = document.getElementById('userInput');
  if (input) {
    input.addEventListener('keydown', function (event) {
      if (event.key === 'Enter') {
        event.preventDefault();
        sendMessage();
      }
    });
  }

  const langToggle = document.getElementById('langToggle');
  langToggle?.addEventListener('click', () => {
    lang = lang === 'fr' ? 'en' : 'fr';

    Object.entries(translations[lang]).forEach(([id, text]) => {
      const el = document.getElementById(id);
      if (el) el.textContent = text;
    });

    langToggle.querySelector('img').src = lang === 'fr' ? 'assets/drapeau_fr.png' : 'assets/drapeau_en.png';

    // 🔁 Mise à jour du message d’accueil si affiché
    const welcomeMsg = document.getElementById('chatWelcome');
    if (welcomeMsg) {
      welcomeMsg.textContent = translations[lang].chatWelcome;
    }
  });
});

function addMessage(text, sender) {
  const box = document.getElementById('chatbox');
  const msg = document.createElement('div');
  msg.classList.add('message', sender);
  msg.textContent = text;

  if (sender === 'bot' && !localStorage.getItem('chatbot_welcome_shown')) {
    msg.setAttribute('id', 'chatWelcome');
  }

  box.appendChild(msg);
  box.scrollTop = box.scrollHeight;
}

function sendMessage() {
  const input = document.getElementById('userInput');
  const userText = input.value.trim();
  if (userText) {
    addMessage(userText, 'user');
    input.value = '';
    setTimeout(() => {
      addMessage("🤖 Je réfléchis à ta demande...", 'bot');
    }, 500);
  }
}
