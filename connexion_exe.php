<?php
session_start();
require_once("info_bdd.php");

if(isset($_POST["login"]) && isset($_POST["password"])){

    $nom = $_POST["login"];
    $mdp = $_POST["password"];

    $stmt = $mon_pdo->prepare("SELECT login, role, mdp FROM users WHERE login = ?");
    $stmt->execute([$nom]);
    $resultat = $stmt->fetch(PDO::FETCH_ASSOC);

    // Si le pseudo et le mdp correspond a ce qui et en bdd alors le pseudo et l'id et garder en session et redirige vers l'accueil
    if ($resultat && $mdp === $resultat['mdp']) {
        session_regenerate_id(true);
        $_SESSION['login'] = $resultat['login'];
        $_SESSION['role'] = $resultat['role'];

        header("location:index.php?page=admin");
        exit();
    }
    // Sinon redirection vers la page de connexion avec l'erreur : Echec De Connexion
    else {
        header("location:index.php?page=connexion&&erreur=Echec De Connexion");
        exit();
    }
}
?>
