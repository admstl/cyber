<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once("info_bdd.php");

// Rediriger si le client n'est pas connecté
if (!isset($_SESSION['idClient'])) {
    header("Location: index.php?page=connexion_client&erreur=Veuillez vous connecter pour accéder à votre compte");
    exit();
}

$idClient = $_SESSION['idClient'];

// 1. Informations du client et son adresse
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

// 2. Historique des commandes du client
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
    <h1 class="mb-4 fw-bold">Espace Mon Compte</h1>

    <div class="row g-4">
        <!-- Informations personnelles -->
        <div class="col-lg-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-dark text-white fw-bold">
                    <i class="bi bi-person-lines-fill me-2"></i>Mes Coordonnées
                </div>
                <div class="card-body">
                    <p class="mb-2"><strong>Nom :</strong> <?php echo htmlspecialchars($client['nom'] ?? ''); ?></p>
                    <p class="mb-2"><strong>Prénom :</strong> <?php echo htmlspecialchars($client['prenom'] ?? ''); ?></p>
                    <p class="mb-2"><strong>Pseudo :</strong> <?php echo htmlspecialchars($client['pseudo'] ?? ''); ?></p>
                    <p class="mb-2"><strong>Email :</strong> <?php echo htmlspecialchars($client['mail'] ?? 'Non renseigné'); ?></p>
                    <p class="mb-3"><strong>Téléphone :</strong> <?php echo htmlspecialchars($client['telephone'] ?? 'Non renseigné'); ?></p>

                    <hr>
                    <h6 class="fw-bold"><i class="bi bi-geo-alt-fill me-1"></i>Adresse</h6>
                    <?php if (!empty($client['rue'])) : ?>
                        <p class="mb-0 text-muted">
                            <?php echo htmlspecialchars($client['rue']); ?><br>
                            <?php echo htmlspecialchars($client['codePostal'] . ' ' . $client['ville']); ?>
                        </p>
                    <?php else : ?>
                        <p class="text-muted small">Aucune adresse enregistrée.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Historique des commandes -->
        <div class="col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-danger text-white fw-bold">
                    <i class="bi bi-bag-check-fill me-2"></i>Historique de mes commandes
                </div>
                <div class="card-body p-4">
                    <?php if (empty($commandes)) : ?>
                        <p class="text-muted mb-0">Vous n'avez pas encore passé de commande.</p>
                        <a href="index.php?page=produit" class="btn btn-outline-danger mt-3 btn-sm">Découvrir les produits</a>
                    <?php else : ?>
                        <div class="accordion" id="commandesAccordion">
                            <?php foreach ($commandes as $index => $cmd) : 
                                // Récupération des articles pour chaque commande
                                $reqArticles = $mon_pdo->prepare("
                                    SELECT p.nomProduit, p.prixUnitaire, co.quantite
                                    FROM contenir co
                                    INNER JOIN produit p ON co.numProduit = p.numProduit
                                    WHERE co.noCommande = ?
                                ");
                                $reqArticles->execute([$cmd['noCommande']]);
                                $articles = $reqArticles->fetchAll(PDO::FETCH_ASSOC);

                                $totalCommande = 0;
                            ?>
                                <div class="accordion-item mb-2 border rounded">
                                    <h2 class="accordion-header" id="heading-<?php echo $cmd['noCommande']; ?>">
                                        <button class="accordion-button <?php echo $index !== 0 ? 'collapsed' : ''; ?>" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-<?php echo $cmd['noCommande']; ?>">
                                            <div class="d-flex justify-content-between align-items-center w-100 pe-3">
                                                <span><strong>Commande #<?php echo $cmd['noCommande']; ?></strong> — <?php echo htmlspecialchars($cmd['dateCommande']); ?></span>
                                                <span class="badge bg-secondary"><?php echo htmlspecialchars($cmd['libelleStatut'] ?? 'En cours'); ?></span>
                                            </div>
                                        </button>
                                    </h2>
                                    <div id="collapse-<?php echo $cmd['noCommande']; ?>" class="accordion-collapse collapse <?php echo $index === 0 ? 'show' : ''; ?>" data-bs-parent="#commandesAccordion">
                                        <div class="accordion-body">
                                            <table class="table table-sm align-middle mb-0">
                                                <thead>
                                                    <tr>
                                                        <th>Article</th>
                                                        <th>Prix unitaire</th>
                                                        <th>Quantité</th>
                                                        <th class="text-end">Sous-total</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($articles as $art) : 
                                                        $sousTotal = $art['prixUnitaire'] * $art['quantite'];
                                                        $totalCommande += $sousTotal;
                                                    ?>
                                                        <tr>
                                                            <td><?php echo htmlspecialchars($art['nomProduit']); ?></td>
                                                            <td><?php echo number_format($art['prixUnitaire'], 2, ',', ' '); ?> €</td>
                                                            <td><?php echo $art['quantite']; ?></td>
                                                            <td class="text-end"><?php echo number_format($sousTotal, 2, ',', ' '); ?> €</td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                </tbody>
                                                <tfoot>
                                                    <tr>
                                                        <th colspan="3" class="text-end">Total :</th>
                                                        <th class="text-end text-danger"><?php echo number_format($totalCommande, 2, ',', ' '); ?> €</th>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>