<?php
require_once("info_bdd.php");
?>

<div class="container text-center">
    <h1>Administration</h1>
    <div class="alert alert-success w-50 mx-auto" role="alert">
        Bienvenue <?php echo $_SESSION['login']; ?> role : <?php echo $_SESSION['role']; ?>
    </div>

    <h2 style="color: green;">Connexion REUSSIE</h2>
    <i class="bi bi-patch-check-fill" style="font-size: 100px; color: green;"></i>
</div>

<?php
$requete_sql = "SELECT codeUser, login, mdp, nom, prenom, role FROM users";
$reponse = $mon_pdo->query($requete_sql);
$resultat = $reponse->fetchAll();
?>

<table class="table table-striped mt-4 w-75 mx-auto">
    <thead class="table-dark">
        <tr class="text-center">
            <th>Login</th>
            <th>Nom</th>
            <th>Prénom</th>
            <th>Rôle</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($resultat as $user) {
        ?>
            <tr>
                <td><?php echo $user['login'] ?></td>
                <td><?php echo $user['nom'] ?></td>
                <td><?php echo $user['prenom'] ?></td>
                <td><?php echo $user['role'] ?></td>
            </tr>
        <?php }
        ?>
    </tbody>
</table>