<?php
session_start();
require_once("info_bdd.php");
require_once("validation.php");
// Fonctions d'écriture dans le journal
require_once("journal.php");

// Journalise l'échec avec la raison (visible uniquement dans les logs), puis renvoie l'utilisateur vers le formulaire
// L'utilisateur voit toujours le même message : on ne lui révèle pas la raison
function retour_connexion_client(string $raison, string $pseudo = ""): void
{
    journaliser("WARNING", "CONNEXION_CLIENT", "ECHEC raison=" . $raison . ($pseudo !== "" ? " pseudo=" . $pseudo : ""));
    header("location:index.php?page=connexion_client&erreur=" . urlencode("Echec De Connexion"));
    exit();
}

if ($_SERVER["REQUEST_METHOD"] !== "POST"
    || !isset($_POST["pseudo"], $_POST["mdp"])
    || !is_string($_POST["pseudo"]) || !is_string($_POST["mdp"])) {
    retour_connexion_client("requete_invalide");
}

// Assainissement (le mot de passe n'est pas nettoyé : les espaces en font partie)
$pseudo = nettoyer_texte($_POST["pseudo"]);
$mdp = $_POST["mdp"];

// Vérifications avant l'envoi à la base de données
// Message volontairement identique à un échec classique on ne révèle pas la règle
if (!valider_pseudo($pseudo) || $mdp === "" || strlen($mdp) > 128) {
    // Le pseudo n'est pas écrit dans le journal : il est invalide, il pourrait contenir n'importe quoi
    retour_connexion_client("donnees_invalides");
}

// Raison de l'échec si la connexion est refusée
$raison = "mot_de_passe_incorrect";

try {
    $stmt = $mon_pdo->prepare("SELECT idClient, pseudo, mdp FROM client WHERE pseudo = ?");
    $stmt->execute([$pseudo]);
    $resultat = $stmt->fetch(PDO::FETCH_ASSOC);

    // Le pseudo n'existe pas : on le note pour le journal
    if (!$resultat) {
        $raison = "compte_inexistant";
    }

    $hash = $resultat ? $resultat['mdp'] : hasher_mdp('mot_de_passe_factice');

    if (verifier_mdp($mdp, $hash) && $resultat) {
        session_regenerate_id(true);
        $_SESSION['login'] = $resultat['pseudo'];
        $_SESSION['idClient'] = $resultat['idClient'];
        $_SESSION['role'] = 'client';
        // Journalise la connexion réussie (jamais le mot de passe)
        journaliser("INFO", "CONNEXION_CLIENT", "SUCCES pseudo=" . $resultat['pseudo'] . " idClient=" . $resultat['idClient']);
        header("location:index.php?page=accueil");
        exit();
    }
} catch (Throwable $e) {
    // Erreur de la base de données : le détail est écrit dans le journal, l'utilisateur ne le voit pas
    journaliser("ERROR", "CONNEXION_CLIENT", "ERREUR_BDD " . nettoyer_log($e->getMessage(), 200));
    $raison = "erreur_technique";
}

// La connexion a échoué : on journalise l'échec avec la raison
retour_connexion_client($raison, $pseudo);