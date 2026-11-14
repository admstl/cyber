// Vérifie qu'un mail a un format valide
function mailValide(mail) {
    let regexMail = /^[A-Za-z0-9._%+-]+@[A-Za-z0-9-]+(\.[A-Za-z0-9-]+)*\.[A-Za-z]{2,}$/;
    return mail.length <= 100 && regexMail.test(mail);
}

// Vérifie qu'un numéro de téléphone français est valide
function telephoneValide(telephone) {
    // On enlève les espaces, points, tirets et parenthèses avant de vérifier
    let numero = telephone.replace(/[\s.\-()]/g, "");
    let regexTel = /^(0|\+33|0033)[1-9][0-9]{8}$/;
    return regexTel.test(numero);
}

// Vérifie les coordonnées du formulaire d'inscription
// Retourne true si tout est correct (le formulaire est envoyé) false sinon (l'envoi est bloqué)
function verifierCoordonnees() {
    let nom = document.getElementById("nom").value.trim();
    let prenom = document.getElementById("prenom").value.trim();
    let mail = document.getElementById("mail").value.trim();
    let telephone = document.getElementById("telephone").value.trim();
    let message = document.getElementById("messageErreur");

    // On vide le message précédent
    message.textContent = "";

    // Nom et prénom lettres, espace, tiret ou apostrophe 2 à 50 caractères
    let regexNom = /^[A-Za-zÀ-ÖØ-öø-ÿ][A-Za-zÀ-ÖØ-öø-ÿ'’ -]{1,49}$/;
    if (!regexNom.test(nom)) {
        message.textContent = "Le nom n'est pas valide (lettres, espace, tiret ou apostrophe, 2 à 50 caractères)";
        return false;
    }
    if (!regexNom.test(prenom)) {
        message.textContent = "Le prénom n'est pas valide (lettres, espace, tiret ou apostrophe, 2 à 50 caractères)";
        return false;
    }

    // Le mail est facultatif on ne le vérifie que s'il est rempli
    if (mail !== "" && !mailValide(mail)) {
        message.textContent = "L'adresse e-mail n'est pas valide";
        return false;
    }

    // Le téléphone est facultatif on ne le vérifie que s'il est rempli
    if (telephone !== "" && !telephoneValide(telephone)) {
        message.textContent = "Le numéro de téléphone n'est pas valide (format français attendu)";
        return false;
    }

    return true;
}

// Vérifie le mot de passe 12 caractères minimum, une minuscule, une majuscule, un chiffre, un caractère spécial et les deux saisies identiques
function verifierMotDePasse() {
    let pseudo = document.getElementById("pseudo").value.trim();
    let mdp = document.getElementById("mdp").value;
    let mdp2 = document.getElementById("mdp2").value;
    let message = document.getElementById("messageErreur");

    message.textContent = "";

    if (mdp.length < 12) {
        message.textContent = "Le mot de passe doit contenir au moins 12 caractères";
    } else if (!/[a-z]/.test(mdp)) {
        message.textContent = "Le mot de passe doit contenir une minuscule";
    } else if (!/[A-Z]/.test(mdp)) {
        message.textContent = "Le mot de passe doit contenir une majuscule";
    } else if (!/[0-9]/.test(mdp)) {
        message.textContent = "Le mot de passe doit contenir un chiffre";
    } else if (!/[^A-Za-z0-9]/.test(mdp)) {
        message.textContent = "Le mot de passe doit contenir un caractère spécial";
    } else if (pseudo !== "" && mdp.toLowerCase().includes(pseudo.toLowerCase())) {
        message.textContent = "Le mot de passe ne doit pas contenir le pseudo";
    } else if (mdp !== mdp2) {
        message.textContent = "Les deux mots de passe ne sont pas identiques";
    }

    // true si aucun message d'erreur n'a été affiché
    return message.textContent === "";
}
