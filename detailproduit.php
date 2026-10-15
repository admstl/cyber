<?php
require "./info_bdd.php";

$numProduit = isset($_GET["numProduit"]) ? (int) $_GET["numProduit"] : 1;

$requeteSQL = "SELECT numProduit, nomProduit, descriptif, stock, prixUnitaire, nomImage 
FROM produit 
WHERE numProduit = :numProduit";

$stmt = $pdo->prepare($requeteSQL);
$stmt->execute(["numProduit" => $numProduit]);

$produit = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$produit) {
    echo "<h2 class='text-center text-danger mt-5'>Produit introuvable</h2>";
    exit;
}
?>

<div class="container mt-5">

    <div class="row g-4 align-items-center">

        <!-- IMAGE -->
        <div class="col-md-6">
            <div class="card shadow border-0">
                <img style="max-height: 450px; object-fit: contain;" src="./images/<?php echo htmlspecialchars($produit["nomImage"]); ?>" 
                     class="img-fluid rounded"
                     style="max-height: 450px; object-fit: cover;"
                     alt="Image produit">
            </div>
        </div>

        <div class="col-md-6">

            <h1 class="fw-bold">
                <?php echo htmlspecialchars($produit["nomProduit"]); ?>
            </h1>

            <h2 class="text-success mt-3">
                <?php echo number_format($produit["prixUnitaire"], 2, ',', ' '); ?> €
            </h2>

            <p class="mt-3 text-muted">
                <?php echo nl2br(htmlspecialchars($produit["descriptif"])); ?>
            </p>

            <?php if ($produit["stock"] > 0) { ?>
                <p class="text-success">
                    En stock (<?php echo $produit["stock"]; ?> disponibles)
                </p>
            <?php } else { ?>
                <p class="text-danger">
                    Rupture de stock
                </p>
            <?php } ?>

            <div class="mt-4 d-flex gap-2">

                <a href="panier_action.php?action=ajouter&id=<?php echo $produit['numProduit']; ?>" class="btn btn-danger <?php echo ($produit['stock'] == 0) ? 'disabled' : ''; ?>">
                    Ajout au Panier
                </a>

                <a href="index.php" class="btn btn-secondary">
                    Retour
                </a>

            </div>

        </div>

    </div>

</div>