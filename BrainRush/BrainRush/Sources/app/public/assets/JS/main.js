document.addEventListener("DOMContentLoaded", () => {
    initializeTheme();
    initializeLanguage();
    initializeFlashMessages();
    initializeNavigation();
});

function initializeTheme() {
    const themeToggle = document.getElementById('themeToggle');
    const savedTheme = localStorage.getItem('theme') || 'light';
    
    applyTheme(savedTheme);
    
    if (themeToggle) {
        themeToggle.addEventListener('click', toggleTheme);
    }
}

function initializeLanguage() {
    const langToggle = document.getElementById('langToggle');
    const savedLang = localStorage.getItem('lang') || 'fr';
    
    updateLanguageButton(savedLang);
    
    if (langToggle) {
        langToggle.addEventListener('click', toggleLanguage);
    }
}

function initializeFlashMessages() {
    const flashMessages = document.querySelectorAll('.alert');
    flashMessages.forEach(msg => {
        setTimeout(() => {
            msg.style.opacity = '0';
            setTimeout(() => msg.remove(), 300);
        }, 5000);
    });
}

function initializeNavigation() {
    const navbarToggle = document.getElementById('navbar-toggle');
    const navbarMenu = document.getElementById('navbar-menu');
    
    if (navbarToggle && navbarMenu) {
        navbarToggle.addEventListener('click', () => {
            navbarMenu.classList.toggle('show');
        });
    }
}

function applyTheme(theme) {
    const body = document.body;
    const nav = document.querySelector('.custom-navbar');
    const themeToggle = document.getElementById('themeToggle');

    body.classList.remove('dark-mode');

    if (theme === 'dark') {
        body.classList.add('dark-mode');
        if (themeToggle) themeToggle.textContent = '☀️';
    } else {
        if (themeToggle) themeToggle.textContent = '🌙';
    }

    localStorage.setItem('theme', theme);
}

function toggleTheme() {
    const currentTheme = localStorage.getItem('theme') || 'light';
    const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
    applyTheme(newTheme);
}

function toggleLanguage() {
    const currentLang = localStorage.getItem('lang') || 'fr';
    const newLang = currentLang === 'fr' ? 'en' : 'fr';
    
    localStorage.setItem('lang', newLang);
    updateLanguageButton(newLang);
    
    if (typeof translatePage === 'function') {
        translatePage(newLang);
    }
}

function updateLanguageButton(lang) {
    const langIcon = document.getElementById('langIcon');
    if (langIcon) {
        if (lang === 'fr') {
            langIcon.textContent = '🇬🇧';
        } else {
            langIcon.textContent = '🇫🇷';
        }
    }
}

function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `alert alert-${type}`;
    notification.textContent = message;
    notification.style.cssText = `
        position: fixed;
        top: 100px;
        right: 20px;
        z-index: 9999;
        min-width: 300px;
        animation: slideIn 0.3s ease;
    `;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.style.animation = 'slideOut 0.3s ease';
        setTimeout(() => notification.remove(), 300);
    }, 3000);
}

const style = document.createElement('style');
style.textContent = `
    @keyframes slideIn {
        from { transform: translateX(100%); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }
    @keyframes slideOut {
        from { transform: translateX(0); opacity: 1; }
        to { transform: translateX(100%); opacity: 0; }
    }
`;
document.head.appendChild(style);