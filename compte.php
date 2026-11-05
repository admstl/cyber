<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once("info_bdd.php");

// Si dans la session l'id client et vide alors ça redirige vers la page de connexion
if (!isset($_SESSION['idClient'])) {
    header("Location: index.php?page=connexion_client&erreur=Veuillez vous connecter");
    exit();
}

$idClient = $_SESSION['idClient'];
// Requete sur les information du client
$reqClient = $mon_pdo->prepare("
    SELECT c.nom, c.prenom, c.pseudo, c.mail, c.telephone,
           a.rue, a.codePostal, a.ville
    FROM client c
    LEFT JOIN habiter h ON c.idClient = h.idClient
    LEFT JOIN adresse a ON h.idAdresse = a.idAdresse
    WHERE c.idClient = ?
");
$reqClient->execute([$idClient]);
$client = $reqClient->fetch(PDO::FETCH_ASSOC);

// Requete sur les commandes
$reqCommandes = $mon_pdo->prepare("
    SELECT cmd.noCommande, cmd.dateCommande, s.libelleStatut
    FROM commande cmd
    LEFT JOIN statut s ON cmd.idStatut = s.idStatut
    WHERE cmd.idClient = ?
    ORDER BY cmd.noCommande DESC
");
$reqCommandes->execute([$idClient]);
$commandes = $reqCommandes->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container my-5">
    <h2 class="fw-bold mb-4">Mon compte</h2>

    <div class="row g-4">
        <div class="col-md-4">
            <div class="p-4 border rounded bg-white">
                <h5 class="fw-bold mb-3">Mes coordonnées</h5>
                <p class="mb-1"><strong><?php echo htmlspecialchars(($client['prenom'] ?? '') . ' ' . ($client['nom'] ?? '')); ?></strong></p>
                <p class="mb-1 text-muted small">@<?php echo htmlspecialchars($client['pseudo'] ?? ''); ?></p>
                <p class="mb-1 text-muted"><?php echo htmlspecialchars($client['mail'] ?? 'Non renseigné'); ?></p>
                <p class="mb-3 text-muted"><?php echo htmlspecialchars($client['telephone'] ?? 'Non renseigné'); ?></p>
            </div>

            <div class="p-3 border rounded bg-white mt-3 small text-muted">
                <strong>Vos données, vos droits</strong><br>
                Vous pouvez demander l'accès, la rectification ou la suppression de vos données.
                <a href="index.php?page=confidentialite">Voir la politique de confidentialité</a>.
            </div>
        </div>

        <div class="col-md-8">
            <h5 class="fw-bold mb-3">Mes commandes</h5>

            <?php if (empty($commandes)) : ?>
                <div class="p-4 border rounded bg-white text-muted">
                    Vous n'avez pas encore passé de commande.
                </div>
            <?php else : ?>
                <?php foreach ($commandes as $cmd) : 
                    $reqArticles = $mon_pdo->prepare("
                        SELECT p.nomProduit, p.prixUnitaire, co.quantite
                        FROM contenir co
                        INNER JOIN produit p ON co.numProduit = p.numProduit
                        WHERE co.noCommande = ?
                    ");
                    $reqArticles->execute([$cmd['noCommande']]);
                    $articles = $reqArticles->fetchAll(PDO::FETCH_ASSOC);

                    $total = 0;
                ?>
                    <div class="border rounded bg-white p-3 mb-3">
                        <div class="d-flex justify-content-between align-items-center border-bottom pb-2 mb-2">
                            <div>
                                <span class="fw-bold">Commande #<?php echo $cmd['noCommande']; ?></span>
                                <span class="text-muted small ms-2"><?php echo htmlspecialchars($cmd['dateCommande']); ?></span>
                            </div>
                            <span class="badge bg-light text-dark border">
                                <?php echo htmlspecialchars($cmd['libelleStatut'] ?? 'En cours'); ?>
                            </span>
                        </div>

                        <div class="small">
                            <?php foreach ($articles as $art) : 
                                $sousTotal = $art['prixUnitaire'] * $art['quantite'];
                                $total += $sousTotal;
                            ?>
                                <div class="d-flex justify-content-between py-1 text-muted">
                                    <span><?php echo htmlspecialchars($art['nomProduit']); ?> × <?php echo $art['quantite']; ?></span>
                                    <span><?php echo number_format($sousTotal, 2, ',', ' '); ?> €</span>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <div class="d-flex justify-content-between pt-2 mt-2 border-top fw-bold">
                            <span>Total</span>
                            <span class="text-danger"><?php echo number_format($total, 2, ',', ' '); ?> €</span>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>