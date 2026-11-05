<?php
session_start();
require_once("info_bdd.php");

if (empty($_POST["consentement"])) {
    header("location:index.php?page=inscription_client&erreur=Vous%20devez%20accepter%20la%20politique%20de%20confidentialite");
    exit();
}

if (
    !empty($_POST["nom"]) &&
    !empty($_POST["prenom"]) &&
    !empty($_POST["pseudo"]) &&
    !empty($_POST["mdp"])
) {
    $nom = trim($_POST["nom"]);
    $prenom = trim($_POST["prenom"]);
    $pseudo = trim($_POST["pseudo"]);
    $mdp = $_POST["mdp"];
    $mail = !empty($_POST["mail"]) ? trim($_POST["mail"]) : null;
    $telephone = !empty($_POST["telephone"]) ? trim($_POST["telephone"]) : null;

    $verifSQL = "SELECT idClient FROM client WHERE pseudo = ?";
    $stmtVerif = $mon_pdo->prepare($verifSQL);
    $stmtVerif->execute([$pseudo]);

    if ($stmtVerif->fetch()) {
        header("location:index.php?page=inscription_client&erreur=Ce%20pseudo%20est%20déjà%20utilisé");
        exit();
    }

    $stmtInsert->execute([$nom, $prenom, $pseudo, $mdp, $mail, $telephone]);

    $insertSQL = "INSERT INTO client (nom, prenom, pseudo, mdp, mail, telephone) 
                  VALUES (?, ?, ?, ?, ?, ?)";
    $stmtInsert = $mon_pdo->prepare($insertSQL);

    try {
        $stmtInsert->execute([$nom, $prenom, $pseudo, $mdp, $mail, $telephone]);
    } catch (PDOException $e) {
        if ($e->getCode() === '23000') {
            header("Location: index.php?page=inscription_client&erreur=Ce%20pseudo%20est%20déjà%20utilisé");
            exit();
        }
        throw $e;
    }

    $idClient = $mon_pdo->lastInsertId();

    $_SESSION['login'] = $pseudo;
    $_SESSION['idClient'] = $idClient;
    $_SESSION['role'] = 'client';

    header("location:index.php?page=accueil&succes=Inscription%20reussie");
    exit();

} else {
    header("location:index.php?page=inscription_client&erreur=Veuillez%20remplir%20tous%20les%20champs%20obligatoires");
    exit();
}