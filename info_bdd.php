<?php
require_once __DIR__ . '/gestion_secrets.php';

$host     = getenv('DB_HOST');
$port     = getenv('DB_PORT');
$dbname   = getenv('DB_NAME');
$username = getenv('DB_USER');
$password = getenv('DB_PASSWORD');

try {
    $pdo = new PDO(
        "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4",
        $username,
        $password,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_TIMEOUT => 5,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
    );

    $mon_pdo = $pdo;

    $connexion = $pdo;
    $bdd = $pdo;
    $db = $pdo;
    $conn = $pdo;

} catch (PDOException $e) {

    error_log("Connexion BDD impossible : " . $e->getMessage());
    http_response_code(500);
    die("Service momentanément indisponible.");
}
