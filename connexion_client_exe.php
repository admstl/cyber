<?php
$tmpPath = __DIR__ . '/tmp';
if (is_dir($tmpPath)) {
    session_save_path($tmpPath);
}
session_start();

require_once("info_bdd.php");

if (!empty($_POST["pseudo"]) && !empty($_POST["mdp"])) {

    $nom = $_POST["pseudo"];
    $mdp = $_POST["mdp"];

    $requete = "SELECT login, role, mdp FROM users WHERE login = ?;";
    $stmt = $mon_pdo->prepare($requete);
    $stmt->execute([$nom]);
    $resultat = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($resultat && $mdp === $resultat['mdp']) {

        session_regenerate_id(true);

        $_SESSION['login'] = $resultat['login'];
        $_SESSION['role'] = $resultat['role'];

        header("Location: index.php?page=admin");
        exit();
    }
}

header("Location: index.php?page=connexion_client&erreur=Echec%20De%20Connexion");
exit();