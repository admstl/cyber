<?php
// Dossier des journaux : défini une seule fois dans le .env (LOG_DIR) avec une valeur par défaut
function dossier_logs(): string
{
    $dossier = getenv('LOG_DIR');
    return rtrim(($dossier !== false && $dossier !== '') ? $dossier : '/var/log/site-marchand', '/');
}

// Nettoie une valeur avant de l'écrire dans le journal : sans retour à la ligne (sinon on pourrait fabriquer de fausses lignes de log) et de longueur limitée
function nettoyer_log(string $valeur, int $max = 60): string
{
    $valeur = preg_replace('/[\x00-\x1F\x7F]/', ' ', $valeur) ?? '';
    return substr($valeur, 0, $max);
}

// Écrit une ligne dans le journal de l'application
// Format : [date heure] [NIVEAU] [EVENEMENT] ip=adresse message
function journaliser(string $niveau, string $evenement, string $message): void
{
    $ip = nettoyer_log($_SERVER['REMOTE_ADDR'] ?? 'inconnue', 45);
    $ligne = '[' . date('Y-m-d H:i:s') . '] [' . $niveau . '] [' . $evenement . '] ip=' . $ip . ' ' . $message . PHP_EOL;

    // Un fichier par jour application-AAAA-MM-JJ.log
    $fichier = dossier_logs() . '/application-' . date('Y-m-d') . '.log';

    // Ecrire dans le fichier indiqué. Si c'est impossible (dossier absent ou droits), on retombe sur le journal PHP
    if (@error_log($ligne, 3, $fichier) === false) {
        error_log(trim($ligne));
    }
}
