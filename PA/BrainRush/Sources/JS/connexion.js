// ✅ Connexion.js avec dark mode, langue, sécurité du bouton, toute la phrase d'inscription cliquable et avatar ajouté dans le header

const evilButton = document.getElementById('evil-button');
const OFFSET = 100;
const SPEED = 300;
let isFormValid = false;
let lang = 'fr';

const translations = {
  fr: {
    title: "🔐 Connexion",
    email: "Adresse email",
    password: "Mot de passe",
    button: "Connexion",
    signupLine: "<a href=\"traitement_index.php?page=inscription\">Vous n'avez pas de compte ? Inscrivez-vous</a>"
  },
  en: {
    title: "🔐 Login",
    email: "Email address",
    password: "Password",
    button: "Login",
    signupLine: "<a href=\"traitement_index.php?page=inscription\">Don't have an account? Sign up</a>"
  }
};

const title = document.getElementById("title");
const emailInput = document.getElementById("emailInput");
const passwordInput = document.getElementById("passwordInput");
const signupLine = document.getElementById("signupLine");

const langToggle = document.getElementById('langToggle');
if (langToggle) {
  langToggle.addEventListener('click', () => {
    lang = lang === 'fr' ? 'en' : 'fr';
    const t = translations[lang];
    title.textContent = t.title;
    emailInput.placeholder = t.email;
    passwordInput.placeholder = t.password;
    evilButton.textContent = t.button;
    signupLine.innerHTML = t.signupLine;
    langToggle.querySelector('img').src = lang === 'fr' ? 'assets/drapeau_fr.png' : 'assets/drapeau_en.png';
  });
}

const themeToggle = document.getElementById("themeToggle");
const body = document.body;
const nav = document.querySelector("nav.navbar");
const container = document.querySelector(".login-container");

function applyTheme(theme) {
  if (theme === "dark") {
    body.classList.add("dark-mode");
    body.classList.remove("light-background");
    if (nav) nav.style.background = "linear-gradient(to right, #DA7B27, #D7572B)";
    if (container) {
      container.style.backgroundColor = "#384454";
      container.style.color = "#fff";
    }
    evilButton.style.backgroundColor = "#DA7B27";
  } else {
    body.classList.remove("dark-mode");
    body.classList.add("light-background");
    if (nav) nav.style.background = "linear-gradient(to right, #007bff, #6610f2)";
    if (container) {
      container.style.backgroundColor = "white";
      container.style.color = "#222";
    }
    evilButton.style.backgroundColor = "#007bff";
  }
}

let currentTheme = localStorage.getItem("theme") || "light";
applyTheme(currentTheme);

if (themeToggle) {
  themeToggle.addEventListener("click", () => {
    currentTheme = currentTheme === "dark" ? "light" : "dark";
    localStorage.setItem("theme", currentTheme);
    applyTheme(currentTheme);
  });
}

function distanceFromCenter(boxPosition, mousePosition, boxSize) {
  return boxPosition - mousePosition + boxSize / 2;
}

function setButtonPosition(left, top) {
  const maxLeft = window.innerWidth - evilButton.offsetWidth;
  const maxTop = window.innerHeight - evilButton.offsetHeight;

  left = Math.max(0, Math.min(left, maxLeft));
  top = Math.max(0, Math.min(top, maxTop));

  evilButton.style.left = `${left}px`;
  evilButton.style.top = `${top}px`;
  evilButton.style.transform = 'none';
}

document.addEventListener('mousemove', (e) => {
  if (isFormValid) return;

  const x = e.pageX;
  const y = e.pageY;
  const box = evilButton.getBoundingClientRect();

  const dx = distanceFromCenter(box.x, x, box.width);
  const dy = distanceFromCenter(box.y, y, box.height);
  const hx = box.width / 2 + OFFSET;
  const hy = box.height / 2 + OFFSET;

  if (Math.abs(dx) <= hx && Math.abs(dy) <= hy) {
    let newLeft = box.x + hx / dx * SPEED;
    let newTop = box.y + hy / dy * SPEED;
    setButtonPosition(newLeft, newTop);
  }
});

function centerButton() {
  const formBox = document.querySelector('.login-container').getBoundingClientRect();
  const box = evilButton.getBoundingClientRect();

  let centerX = formBox.left + formBox.width / 2 - box.width / 2;
  let targetY = formBox.bottom + 24;

  setButtonPosition(centerX, targetY);
}

document.getElementById('loginForm').addEventListener('input', () => {
  if (emailInput.value.trim() && passwordInput.value.trim()) {
    isFormValid = true;
    centerButton();
  } else {
    isFormValid = false;
  }
});

evilButton.addEventListener('click', () => {
  if (!isFormValid) {
    alert("Remplis le formulaire d'abord 😜");
  } else {
    alert("Connexion réussie ✅");
  }
});
