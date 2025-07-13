function initializeInfiniteCarousel(id) {
  const carousel = document.getElementById(id);
  const items = [...carousel.children];

  items.forEach(item => {
    const before = item.cloneNode(true);
    const after = item.cloneNode(true);
    carousel.insertBefore(before, carousel.firstChild);
    carousel.appendChild(after);
  });

  setTimeout(() => {
    carousel.scrollLeft = carousel.scrollWidth / 3;
  }, 0);


  carousel.addEventListener('scroll', () => {
    const maxScroll = carousel.scrollWidth;
    const currentScroll = carousel.scrollLeft;
    const third = maxScroll / 3;

    if (currentScroll <= 0) {
      carousel.scrollLeft = third;
    } else if (currentScroll >= third * 2) {
      carousel.scrollLeft = third;
    }
  });
}


function scrollgauche(id) {
  const carousel = document.getElementById(id);
  carousel.scrollBy({ left: -200, behavior: 'smooth' });
}

function scrolldroit(id) {
  const carousel = document.getElementById(id);
  carousel.scrollBy({ left: 200, behavior: 'smooth' });
}

window.addEventListener('DOMContentLoaded', () => {
  ['recommender', 'populaire', 'communautÃ©'].forEach(id => {
    initializeInfiniteCarousel(id);
  });
});