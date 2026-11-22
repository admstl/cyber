<?php
session_start();
require_once("info_bdd.php");
require_once("validation.php");

function retour_connexion(): void
{
    header("location:index.php?page=connexion&erreur=" . urlencode("Echec De Connexion"));
    exit();
}

if ($_SERVER["REQUEST_METHOD"] !== "POST"
    || !isset($_POST["login"], $_POST["password"])
    || !is_string($_POST["login"]) || !is_string($_POST["password"])) {
    retour_connexion();
}

// Assainissement le login est nettoyé, le mot de passe est laissé tel quel
$login = nettoyer_texte($_POST["login"]);
$mdp = $_POST["password"];

// Vérifications avant d'interroger la BDD (login = varchar(15) dans la table users)
if ($login === "" || strlen($login) > 15 || !valider_pseudo($login)
    || $mdp === "" || strlen($mdp) > 128) {
    retour_connexion();
}

// Le try/catch prévoit une panne de la base de données
try {
    $stmt = $mon_pdo->prepare("SELECT login, role, mdp FROM users WHERE login = ?");
    $stmt->execute([$login]);
    $resultat = $stmt->fetch(PDO::FETCH_ASSOC);

    // Si l'utilisateur n'existe pas on vérifie quand même un faux hash pour que le temps de réponse ne révèle pas si le login existe
    $hash = $resultat ? $resultat['mdp'] : hasher_mdp('mot_de_passe_factice');

    if (verifier_mdp($mdp, $hash) && $resultat) {
        session_regenerate_id(true);
        $_SESSION['login'] = $resultat['login'];
        $_SESSION['role'] = $resultat['role'];
        header("location:index.php?page=admin");
        exit();
    }
} catch (Throwable $e) {
    // Le détail technique va dans les logs, l'utilisateur ne voit que le message d'échec
    error_log("Connexion administrateur impossible : " . $e->getMessage());
}

retour_connexion();