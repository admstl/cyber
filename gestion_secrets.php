<?php
require_once __DIR__ . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createUnsafeImmutable(__DIR__);
$dotenv->load();

$hash_key = getenv('HASH_KEY');
$cle_chiffrement = getenv('ENCRYPTION_KEY');

function hasher_mdp($mdp)
{
    global $hash_key;
    return password_hash(hash_hmac('sha256', $mdp, $hash_key), PASSWORD_DEFAULT);
}

function verifier_mdp($mdp, $hash)
{
    global $hash_key;
    return password_verify(hash_hmac('sha256', $mdp, $hash_key), $hash);
}

function chiffrer_donnee($donnee, $cle_chiffrement)
{
    if ($donnee === null) {
        return null;
    }
    $iv = random_bytes(12);
    $chiffre = openssl_encrypt($donnee, 'aes-256-gcm', $cle_chiffrement, 0, $iv, $tag);
    return base64_encode($iv . $tag . $chiffre);
}

function dechiffrer_donnee($donnee_chiffree, $cle_dechiffrement)
{
    if ($donnee_chiffree === null) {
        return null;
    }
    $brut = base64_decode($donnee_chiffree);
    if (strlen($brut) < 28) {
        return false;
    }
    $iv = substr($brut, 0, 12);
    $tag = substr($brut, 12, 16);
    $chiffre = substr($brut, 28);
    return openssl_decrypt($chiffre, 'aes-256-gcm', $cle_dechiffrement, 0, $iv, $tag);
}
