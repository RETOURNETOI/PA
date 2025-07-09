//a remplacer par les questions de base de donnée 
const questions = [
  {
    question: "1 + 1 = ?",
    options: ["2", "3", "1", "0"],
    answer: "2"
  },
];

let indexQuestionActuelle = 0;
let tempsRestant = 10;
let intervalleTimer;

function chargerQuestion() {
  const conteneur = document.getElementById("quizz");
  conteneur.innerHTML = "";

  const titre = document.createElement("h1");
  titre.textContent = "Mathématique";
  conteneur.appendChild(titre);

  const question = questions[indexQuestionActuelle];
  const texteQuestion = document.createElement("p");
  texteQuestion.textContent = question.question;
  conteneur.appendChild(texteQuestion);

  const barreTimer = document.createElement("div");
  barreTimer.className = "timer_bar";

  const remplissageTimer = document.createElement("div");
  remplissageTimer.className = "timer_recharge";
  remplissageTimer.id = "remplissage_timer";

  barreTimer.appendChild(remplissageTimer);
  conteneur.appendChild(barreTimer);

  for (let i = 0; i < question.options.length; i++) {
    const bouton = document.createElement("bouton");
    bouton.className = "bouton_reponse";
    bouton.textContent = question.options[i];

    bouton.addEventListener("click", function () {
      gererReponse(question.options[i]);
    });

    conteneur.appendChild(bouton);
  }
}


  tempsRestant = 10;
  mettreAJourBarreTimer();

  clearInterval(intervalleTimer);
  intervalleTimer = setInterval(() => {
    tempsRestant--;
    mettreAJourBarreTimer();
    if (tempsRestant < 0) {
          mettreAJourBarreTimer();
      clearInterval(intervalleTimer);
    document.getElementById("temps").innerHTML = "<h3>Plus de temps</h3>"
    setTimeout(function() {
    document.getElementById("temps").innerHTML = "Plus deejzafoijoij";
    },3000);
      questionSuivante();
    }
  }, 1000);


function mettreAJourBarreTimer() {
  const barre = document.getElementById("remplissage_timer");
  if (barre) {
    barre.style.width = (tempsRestant * 1) + "%";
  }
}

function gererReponse(reponseChoisie) {
  clearInterval(intervalleTimer);
  const bonneReponse = questions[indexQuestionActuelle].answer;
  alert(reponseChoisie === bonneReponse ? "pas mal" : `rater loserrr`);
  questionSuivante();
}

function questionSuivante() {
  indexQuestionActuelle++;
  if (indexQuestionActuelle < questions.length) {
    chargerQuestion();
  } else {
    document.getElementById("quizz").innerHTML = "<h2>Fin de quizz, GG !</h2>";
  }
}

window.onload = chargerQuestion;