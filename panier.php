<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once("info_bdd.php");

$panier = $_SESSION['panier'] ?? [];
$produitsPanier = [];
$total = 0;

if (!empty($panier)) {
    $ids = implode(',', array_keys($panier));
    $req = $mon_pdo->query("SELECT * FROM produit WHERE numProduit IN ($ids)");
    $produitsPanier = $req->fetchAll(PDO::FETCH_ASSOC);
}
?>

<div class="container my-5">
    <h1 class="mb-4">Votre Panier</h1>

    <?php if (isset($_GET['succes'])) { ?>
        <div class="alert alert-success">
            Merci ! Votre commande a bien été enregistrée.
        </div>
    <?php } ?>

    <?php if (empty($produitsPanier)) { ?>
        <p class="text-muted">Votre panier est actuellement vide.</p>
        <a href="index.php?page=produit" class="btn btn-outline-danger">Continuer vos achats</a>
    <?php } else { ?>
        <table class="table table-bordered align-middle">
            <thead class="table-dark">
                <tr>
                    <th>Article</th>
                    <th>Prix unitaire</th>
                    <th style="width: 150px;">Quantité</th>
                    <th>Sous-total</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($produitsPanier as $p) { 
                    $qte = $panier[$p['numProduit']];
                    $sousTotal = $p['prixUnitaire'] * $qte;
                    $total += $sousTotal;
                ?>
                    <tr>
                        <td>
                            <strong><?php echo htmlspecialchars($p['nomProduit']); ?></strong>
                        </td>
                        <td><?php echo number_format($p['prixUnitaire'], 2, ',', ' '); ?> €</td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <a href="panier_action.php?action=moins&id=<?php echo $p['numProduit']; ?>" class="btn btn-sm btn-secondary">-</a>
                                <span class="fw-bold"><?php echo $qte; ?></span>
                                <a href="panier_action.php?action=ajouter&id=<?php echo $p['numProduit']; ?>" class="btn btn-sm btn-secondary">+</a>
                            </div>
                        </td>
                        <td><?php echo number_format($sousTotal, 2, ',', ' '); ?> €</td>
                        <td>
                            <a href="panier_action.php?action=supprimer&id=<?php echo $p['numProduit']; ?>" class="btn btn-sm btn-danger">Supprimer</a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="3" class="text-end">Total général :</th>
                    <th colspan="2" class="text-success fs-5"><?php echo number_format($total, 2, ',', ' '); ?> €</th>
                </tr>
            </tfoot>
        </table>

        <div class="d-flex justify-content-between mt-4">
            <a href="panier_action.php?action=vider" class="btn btn-outline-secondary">Vider le panier</a>
            <a href="panier_action.php?action=valider" class="btn btn-success btn-lg">Valider la commande</a>
        </div>
    <?php } ?>
</div>