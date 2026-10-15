<?php
session_start();
require_once("info_bdd.php");

if (
    !empty($_POST["nom"]) &&
    !empty($_POST["prenom"]) &&
    !empty($_POST["pseudo"]) &&
    !empty($_POST["mdp"])
) {
    $nom = trim($_POST["nom"]);
    $prenom = trim($_POST["prenom"]);
    $pseudo = trim($_POST["pseudo"]);
    $mdp = trim($_POST["mdp"]);
    $mail = !empty($_POST["mail"]) ? trim($_POST["mail"]) : null;
    $telephone = !empty($_POST["telephone"]) ? trim($_POST["telephone"]) : null;

    // 2. Vérifier si le pseudo existe déjà dans la table client
    $verifSQL = "SELECT idClient FROM client WHERE pseudo = ?";
    $stmtVerif = $mon_pdo->prepare($verifSQL);
    $stmtVerif->execute([$pseudo]);

    if ($stmtVerif->fetch()) {
        header("Location: index.php?page=inscription_client&erreur=Ce%20pseudo%20est%20déjà%20utilisé");
        exit();
    }

    $insertSQL = "INSERT INTO client (nom, prenom, pseudo, mdp, mail, telephone) 
                  VALUES (?, ?, ?, ?, ?, ?)";
    $stmtInsert = $mon_pdo->prepare($insertSQL);
    $stmtInsert->execute([$nom, $prenom, $pseudo, $mdp, $mail, $telephone]);

    $idClient = $mon_pdo->lastInsertId();

    $_SESSION['login'] = $pseudo;
    $_SESSION['idClient'] = $idClient;
    $_SESSION['role'] = 'client';

    header("Location: index.php?page=accueil&succes=Inscription%20reussie");
    exit();

} else {
    header("Location: index.php?page=inscription_client&erreur=Veuillez%20remplir%20tous%20les%20champs%20obligatoires");
    exit();
}