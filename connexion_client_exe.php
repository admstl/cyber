<?php
session_start();
require_once("info_bdd.php");
require_once("validation.php");

function retour_connexion_client(): void
{
    header("location:index.php?page=connexion_client&erreur=" . urlencode("Echec De Connexion"));
    exit();
}

if ($_SERVER["REQUEST_METHOD"] !== "POST"
    || !isset($_POST["pseudo"], $_POST["mdp"])
    || !is_string($_POST["pseudo"]) || !is_string($_POST["mdp"])) {
    retour_connexion_client();
}

// Assainissement (le mot de passe n'est pas nettoyé : les espaces en font partie)
$pseudo = nettoyer_texte($_POST["pseudo"]);
$mdp = $_POST["mdp"];

// Vérifications avant l'envoi à la base de données
// Message volontairement identique à un échec classique on ne révèle pas la règle
if (!valider_pseudo($pseudo) || $mdp === "" || strlen($mdp) > 128) {
    retour_connexion_client();
}

try {
    $stmt = $mon_pdo->prepare("SELECT idClient, pseudo, mdp FROM client WHERE pseudo = ?");
    $stmt->execute([$pseudo]);
    $resultat = $stmt->fetch(PDO::FETCH_ASSOC);

    $hash = $resultat ? $resultat['mdp'] : hasher_mdp('mot_de_passe_factice');

    if (verifier_mdp($mdp, $hash) && $resultat) {
        session_regenerate_id(true);
        $_SESSION['login'] = $resultat['pseudo'];
        $_SESSION['idClient'] = $resultat['idClient'];
        $_SESSION['role'] = 'client';
        header("location:index.php?page=accueil");
        exit();
    }
} catch (Throwable $e) {
    error_log("Connexion client impossible : " . $e->getMessage());
}

retour_connexion_client();