<?php
require_once("info_bdd.php");

if(isset($_POST["pseudo"]) && isset($_POST["password"])){

    $nom = $_POST["pseudo"];
    $mdp = $_POST["password"];

    $requete = "SELECT login, role, mdp FROM users where login='$nom' and mdp='$mdp';";
    $reponse = $mon_pdo->query($requete);
    $resultat = $reponse->fetch(PDO::FETCH_ASSOC);

    if ($resultat){
        $_SESSION['login'] = $nom;
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