<?php
session_start();
require_once("info_bdd.php");
require_once("validation.php");

// Renvoie vers le formulaire avec un message d'erreur et arrête le script
function retour_formulaire(string $message): void
{
    header("location:index.php?page=inscription_client&erreur=" . urlencode($message));
    exit();
}

// Cas d'échec la page est ouverte sans passer par le formulaire
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    retour_formulaire("Veuillez remplir le formulaire d'inscription");
}

// Refuse l'inscription si la case de la politique de confidentialité n'est pas cochée
if (empty($_POST["consentement"])) {
    retour_formulaire("Vous devez accepter la politique de confidentialité");
}

// Cas d'échec un champ obligatoire est absent vide ou n'est pas du texte
foreach (["nom", "prenom", "pseudo", "mdp", "mdp2"] as $champ) {
    if (!isset($_POST[$champ]) || !is_string($_POST[$champ]) || $_POST[$champ] === "") {
        retour_formulaire("Veuillez remplir tous les champs obligatoires");
    }
}

// Nettoyage des textes saisis
$nom = nettoyer_texte($_POST["nom"]);
$prenom = nettoyer_texte($_POST["prenom"]);
$pseudo = nettoyer_texte($_POST["pseudo"]);
// Le mot de passe n'est pas nettoyé les espaces font partie du mot de passe
$mdp = $_POST["mdp"];
$mdp2 = $_POST["mdp2"];
// Mail et téléphone sont facultatifs chaîne vide si absents ou si ce n'est pas du texte
$mail = nettoyer_texte(is_string($_POST["mail"] ?? null) ? $_POST["mail"] : "");
$telephone = nettoyer_texte(is_string($_POST["telephone"] ?? null) ? $_POST["telephone"] : "");

// Vérifications on s'arrête à la première erreur et on renvoie un message
if (!valider_pseudo($pseudo)) {
    retour_formulaire("Le pseudo doit contenir 3 à 32 caractères : lettres, chiffres, point, tiret ou underscore");
}
if (!valider_nom($nom)) {
    retour_formulaire("Le nom n'est pas valide (lettres, espace, tiret ou apostrophe, 2 à 50 caractères)");
}
if (!valider_nom($prenom)) {
    retour_formulaire("Le prénom n'est pas valide (lettres, espace, tiret ou apostrophe, 2 à 50 caractères)");
}

// Mail facultatif null s'il est vide sinon on vérifie son format
if ($mail === "") {
    $mail = null;
} elseif (!valider_mail($mail)) {
    retour_formulaire("L'adresse e-mail n'est pas valide");
}

// Téléphone facultatif null s'il est vide sinon on le vérifie et on le met au format 0612345678
if ($telephone === "") {
    $telephone = null;
} else {
    $telephone = normaliser_telephone($telephone);
    if ($telephone === null) {
        retour_formulaire("Le numéro de téléphone n'est pas valide (format français attendu)");
    }
}

// Mot de passe les deux saisies doivent être identiques puis le mot de passe doit être assez fort
if ($mdp !== $mdp2) {
    retour_formulaire("Les deux mots de passe ne sont pas identiques");
}
$erreurMdp = verifier_force_mdp($mdp, $pseudo);
if ($erreurMdp !== null) {
    retour_formulaire($erreurMdp);
}

// Les données sont valides on peut parler à la base de données
try {
    // Vérifie si le pseudo existe déjà dans la table client
    $verifSQL = "SELECT idClient FROM client WHERE pseudo = ?";
    $stmtVerif = $mon_pdo->prepare($verifSQL);
    $stmtVerif->execute([$pseudo]);

    // Pseudo déjà pris retour sur le formulaire avec un message
    if ($stmtVerif->fetch()) {
        retour_formulaire("Ce pseudo est déjà utilisé");
    }
    // Le pseudo reste en clair car il sert à se connecter
    $mdpHache = hasher_mdp($mdp);
    $nomChiffre = chiffrer_donnee($nom, $cle_chiffrement);
    $prenomChiffre = chiffrer_donnee($prenom, $cle_chiffrement);
    // Le mail et le téléphone ne sont chiffrés que s'ils ont été renseignés
    $mailChiffre = $mail !== null ? chiffrer_donnee($mail, $cle_chiffrement) : null;
    $telChiffre = $telephone !== null ? chiffrer_donnee($telephone, $cle_chiffrement) : null;

    // Ajoute le nouveau client dans la table client
    $insertSQL = "INSERT INTO client (nom, prenom, pseudo, mdp, mail, telephone)
                  VALUES (?, ?, ?, ?, ?, ?)";
    $stmtInsert = $mon_pdo->prepare($insertSQL);
    $stmtInsert->execute([$nomChiffre, $prenomChiffre, $pseudo, $mdpHache, $mailChiffre, $telChiffre]);

    // Id du client qui vient d'être créé
    $idClient = $mon_pdo->lastInsertId();
} catch (PDOException $e) {
    // Deux inscriptions simultanées avec le même pseudo la contrainte UNIQUE de la base refuse la deuxième (code 23000)
    if ($e->getCode() === "23000") {
        retour_formulaire("Ce pseudo est déjà utilisé");
    }
    // Autre erreur de base de données le détail va dans les logs l'utilisateur voit un message simple
    error_log("Inscription impossible (base de données) : " . $e->getMessage());
    retour_formulaire("Une erreur est survenue, veuillez réessayer plus tard");
} catch (Throwable $e) {
    // Autre erreur (par exemple le chiffrement) même traitement
    error_log("Inscription impossible : " . $e->getMessage());
    retour_formulaire("Une erreur est survenue, veuillez réessayer plus tard");
}

// Connecte directement le client après son inscription
$_SESSION['login'] = $pseudo;
$_SESSION['idClient'] = $idClient;
$_SESSION['role'] = 'client';

// Redirige vers l'accueil avec un message de réussite
header("location:index.php?page=accueil&succes=Inscription%20reussie");
exit();
