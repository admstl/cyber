<?php
// Script à lancer une seule fois en ligne de commande : php chiffrer_donnees_existantes.php
// Il chiffre le nom et le prénom des tables users et client, ainsi que le mail et le téléphone de la table client.

// Refuse l'exécution depuis un navigateur
if (PHP_SAPI !== 'cli') {
    exit("Script réservé à la ligne de commande.\n");
}

// Connexion à la base de données et fonctions de gestion des secrets
require_once __DIR__ . '/info_bdd.php';

// Pour chaque table : colonne de l'identifiant et colonnes à chiffrer
$tables = [
    'users'  => ['codeUser', ['nom', 'prenom']],
    'client' => ['idClient', ['nom', 'prenom', 'mail', 'telephone']],
];

// Retourne true si la valeur est déjà chiffrée (elle se déchiffre avec notre clé)
function est_deja_chiffre(string $valeur, string $cle): bool
{
    try {
        dechiffrer_donnee($valeur, $cle);
        return true;
    } catch (Throwable $e) {
        return false;
    }
}

// Tout est fait dans une transaction : en cas d'erreur, la base reste inchangée
$mon_pdo->beginTransaction();
try {
    foreach ($tables as $table => [$colonneId, $colonnes]) {
        $nb = 0;
        foreach ($colonnes as $colonne) {
            $lignes = $mon_pdo->query("SELECT $colonneId AS id, $colonne AS valeur FROM $table")->fetchAll(PDO::FETCH_ASSOC);
            $maj = $mon_pdo->prepare("UPDATE $table SET $colonne = ? WHERE $colonneId = ?");

            foreach ($lignes as $ligne) {
                // Valeur vide (mail ou téléphone non renseigné) ou déjà chiffrée : on ne touche pas
                if ($ligne['valeur'] === null || $ligne['valeur'] === '' || est_deja_chiffre($ligne['valeur'], $cle_chiffrement)) {
                    continue;
                }
                $maj->execute([chiffrer_donnee($ligne['valeur'], $cle_chiffrement), $ligne['id']]);
                $nb++;
            }
        }
        echo "$table : $nb valeur(s) chiffrée(s)\n";
    }
    $mon_pdo->commit();
} catch (Throwable $e) {
    $mon_pdo->rollBack();
    echo "Erreur, aucune modification appliquée : " . $e->getMessage() . "\n";
    exit(1);
}
