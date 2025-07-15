let iconeOeil = document.getElementById("icone-oeil");
let champMotDePasse = document.getElementById("motdepasse");

iconeOeil.onclick = function () {
    if (champMotDePasse.type === "password") {
        champMotDePasse.type = "text";
        iconeOeil.src = "./images/eye-open.png";
    } else {
        champMotDePasse.type = "password";
        iconeOeil.src = "./images/eye-close.png";
    }
};
function verifierEtMontrerPhoto() {
    let champ = document.getElementById('photo');
    let apercu = document.getElementById('aperçuPhoto');
    let fichier = champ.files[0];
    if (!fichier) return;

    if (fichier.type !== "image/jpeg" && fichier.type !== "image/jpg") {
        alert("Seuls les fichiers JPG sont autorisés");
        champ.value = "";
        apercu.style.display = "none";
        return;
    }

    let lecteur = new FileReader();
    lecteur.onload = function (e) {
        apercu.src = e.target.result;
        apercu.style.display = "block";
    };
    lecteur.readAsDataURL(fichier);
}

let caracteresCaptcha = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
let tailleCaptcha = 6;
let captchaActuel = "";
let captchaValide = false;
let dernierePhoto = "";

function creerCaptcha() {
    let c = "";
    for (let i = 0; i < tailleCaptcha; i++) {
        let alea = Math.floor(Math.random() * caracteresCaptcha.length);
        c += caracteresCaptcha.charAt(alea);
    }
    return c;
}

function montrerCaptcha() {
    captchaActuel = creerCaptcha();
    document.querySelector('.captcha').innerText = captchaActuel.split('').join(' ');
    document.getElementById('zone-captcha').style.display = "block";
}

document.getElementById('photo').addEventListener('change', function () {
    let champ = document.getElementById('photo');
    let fichier = champ.files[0];
    if (!fichier) return;

    if (fichier.name !== dernierePhoto) {
        dernierePhoto = fichier.name;
        captchaActuel = "";
        captchaValide = false;
        document.getElementById('champCaptcha').value = "";
        document.getElementById('etatCaptcha').innerText = "";
        document.getElementById('aperçuPhoto').style.display = "none";

        verifierEtMontrerPhoto();
        montrerCaptcha();
    }});
document.getElementById('btnVerifierCaptcha').addEventListener('click', function () {
    let entree = document.getElementById('champCaptcha').value.trim();
    if (entree === captchaActuel) {
        captchaValide = true;
        document.getElementById("etatCaptcha").style.color = "green";
        document.getElementById("etatCaptcha").innerText = "Captcha validé";
        document.getElementById("zone-captcha").style.display = "none";
    } else {
        captchaValide = false;
        document.getElementById("etatCaptcha").style.color = "red";
        document.getElementById("etatCaptcha").innerText = "Raté. Réessaie.";
    }});

document.querySelector('.captcha').addEventListener('contextmenu', function (e) {
    e.preventDefault();
});
document.querySelector('.captcha').addEventListener('copy', function (e) {
    e.preventDefault();
});
function soumettreFormulaire() {
    if (!captchaValide) {
        alert("Tu dois valider le captcha d'abord !");
        return;
    }

    let form = document.getElementById('formulaireProfil');
    form.submit();
}



