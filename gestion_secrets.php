<?php
require_once __DIR__ . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createUnsafeImmutable(__DIR__);
$dotenv->load();

// On vérifie que les clés existent bien dans le fichier .env
$dotenv->required(['HASH_KEY', 'ENCRYPTION_KEY'])->notEmpty();

// Récupération des clés avec getenv()
$hash_key = getenv('HASH_KEY');
$cle_chiffrement = getenv('ENCRYPTION_KEY');

function hasher_mdp(string $mdp): string
{
    global $hash_key;
    // Utilisation de l'algorithme sha256
    return password_hash(hash_hmac('sha256', $mdp, $hash_key), PASSWORD_DEFAULT);
}

function verifier_mdp(string $mdp, string $hash): bool
{
    global $hash_key;
    // Utilisation de l'algorithme sha256
    return password_verify(hash_hmac('sha256', $mdp, $hash_key), $hash);
}

function chiffrer_donnee(string $donnee, string $cle_chiffrement): string
{
    // Génération un vecteur d'initialisation iv aléatoire sur 12 octets
    $iv = random_bytes(12);
    // Chiffrement de la donnée en AES-256-GCM et récupération du tag d'authentification
    $chiffre = openssl_encrypt($donnee, 'aes-256-gcm', $cle_chiffrement, 0, $iv, $tag);
    return base64_encode($iv . $tag . $chiffre);
}

function dechiffrer_donnee(string $donnee_chiffree, string $cle_dechiffrement): string
{
    // Décodage de la chaîne base64 pour récupérer les données binaires brutes
    $brut = base64_decode($donnee_chiffree);
    // Extraction de l'IV
    $iv = substr($brut, 0, 12);
    // Extraction du tag d'authentification
    $tag = substr($brut, 12, 16);
    // Extraction du message chiffré
    $chiffre = substr($brut, 28);
    // Déchiffrement et vérification de l'intégrité des données avec le tag
    $claire = openssl_decrypt($chiffre, 'aes-256-gcm', $cle_dechiffrement, 0, $iv, $tag);
    // Si la clé est fausse ou les données altérées, on place une exception
    if ($claire === false) {
        throw new Exception("Déchiffrement impossible");
    }
    return $claire;
}