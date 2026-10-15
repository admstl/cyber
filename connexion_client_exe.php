<?php

session_start();
require_once("info_bdd.php");

if (!empty($_POST["pseudo"]) && !empty($_POST["mdp"])) {

    $nom = $_POST["pseudo"];
    $mdp = $_POST["mdp"];

    $requete = "SELECT idClient, pseudo, mdp FROM client WHERE pseudo = ?;";
    $stmt = $mon_pdo->prepare($requete);
    $stmt->execute([$nom]);
    $resultat = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($resultat && $mdp === $resultat['mdp']) {
        session_regenerate_id(true);

        $_SESSION['login'] = $resultat['pseudo'];

        // ICI : on garde son identifiant client
        $_SESSION['idClient'] = $resultat['idClient'];

        header("Location: index.php?page=accueil");
        exit();
    }
}

header("Location: index.php?page=connexion_client&erreur=Echec%20De%20Connexion");
exit();