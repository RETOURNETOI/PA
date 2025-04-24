const evilButton = document.getElementById('evil-button');
const OFFSET = 100;
const SPEED = 300;

let isFormValid = false;

// Met à jour le texte du bouton
evilButton.textContent = 'Connexion';

// Fonction pour déplacer le bouton
document.addEventListener('mousemove', (e) => {
  if (isFormValid) return; // Si le formulaire est rempli, ne pas bouger

  const x = e.pageX;
  const y = e.pageY;
  const buttonBox = evilButton.getBoundingClientRect();

  const horizontalDistanceFrom = distanceFromCenter(buttonBox.x, x, buttonBox.width);
  const verticalDistanceFrom = distanceFromCenter(buttonBox.y, y, buttonBox.height);
  const horizontalOffset = buttonBox.width / 2 + OFFSET;
  const verticalOffset = buttonBox.height / 2 + OFFSET;

  if (Math.abs(horizontalDistanceFrom) <= horizontalOffset && Math.abs(verticalDistanceFrom) <= verticalOffset) {
    setButtonPosition(
      buttonBox.x + horizontalOffset / horizontalDistanceFrom * SPEED,
      buttonBox.y + verticalOffset / verticalDistanceFrom * SPEED
    );
  }
});

// Repositionne le bouton au centre
function moveButtonToCenter() {
    const formBox = document.querySelector('.login-container').getBoundingClientRect();
    const buttonBox = evilButton.getBoundingClientRect();
  
    // Centrage horizontal par rapport au bloc
    const centerX = formBox.left + formBox.width / 2 - buttonBox.width / 2;
  
    // Centrage vertical sous le bloc MAIS contraint dans la fenêtre
    const targetY = Math.min(
      formBox.bottom + 24,
      window.innerHeight - buttonBox.height - 24
    );
  
    evilButton.style.transition = 'left 0.3s ease, top 0.3s ease';
    evilButton.style.left = `${centerX}px`;
    evilButton.style.top = `${targetY}px`;
}
  

// Vérifie les champs du formulaire
document.querySelector('form').addEventListener('input', () => {
  const email = document.querySelector('input[name="email"]').value.trim();
  const password = document.querySelector('input[name="password"]').value.trim();

  if (email !== '' && password !== '') {
    isFormValid = true;
    moveButtonToCenter();
  } else {
    isFormValid = false;
  }
});

// Action quand on clique dessus
evilButton.addEventListener('click', () => {
  if (!isFormValid) {
    alert('Remplis le formulaire d\'abord 😜');
    return;
  }

  alert('Connexion réussie ✅');
  // Tu peux ici appeler form.submit() ou rediriger l’utilisateur
});

// Aide à calculer la distance
function distanceFromCenter(boxPosition, mousePosition, boxSize) {
  return boxPosition - mousePosition + boxSize / 2;
}

function setButtonPosition(left, top) {
  const windowBox = document.body.getBoundingClientRect();
  const buttonBox = evilButton.getBoundingClientRect();

  if (distanceFromCenter(left, windowBox.left, buttonBox.width) < 0) {
    left = windowBox.right - buttonBox.width - OFFSET;
  }
  if (distanceFromCenter(left, windowBox.right, buttonBox.width) > 0) {
    left = windowBox.left + OFFSET;
  }

  if (distanceFromCenter(top, windowBox.top, buttonBox.height) < 0) {
    top = windowBox.bottom - buttonBox.height - OFFSET;
  }
  if (distanceFromCenter(top, windowBox.bottom, buttonBox.height) > 0) {
    top = windowBox.top + OFFSET;
  }

  evilButton.style.left = `${left}px`;
  evilButton.style.top = `${top}px`;
}