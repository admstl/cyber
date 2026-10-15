<?php
session_start();
require_once("info_bdd.php");

if (!isset($_SESSION['panier'])) {
    $_SESSION['panier'] = [];
}

$action = $_GET['action'] ?? '';
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($action === 'ajouter' && $id > 0) {
    if (isset($_SESSION['panier'][$id])) {
        $_SESSION['panier'][$id]++;
    } else {
        $_SESSION['panier'][$id] = 1;
    }
    header("Location: index.php?page=panier");
    exit();
}

if ($action === 'moins' && $id > 0) {
    if (isset($_SESSION['panier'][$id])) {
        $_SESSION['panier'][$id]--;
        if ($_SESSION['panier'][$id] <= 0) {
            unset($_SESSION['panier'][$id]);
        }
    }
    header("Location: index.php?page=panier");
    exit();
}

if ($action === 'supprimer' && $id > 0) {
    unset($_SESSION['panier'][$id]);
    header("Location: index.php?page=panier");
    exit();
}

if ($action === 'vider') {
    $_SESSION['panier'] = [];
    header("Location: index.php?page=panier");
    exit();
}

if ($action === 'valider') {
    if (empty($_SESSION['panier'])) {
        header("Location: index.php?page=panier");
        exit();
    }

    if (!isset($_SESSION['idClient'])) {
        header("Location: index.php?page=connexion_client&erreur=Connectez-vous pour commander");
        exit();
    }

    $idClient = $_SESSION['idClient'];
    $date = date('Y-m-d');
    $idStatut = 'ENC'; // Code statut (pense à en insérer un dans ta table statut si besoin)

    $reqCmd = $mon_pdo->prepare("INSERT INTO commande (idClient, idStatut, dateCommande) VALUES (?, ?, ?)");
    $reqCmd->execute([$idClient, $idStatut, $date]);
    $noCommande = $mon_pdo->lastInsertId();

    $reqContenir = $mon_pdo->prepare("INSERT INTO contenir (noCommande, numProduit, quantite) VALUES (?, ?, ?)");
    foreach ($_SESSION['panier'] as $prodId => $qte) {
        $reqContenir->execute([$noCommande, $prodId, $qte]);
    }

    $_SESSION['panier'] = [];

    header("Location: index.php?page=commande_succes&num=" . $noCommande);
    exit();
}