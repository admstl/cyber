<?php
require "./info_bdd.php";

$panier = $_SESSION['panier'] ?? [];
?>

<h1 class="text-center m-4">Mon Panier</h1>

<div class="container">

<?php
if (empty($panier)) {
    echo "<p class='text-center'>Votre panier est vide</p>";
} else {

    $total = 0;

    foreach ($panier as $id => $quantite) {

        $stmt = $pdo->prepare("SELECT numProduit, nomProduit, prixUnitaire, nomImage 
                               FROM produit 
                               WHERE numProduit = :id");

        $stmt->execute(["id" => $id]);
        $produit = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$produit) continue;

        $sousTotal = $produit['prixUnitaire'] * $quantite;
        $total += $sousTotal;
?>

        <div class="card mb-3 shadow">
            <div class="row g-0">

                <div class="col-md-3">
                    <img src="./images/<?php echo $produit['nomImage']; ?>" 
                         class="img-fluid rounded-start">
                </div>

                <div class="col-md-9">
                    <div class="card-body">

                        <h5><?php echo $produit['nomProduit']; ?></h5>

                        <p>
                            Prix : <?php echo $produit['prixUnitaire']; ?> € <br>
                            Quantité : <?php echo $quantite; ?> <br>
                            Sous-total : <?php echo $sousTotal; ?> €
                        </p>

                        <a href="supprimer_panier.php?id=<?php echo $id; ?>" 
                           class="btn btn-danger btn-sm">
                           Supprimer
                        </a>

                    </div>
                </div>

            </div>
        </div>

<?php
    }

    echo "<h3 class='text-end'>Total : $total €</h3>";
}
?>

</div>