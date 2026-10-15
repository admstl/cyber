<?php

session_start();

$pages_autorisees = [
    'accueil', 'produit', 'contact', 'connexion', 
    'connexion_client', 'inscription_client', 'admin', 
    'detailproduit', 'apropos', 'mention', 'panier', 
    'commande_succes'
];

$page = $_GET["page"] ?? 'accueil';

if (!in_array($page, $pages_autorisees)) {
    $page = 'accueil';
}

if (!file_exists($page . ".php")) {
    $page = 'accueil';
}

include("head.php");
include($page . ".php");
?>

<?php
include("footer.php");
?>
