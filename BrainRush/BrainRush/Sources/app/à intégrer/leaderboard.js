document.addEventListener("DOMContentLoaded", function() {

fetch("../traitement/routeur/traitement_leaderboard.php")
.then(function(reponse) {
  return reponse.json();
})
.then(function(donnees) {
var tableau = document.getElementById("corps-tableau");

donnees.forEach(function(joueur, indice) {

var ligne = document.createElement("tr");

ligne.innerHTML = "<td>" + (indice+1) + "</td>" +
"<td>" + joueur.username + "</td>" +
"<td>" + joueur.score + " QP</td>";

tableau.appendChild(ligne);
});

})
.catch(function(erreur) {
console.log("Erreur de chargement :", erreur);
document.getElementById("corps-tableau").innerHTML =
"<tr><td colspan='3'>Erreur de chargement</td></tr>";
});

});

document.getElementById("recherche-joueur").addEventListener("input", function() {
var texte = this.value.toLowerCase();
var lignes = document.querySelectorAll("#corps-tableau tr");

lignes.forEach(function(ligne) {
var nom = ligne.children[1].textContent.toLowerCase();

if (nom.includes(texte)) {
ligne.style.display = "";
}
else {
ligne.style.display = "none";
}
});

});
