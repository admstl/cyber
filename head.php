<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Smart Phone</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="Style.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700&family=Inter:wght@400&display=swap" rel="stylesheet">
</head>

<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<body>
    <nav class="navbar navbar-expand-lg bg-white border-bottom">
        <div class="container-fluid">

            <a class="navbar-brand fw-bold" href="index.php?page=accueil">
                SMART PHONE
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">

                <ul class="navbar-nav me-auto mb-2 mb-lg-0">

                    <li class="nav-item">
                        <a class="nav-link active" href="index.php?page=accueil">Accueil</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="index.php?page=produit">Produits</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="index.php?page=contact">Contact</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="index.php?page=apropos">À propos</a>
                    </li>

                </ul>

                <div class="d-flex align-items-center gap-3">

                    <input class="form-control form-control-sm" type="search" placeholder="Rechercher...">

                    <?php if (isset($_SESSION['login'])): ?>
                        <!-- Lien vers la page Mon Compte -->
                        <a href="index.php?page=compte" class="btn btn-outline-dark btn-sm text-decoration-none">
                            <i class="bi bi-person-circle"></i> <?php echo htmlspecialchars($_SESSION['login']); ?>
                        </a>

                        <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'adm'): ?>
                            <a href="index.php?page=admin" class="btn btn-outline-warning btn-sm">Admin</a>
                        <?php endif; ?>

                        <!-- Déconnexion -->
                        <a href="deconnexion.php" class="btn btn-outline-danger btn-sm" title="Se déconnecter">
                            <i class="bi bi-box-arrow-right"></i> Déconnexion
                        </a>

                    <?php else: ?>
                        <!-- Connexion -->
                        <a href="index.php?page=connexion_client" class="btn btn-outline-dark btn-sm">
                            <i class="bi bi-person"></i> Connexion
                        </a>
                    <?php endif; ?>

                    <!-- Panier -->
                    <?php
                    $nbArticles = !empty($_SESSION['panier']) ? array_sum($_SESSION['panier']) : 0;
                    ?>
                    <a href="index.php?page=panier" class="btn btn-danger btn-sm position-relative">
                        <i class="bi bi-cart"></i> Panier
                        <?php if ($nbArticles > 0): ?>
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-dark">
                                <?php echo $nbArticles; ?>
                            </span>
                        <?php endif; ?>
                    </a>

                </div>

            </div>
        </div>
    </nav>