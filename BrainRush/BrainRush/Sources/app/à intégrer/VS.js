function initialiserCarrousel(piste) {
  let element = document.getElementById(piste);
  if (!element) return;

  let enfants = Array.from(element.children);
  enfants.forEach((item) => {
    let clone1 = item.cloneNode(true);
    let clone2 = item.cloneNode(true);
    element.insertBefore(clone1, element.firstChild);
    element.appendChild(clone2);});


  setTimeout(() => {
    element.scrollLeft = element.scrollWidth / 3;
  }, 5);

  element.addEventListener("scroll", () => {
    let total = element.scrollWidth;
    let pos = element.scrollLeft;
    let unTiers = total / 3;

    if (pos < 1) {
      element.scrollLeft = unTiers;
    } else if (pos >= unTiers * 2) {
      element.scrollLeft = unTiers;
    }
  });}

function scrollgauche(id) {
  let zone = document.getElementById(id);
  if (zone) {
    zone.scrollBy({ left: -200, behavior: "smooth" });
  }}

function scrolldroit(id) {
  let zone = document.getElementById(id);
  if (zone) {
    zone.scrollBy({ left: 200, behavior: "smooth" });
  }}

document.addEventListener("DOMContentLoaded", () => {
  initialiserCarrousel("recommender");
  initialiserCarrousel("Populaires");
  initialiserCarrousel("communauté");
});
