let questions = [];
let theme = document.title.replace("Quiz ", "").toLowerCase();

fetch("./api/questions.php?theme=" + theme)
  .then(function(res) {
    return res.json();
  })
  .then(function(data) {
    questions = data;
    afficherQuestion();
  })
  .catch(function() {
    document.getElementById("quizz").innerHTML = "Erreur lors du chargement du quiz.";
  });

let q = 0;
let t = 10;
let chrono;
let score = 0;

function verifierReponse(reponse) {
  clearInterval(chrono);
  let bonneReponse = questions[q].answer;
  if (reponse === bonneReponse) {
    alert("Correct !");
    score += 10;
  } else {
    alert("Miss !");
  }
  passerQuestion();
}

function majBarreTemps() {
  const barre = document.getElementById("remplissage_chrono");
  if (barre) {
    barre.style.width = (t * 10) + "%";
  }
}

function afficherQuestion() {
  const zone = document.getElementById("quizz");
  zone.innerHTML = "";

  const titre = document.createElement("h1");
  titre.textContent = theme;
  zone.appendChild(titre);

  const quest = questions[q];

  const texteQuestion = document.createElement("p");
  texteQuestion.textContent = quest.question;
  zone.appendChild(texteQuestion);

  const conteneurBarre = document.createElement("div");
  conteneurBarre.className = "barre_chrono";

  const remplissage = document.createElement("div");
  remplissage.className = "remplissage_chrono";
  remplissage.id = "remplissage_chrono";

  conteneurBarre.appendChild(remplissage);
  zone.appendChild(conteneurBarre);

  for (let i = 0; i < quest.options.length; i++) {
    const bouton = document.createElement("button");
    bouton.textContent = quest.options[i];
    bouton.className = "bouton_reponse bouton_option";
    bouton.addEventListener("click", function() {
      verifierReponse(quest.options[i]);
    });
    zone.appendChild(bouton);
  }

  t = 10;
  majBarreTemps();

  if (chrono) {
    clearInterval(chrono);
  }

  chrono = setInterval(function() {
    t--;
    majBarreTemps();
    if (t < 0) {
      clearInterval(chrono);
      alert("Temps écoulé");
      passerQuestion();
    }
  }, 1000);
}

function passerQuestion() {
  q++;
  if (q < questions.length) {
    afficherQuestion();
  } else {
    document.getElementById("quizz").innerHTML =
      "<h2>Fin du quizz GG !</h2><p>Ton score : <strong>" + score + " points</strong></p>";
    envoyerScoreEnBase(score, theme);
  }
}

function envoyerScoreEnBase(scoreFinal, themeActuel) {
  fetch("./api/enregistrer_score.php", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({
      score: scoreFinal,
      theme: themeActuel
    })
  });
}

window.onload = afficherQuestion;





