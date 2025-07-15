// main.js - Version simplifiée pour éviter les conflits
document.addEventListener("DOMContentLoaded", () => {
  console.log('main.js loaded');
});
// Fonctions utilitaires communes
function toggleMenu() {
    const nav = document.querySelector('.main-nav ul');
    nav.classList.toggle('active');
}

// Gestion des messages flash
document.addEventListener('DOMContentLoaded', function() {
    const flashMessages = document.querySelectorAll('.flash-message');
    flashMessages.forEach(msg => {
        setTimeout(() => {
            msg.style.display = 'none';
        }, 5000);
    });
});