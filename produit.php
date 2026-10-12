<?php
require "./info_bdd.php";

$requeteSQL = "SELECT numProduit, nomProduit, descriptif, stock, prixUnitaire, nomImage,
categorie.libelleCategorie 
FROM produit
INNER JOIN categorie 
ON produit.idCategorie = categorie.idCategorie;";

$reponse = $pdo->query($requeteSQL);
$liste_produits = $reponse->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container py-5">
    <h1 class="text-center mb-5">Produits</h1>
    <div class="row g-4">
        <?php foreach ($liste_produits as $produit) { ?>
            <div class="col-md-4">
                <div class="card h-100 shadow-sm">
                    <img src="./images/<?php echo htmlspecialchars($produit['nomImage']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($produit['nomProduit']); ?>">
                    <div class="card-body text-center">
                        <h5 class="card-title"><?php echo htmlspecialchars($produit['nomProduit']); ?></h5>
                        <p class="text-success fw-bold"><?php echo number_format($produit['prixUnitaire'], 2, ',', ' '); ?> €</p>
                        <a href="index.php?page=detailproduit&numProduit=<?php echo $produit['numProduit']; ?>" class="btn btn-danger">Voir Produit</a>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
</div>