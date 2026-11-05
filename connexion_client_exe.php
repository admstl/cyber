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

    // Si le pseudo et le mdp correspond a ce qui et en bdd alors le pseudo et l'id et garder en session et redirige vers l'accueil
    if ($resultat && $mdp === $resultat['mdp']) {
        session_regenerate_id(true);

        $_SESSION['login'] = $resultat['pseudo'];

        $_SESSION['idClient'] = $resultat['idClient'];

        header("location:index.php?page=accueil");
        exit();
    }
}
// Sinon redirection sur la meme page mais avec une erreur de connexion
header("location:index.php?page=connexion_client&erreur=Echec%20De%20Connexion");
exit();