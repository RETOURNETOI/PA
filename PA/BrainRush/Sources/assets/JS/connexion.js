document.addEventListener('DOMContentLoaded', function() {
    const navbarToggle = document.getElementById('navbar-toggle');
    const navbarMenu = document.getElementById('navbar-menu');
    
    if (navbarToggle && navbarMenu) {
        navbarToggle.addEventListener('click', function() {
            navbarMenu.classList.toggle('show');
        });
    }

    let moveInterval;
    let isMoving = false;
    
    const evilButton = document.getElementById('evil-button');
    const loginForm = document.getElementById('loginForm');
    const emailInput = document.getElementById('emailInput');
    const passwordInput = document.getElementById('passwordInput');
    
    if (evilButton && loginForm && emailInput && passwordInput) {
        
        function areFieldsFilled() {
            return emailInput.value.trim() !== '' && passwordInput.value.trim() !== '';
        }
        
        function moveButtonRandomly() {
            const viewportWidth = window.innerWidth;
            const viewportHeight = window.innerHeight;
            const buttonWidth = evilButton.offsetWidth;
            const buttonHeight = evilButton.offsetHeight;
            
            const maxX = viewportWidth - buttonWidth - 20;
            const maxY = viewportHeight - buttonHeight - 20;
            const minX = 20;
            const minY = 20;
            
            const randomX = Math.random() * (maxX - minX) + minX;
            const randomY = Math.random() * (maxY - minY) + minY;
            
            evilButton.style.left = randomX + 'px';
            evilButton.style.top = randomY + 'px';
            evilButton.style.transform = 'none';
        }
        
        function startMoving() {
            if (!isMoving) {
                isMoving = true;
                evilButton.style.position = 'fixed';
                evilButton.style.zIndex = '1000';
                evilButton.style.transition = 'all 0.3s ease-out';
                
                moveButtonRandomly();
                moveInterval = setInterval(moveButtonRandomly, 800);
            }
        }
        
        function stopMoving() {
            if (isMoving) {
                isMoving = false;
                clearInterval(moveInterval);
                
                evilButton.style.transition = 'all 0.6s cubic-bezier(0.25, 0.46, 0.45, 0.94)';
                
                const loginContainer = document.querySelector('.login-container');
                if (loginContainer) {
                    const containerRect = loginContainer.getBoundingClientRect();
                    const containerBottom = containerRect.bottom;
                    const containerCenterX = containerRect.left + (containerRect.width / 2);
                    
                    evilButton.style.left = containerCenterX + 'px';
                    evilButton.style.top = (containerBottom + 30) + 'px';
                    evilButton.style.transform = 'translateX(-50%)';
                    
                    evilButton.style.boxShadow = '0 8px 25px rgba(79, 70, 229, 0.4)';
                    evilButton.style.background = 'linear-gradient(135deg, #10b981, #059669)';
                    
                    if (document.body.classList.contains('dark-mode')) {
                        evilButton.style.background = 'linear-gradient(135deg, #059669, #047857)';
                        evilButton.style.boxShadow = '0 8px 25px rgba(5, 150, 105, 0.4)';
                    }
                } else {
                    evilButton.style.left = '50%';
                    evilButton.style.top = '70%';
                    evilButton.style.transform = 'translate(-50%, -50%)';
                }
            }
        }
        
        if (!areFieldsFilled()) {
            startMoving();
        }
        
        function checkFields() {
            if (areFieldsFilled()) {
                stopMoving();
                evilButton.style.cursor = 'pointer';
                evilButton.style.opacity = '1';
                
                evilButton.style.animation = 'gentlePulse 2s infinite';
                
                if (!document.querySelector('#pulse-animation')) {
                    const style = document.createElement('style');
                    style.id = 'pulse-animation';
                    style.textContent = `
                        @keyframes gentlePulse {
                            0%, 100% { 
                                transform: translateX(-50%) scale(1);
                            }
                            50% { 
                                transform: translateX(-50%) scale(1.05);
                            }
                        }
                    `;
                    document.head.appendChild(style);
                }
            } else {
                startMoving();
                evilButton.style.cursor = 'not-allowed';
                evilButton.style.opacity = '0.8';
                evilButton.style.animation = 'none';
               
                evilButton.style.background = 'linear-gradient(135deg, var(--connexion-primary), var(--connexion-secondary))';
                evilButton.style.boxShadow = '0 4px 15px rgba(79, 70, 229, 0.3)';
                
                if (document.body.classList.contains('dark-mode')) {
                    evilButton.style.background = 'linear-gradient(135deg, var(--connexion-dark-orange), var(--connexion-light-orange))';
                    evilButton.style.boxShadow = '0 4px 15px rgba(194, 65, 12, 0.3)';
                }
            }
        }
        
        emailInput.addEventListener('input', checkFields);
        passwordInput.addEventListener('input', checkFields);
        emailInput.addEventListener('blur', checkFields);
        passwordInput.addEventListener('blur', checkFields);
        
        evilButton.addEventListener('click', function(e) {
            e.preventDefault();
            
            if (!areFieldsFilled()) {
                clearInterval(moveInterval);
                evilButton.style.transition = 'all 0.15s ease-out'; 
                moveButtonRandomly();
                moveInterval = setInterval(moveButtonRandomly, 400); 
                
                const messages = [
                    "Remplissez d'abord les champs ! 😏",
                    "Pas si vite ! 🏃‍♂️💨",
                    "Attrapez-moi si vous pouvez ! 😅",
                    "Trop lent ! ⚡",
                    "Vous devez être plus rapide ! 🚀",
                    "Nope ! 🙅‍♂️",
                    "Essayez encore ! 🎯"
                ];
                const randomMessage = messages[Math.floor(Math.random() * messages.length)];
                
                showNotification(randomMessage);
                return;
            }
            
            stopMoving();
            loginForm.submit();
        });
        
        
        evilButton.addEventListener('mouseenter', function() {
            if (!areFieldsFilled()) {
                clearInterval(moveInterval);
                evilButton.style.transition = 'all 0.1s ease-out'; 
                moveButtonRandomly();
                moveInterval = setInterval(moveButtonRandomly, 300); 
            }
        });
        
        evilButton.addEventListener('mouseleave', function() {
            if (!areFieldsFilled()) {
                clearInterval(moveInterval);
                evilButton.style.transition = 'all 0.3s ease-out';
                moveInterval = setInterval(moveButtonRandomly, 800);
            }
        });
        
        window.addEventListener('resize', function() {
            if (isMoving) {
                moveButtonRandomly();
            } else if (areFieldsFilled()) {
                setTimeout(() => {
                    const loginContainer = document.querySelector('.login-container');
                    if (loginContainer) {
                        const containerRect = loginContainer.getBoundingClientRect();
                        const containerBottom = containerRect.bottom;
                        const containerCenterX = containerRect.left + (containerRect.width / 2);
                        
                        evilButton.style.left = containerCenterX + 'px';
                        evilButton.style.top = (containerBottom + 30) + 'px';
                    }
                }, 100);
            }
        });
    }
    
    function showNotification(message) {
        const existingNotif = document.querySelector('.temp-notification');
        if (existingNotif) {
            existingNotif.remove();
        }
        
        const notification = document.createElement('div');
        notification.className = 'temp-notification';
        notification.textContent = message;
        notification.style.cssText = `
            position: fixed;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            background: #ff6b6b;
            color: white;
            padding: 12px 24px;
            border-radius: 8px;
            font-weight: 500;
            z-index: 9999;
            animation: slideDown 0.3s ease-out;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        `;
        
        if (!document.querySelector('#notification-styles')) {
            const style = document.createElement('style');
            style.id = 'notification-styles';
            style.textContent = `
                @keyframes slideDown {
                    from {
                        opacity: 0;
                        transform: translateX(-50%) translateY(-20px);
                    }
                    to {
                        opacity: 1;
                        transform: translateX(-50%) translateY(0);
                    }
                }
            `;
            document.head.appendChild(style);
        }
        
        document.body.appendChild(notification);
        
        setTimeout(() => {
            if (notification.parentNode) {
                notification.style.animation = 'slideDown 0.3s ease-out reverse';
                setTimeout(() => notification.remove(), 300);
            }
        }, 3000);
    }

    const themeToggle = document.getElementById('themeToggle');
    if (themeToggle) {
        themeToggle.addEventListener('click', function() {
            document.body.classList.toggle('dark-mode');
            
            if (document.body.classList.contains('dark-mode')) {
                this.textContent = '☀️';
            } else {
                this.textContent = '🌙';
            }
        });
    }

    try {
        const savedTheme = localStorage.getItem('theme');
        if (savedTheme === 'dark') {
            document.body.classList.add('dark-mode');
            if (themeToggle) themeToggle.textContent = '☀️';
        }
    } catch(e) {
        console.log('localStorage non disponible');
    }

    const langToggle = document.getElementById('langToggle');
    const langIcon = document.getElementById('langIcon');
    
    if (langToggle && langIcon) {
        langToggle.addEventListener('click', function() {
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

        if (titleElement) titleElement.textContent = t.title;
        if (emailLabel) emailLabel.textContent = t.emailLabel;
        if (passwordLabel) passwordLabel.textContent = t.passwordLabel;
        if (emailInput) emailInput.placeholder = t.emailPlaceholder;
        if (passwordInput) passwordInput.placeholder = t.passwordPlaceholder;
        if (signupLink) signupLink.textContent = t.signupLink;
        if (evilButton) evilButton.textContent = t.buttonText;
    }
}

const captchaPopup = document.getElementById('captcha-popup');
const closeCaptcha = document.querySelector('.close-captcha');
const captchaForm = document.querySelector('.captcha-popup .input-area');
let isCaptchaValid = false;

function showCaptcha() {
  captchaPopup.style.display = 'block';
  document.body.style.overflow = 'hidden';
}

function hideCaptcha() {
  captchaPopup.style.display = 'none';
  document.body.style.overflow = 'auto';
}

closeCaptcha.addEventListener('click', hideCaptcha);

captchaPopup.addEventListener('click', function(e) {
  if (e.target === captchaPopup) {
    hideCaptcha();
  }
});

evilButton.addEventListener('click', function(e) {
  e.preventDefault();
  
  if (!areFieldsFilled()) {
    return;
  }
  
  if (!isCaptchaValid) {
    showCaptcha();
    return;
  }
  
  loginForm.submit();
});

document.querySelector('.inscription-form').addEventListener('submit', function(e) {
  e.preventDefault();
  
  if (!isCaptchaValid) {
    showCaptcha();
    return;
  }
  
  this.submit();
});

captchaForm.addEventListener('submit', function(e) {
  e.preventDefault();
  const inputVal = this.querySelector('input').value.split('').join(' ');
  const captchaText = document.querySelector('.captcha-popup .captcha').innerText;
  
  if (inputVal === captchaText) {
    document.querySelector('.captcha-popup .status-text').style.display = "block";
    document.querySelector('.captcha-popup .status-text').style.color = "#06c59c";
    document.querySelector('.captcha-popup .status-text').innerText = "Vérification réussie!";
    
    isCaptchaValid = true;
    setTimeout(() => {
      hideCaptcha();
      if (document.getElementById('loginForm')) {
        document.getElementById('loginForm').submit();
      } else if (document.querySelector('.inscription-form')) {
        document.querySelector('.inscription-form').submit();
      }
    }, 1000);
  } else {
    document.querySelector('.captcha-popup .status-text').style.display = "block";
    document.querySelector('.captcha-popup .status-text').style.color = "#ff0000";
    document.querySelector('.captcha-popup .status-text').innerText = "CAPTCHA incorrect. Veuillez réessayer!";
  }
});