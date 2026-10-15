<?php
session_start(); 

require_once("info_bdd.php");

if (!empty($_POST["pseudo"]) && !empty($_POST["password"])) {

    $nom = $_POST["pseudo"];
    $mdp = $_POST["password"];

    // 2. On utilise des marqueurs (?) pour éviter les injections SQL
    $requete = "SELECT login, role, mdp FROM users WHERE login = ?;";
    $stmt = $mon_pdo->prepare($requete);
    $stmt->execute([$nom]);
    $resultat = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($resultat && password_verify($mdp, $resultat['mdp'])) {
        
        session_regenerate_id(true); 
        
        $_SESSION['login'] = $resultat['login'];
        $_SESSION['role'] = $resultat['role'];

        header("Location: index.php?page=admin");
        exit();
    } else {
        header("Location: index.php?page=connexion&erreur=Echec%20De%20Connexion");
        exit();
    }
}
?>
