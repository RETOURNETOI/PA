// ✅ chatbot.js FINAL avec gestion du bouton, thème et textes dynamiques

const chatbotMessages = {
    fr: {
      welcomeConnected: "👋 Salut ! Tu peux me demander :\n- Ajouter un ami\n- Envoyer un message\n- Voir de l'aide",
      welcomeDisconnected: "👋 Salut ! Pour utiliser BrainBot, connecte-toi ou crée un compte.",
      loginBtn: "Me connecter",
      signupBtn: "Je m'inscris",
      notFound: "🚧 Oups ! Cette page n'existe pas.",
      backHome: "Retour à l'accueil"
    },
    en: {
      welcomeConnected: "👋 Hi! You can ask me:\n- Add a friend\n- Send a message\n- Get help",
      welcomeDisconnected: "👋 Hi! To use BrainBot, please log in or create an account.",
      loginBtn: "Log In",
      signupBtn: "Sign Up",
      notFound: "🚧 Oops! This page doesn't exist.",
      backHome: "Return Home"
    }
  };
  
  function applyChatbotTheme(theme) {
    const chatbotBox = document.getElementById('chatbot-box');
    const chatbotInput = document.querySelector('.chatbox-input input');
    const loginBtns = document.querySelectorAll('.chatbot-button-container a');
  
    if (chatbotBox) {
      chatbotBox.style.background = theme === 'dark'
        ? 'linear-gradient(to right, #DA7B27, #D7572B)'
        : 'linear-gradient(to right, #007bff, #6610f2)';
    }
  
    if (chatbotInput) {
      chatbotInput.style.backgroundColor = theme === 'dark' ? '#4a5568' : '#ffffff';
      chatbotInput.style.color = theme === 'dark' ? '#ffffff' : '#222222';
    }
  
    if (loginBtns.length) {
      loginBtns.forEach(btn => {
        btn.style.background = 'white';
        btn.style.color = theme === 'dark' ? '#DA7B27' : '#007bff';
        btn.style.fontWeight = 'bold';
      });
    }
  }
  
  function displayChatbotWelcome(lang = 'fr', theme = 'light', isConnected = false) {
    const chatbox = document.getElementById('chatbox');
    chatbox.innerHTML = '';
  
    const msg = document.createElement('div');
    msg.className = 'message bot';
    msg.innerText = isConnected ? chatbotMessages[lang].welcomeConnected : chatbotMessages[lang].welcomeDisconnected;
    msg.style.color = 'white';
    chatbox.appendChild(msg);
  
    if (!isConnected) {
      const btnContainer = document.createElement('div');
      btnContainer.className = 'chatbot-button-container';
  
      const loginBtn = document.createElement('a');
      loginBtn.href = 'traitement/routeur/routeur.php?page=connexion';
      loginBtn.textContent = chatbotMessages[lang].loginBtn;
      loginBtn.className = 'chatbot-button';
  
      const signupBtn = document.createElement('a');
      signupBtn.href = 'traitement/routeur/routeur.php?page=inscription';
      signupBtn.textContent = chatbotMessages[lang].signupBtn;
      signupBtn.className = 'chatbot-button';
  
      btnContainer.appendChild(loginBtn);
      btnContainer.appendChild(signupBtn);
      chatbox.appendChild(btnContainer);
    }
  
    applyChatbotTheme(theme);
    chatbox.scrollTop = chatbox.scrollHeight;
  }
  
  function display404Message(lang = 'fr', theme = 'light') {
    const chatbox = document.getElementById('chatbox');
    chatbox.innerHTML = '';
  
    const msg = document.createElement('div');
    msg.className = 'message bot';
    msg.innerText = chatbotMessages[lang].notFound;
    msg.style.color = 'white';
    chatbox.appendChild(msg);
  
    const returnBtn = document.createElement('a');
    returnBtn.href = 'traitement/routeur/routeur.php?page=index';
    returnBtn.textContent = chatbotMessages[lang].backHome;
    returnBtn.className = 'chatbot-button';
    returnBtn.style.marginTop = '15px';
  
    const btnContainer = document.createElement('div');
    btnContainer.className = 'chatbot-button-container';
    btnContainer.appendChild(returnBtn);
  
    chatbox.appendChild(btnContainer);
    applyChatbotTheme(theme);
    chatbox.scrollTop = chatbox.scrollHeight;
  }
  
  function initChatbot() {
    const theme = localStorage.getItem('theme') || 'light';
    const lang = localStorage.getItem('lang') || 'fr';
    const isConnected = localStorage.getItem('user_connected') === 'true';
  
    if (window.location.pathname.includes('404.php')) {
      display404Message(lang, theme);
    } else if (window.location.pathname.includes('index.html') || window.location.pathname.endsWith('/')) {
      displayChatbotWelcome(lang, theme, isConnected);
    }
  }
  
  document.addEventListener('DOMContentLoaded', initChatbot);
  