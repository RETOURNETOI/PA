// ✅ index.js FINAL avec gestion dynamique du chatbot-icon, du thème et de la langue (index + 404)

function applyTheme(theme) {
  const body = document.body;
  const nav = document.querySelector("nav.navbar");
  const chatbotIcon = document.getElementById('chatbot-icon');

  if (theme === "dark") {
    body.classList.add("dark-mode");
    body.classList.remove("light-background");
    if (nav) nav.style.background = "linear-gradient(to right, #DA7B27, #D7572B)";
    if (chatbotIcon) chatbotIcon.style.background = "linear-gradient(to right, #DA7B27, #D7572B)";
  } else {
    body.classList.remove("dark-mode");
    body.classList.add("light-background");
    if (nav) nav.style.background = "linear-gradient(to right, #007bff, #6610f2)";
    if (chatbotIcon) chatbotIcon.style.background = "linear-gradient(to right, #007bff, #6610f2)";
  }
}

function applyLanguage(lang) {
  const title = document.getElementById("main-title");
  const paragraph = document.getElementById("main-description");

  if (title && paragraph) {
    if (lang === "en") {
      title.innerText = "Welcome to BrainRush";
      paragraph.innerText = "Challenge yourself with quizzes and tournaments!";
    } else {
      title.innerText = "Bienvenue sur BrainRush";
      paragraph.innerText = "Défie-toi avec des quiz et des tournois!";
    }
  }

  const theme = localStorage.getItem("theme") || "light";
  const isConnected = localStorage.getItem("user_connected") === "true";

  if (typeof displayChatbotWelcome === 'function' && window.location.pathname.includes('index')) {
    displayChatbotWelcome(lang, theme, isConnected);
  }
  if (typeof display404Message === 'function' && window.location.pathname.includes('404.php')) {
    display404Message(lang, theme);
  }
}

document.addEventListener("DOMContentLoaded", () => {
  let theme = localStorage.getItem("theme") || "light";
  let lang = localStorage.getItem("lang") || "fr";

  applyTheme(theme);
  applyLanguage(lang);

  const themeToggle = document.getElementById("themeToggle");
  const langToggle = document.getElementById("langToggle");

  if (themeToggle) {
    themeToggle.addEventListener("click", () => {
      theme = theme === "dark" ? "light" : "dark";
      localStorage.setItem("theme", theme);
      applyTheme(theme);
      applyChatbotTheme(theme);
    });
  }

  if (langToggle) {
    langToggle.addEventListener("click", () => {
      lang = lang === "fr" ? "en" : "fr";
      localStorage.setItem("lang", lang);
      applyLanguage(lang);
    });
  }
});