<?php

session_start();
require_once("info_bdd.php");

if(isset($_POST["login"]) && isset($_POST["password"])){

    $nom = $_POST["login"];
    $mdp = $_POST["password"];

    $stmt = $mon_pdo->prepare("SELECT login, role, mdp FROM users WHERE login = ?");
    $stmt->execute([$nom]);
    $resultat = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($resultat && $mdp === $resultat['mdp']) {
        session_regenerate_id(true);
        $_SESSION['login'] = $resultat['login'];
        $_SESSION['role'] = $resultat['role'];

        header("location:index.php?page=admin");
        exit();
    }
    else {
        header("location:index.php?page=connexion&&erreur=Echec De Connexion");
        exit();
    }
}
?>
