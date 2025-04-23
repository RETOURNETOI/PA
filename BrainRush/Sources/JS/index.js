document.addEventListener('DOMContentLoaded', () => {
    const cards = document.querySelectorAll('.card');
  
    cards.forEach(card => {
      card.addEventListener('mouseenter', () => {
        card.classList.add('shadow-lg');
      });
  
      card.addEventListener('mouseleave', () => {
        card.classList.remove('shadow-lg');
      });
    });
  
    // Mini effet "pop" quand on clique sur les boutons
    const buttons = document.querySelectorAll('.btn');
  
    buttons.forEach(btn => {
      btn.addEventListener('click', () => {
        btn.classList.add('animate__animated', 'animate__pulse');
        setTimeout(() => {
          btn.classList.remove('animate__animated', 'animate__pulse');
        }, 700);
      });
    });
  });  