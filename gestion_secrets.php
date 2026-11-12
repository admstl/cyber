<?php
require_once __DIR__ . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createUnsafeImmutable(__DIR__);
$dotenv->load();

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
    $chiffre = openssl_encrypt($donnee, 'aes-256-gcm', $cle_chiffrement, 0, $iv, $tag);
    return base64_encode($iv . $tag . $chiffre);
}

function dechiffrer_donnee(string $donnee_chiffree, string $cle_dechiffrement): string
{
    $brut = base64_decode($donnee_chiffree);
    $iv = substr($brut, 0, 12);
    $tag = substr($brut, 12, 16);
    $chiffre = substr($brut, 28);
    $claire = openssl_decrypt($chiffre, 'aes-256-gcm', $cle_dechiffrement, 0, $iv, $tag);
    if ($claire === false) {
        throw new Exception("Déchiffrement impossible");
    }
    return $claire;
}