<?php
$numCommande = isset($_GET['num']) ? (int)$_GET['num'] : 0;
?>

<div class="container py-5 text-center">
    <div class="card shadow-sm border-0 p-5 mx-auto" style="max-width: 600px;">
        
        <div class="mb-4">
            <i class="bi bi-check-circle-fill text-success" style="font-size: 80px;"></i>
        </div>

        <h1 class="fw-bold mb-3">Commande validée !</h1>
        <p class="text-muted fs-5">
            Merci pour votre achat chez <strong>Smart Phone</strong>.
        </p>

        <?php if ($numCommande > 0) : ?>
            <div class="alert alert-light border my-3 py-3">
                Numéro de commande : <strong class="text-danger fs-5">#<?php echo $numCommande; ?></strong>
            </div>
        <?php endif; ?>

        <p class="text-secondary small">
            Un e-mail récapitulatif vient de vous être envoyé. Notre équipe prépare votre colis pour une expédition en 24-48h.
        </p>

        <div class="d-flex justify-content-center gap-3 mt-4">
            <a href="index.php?page=accueil" class="btn btn-secondary">
                Retour à l'accueil
            </a>
            <a href="index.php?page=produit" class="btn btn-danger">
                Continuer mes achats
            </a>
        </div>

    </div>
</div>