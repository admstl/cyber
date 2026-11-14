<?php
// Fonctions de nettoyage et de validation des données de l'inscription.
// Elles sont utilisées par inscription_client_exe.php avant tout envoi à la base de données.

// Nettoie un texte saisi : enlève les espaces au début et à la fin,
// les caractères de contrôle invisibles et les espaces en double.
function nettoyer_texte(string $texte): string
{
    $texte = trim($texte);
    $texte = preg_replace('/[\x00-\x1F\x7F]/u', '', $texte) ?? '';
    $texte = preg_replace('/\s+/u', ' ', $texte) ?? '';
    return $texte;
}

// Pseudo : 3 à 32 caractères, lettres, chiffres, point, tiret et underscore uniquement.
function valider_pseudo(string $pseudo): bool
{
    return preg_match('/^[A-Za-z0-9_.-]{3,32}$/', $pseudo) === 1;
}

// Nom ou prénom : 2 à 50 caractères, lettres (accents compris), espace, tiret et apostrophe.
function valider_nom(string $nom): bool
{
    return preg_match("/^\p{L}[\p{L}'’ -]{1,49}$/u", $nom) === 1;
}

// Mail : format valide (filter_var), extension d'au moins 2 lettres (.fr, .com...) et 100 caractères maximum.
function valider_mail(string $mail): bool
{
    return strlen($mail) <= 100
        && filter_var($mail, FILTER_VALIDATE_EMAIL) !== false
        && preg_match('/\.[A-Za-z]{2,}$/', $mail) === 1;
}

// Téléphone français : enlève les séparateurs (espaces, points, tirets, parenthèses),
// accepte le format 06... ou +33 6... et retourne le numéro sous la forme 0612345678.
// Retourne null si le numéro n'est pas valide.
function normaliser_telephone(string $telephone): ?string
{
    $numero = preg_replace('/[\s.\-()]/', '', $telephone) ?? '';
    if (preg_match('/^(\+33|0033)/', $numero) === 1) {
        $numero = '0' . preg_replace('/^(\+33|0033)/', '', $numero);
    }
    return preg_match('/^0[1-9][0-9]{8}$/', $numero) === 1 ? $numero : null;
}

// Mot de passe : retourne le message d'erreur s'il est trop faible, ou null s'il est valide.
// Règles : 12 caractères minimum, au moins une minuscule, une majuscule, un chiffre
// et un caractère spécial, et il ne doit pas contenir le pseudo.
function verifier_force_mdp(string $mdp, string $pseudo): ?string
{
    if (strlen($mdp) < 12) {
        return "Le mot de passe doit contenir au moins 12 caractères";
    }
    if (strlen($mdp) > 128) {
        return "Le mot de passe est trop long (128 caractères maximum)";
    }
    if (preg_match('/[a-z]/', $mdp) !== 1) {
        return "Le mot de passe doit contenir une minuscule";
    }
    if (preg_match('/[A-Z]/', $mdp) !== 1) {
        return "Le mot de passe doit contenir une majuscule";
    }
    if (preg_match('/[0-9]/', $mdp) !== 1) {
        return "Le mot de passe doit contenir un chiffre";
    }
    if (preg_match('/[^A-Za-z0-9]/', $mdp) !== 1) {
        return "Le mot de passe doit contenir un caractère spécial";
    }
    if ($pseudo !== '' && stripos($mdp, $pseudo) !== false) {
        return "Le mot de passe ne doit pas contenir le pseudo";
    }
    return null;
}
