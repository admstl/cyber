<form action="connexion_exe.php" method="post">
    <div class="container text-center">
        <h1 style="margin: 20px;">Connexion à la partie "Administration"</h1>
        <div>
            <label for="nom">Login :</label>
            <input name="login" type="login" id="login">
        </div>
        <div>
            <label>Mot de passe :</label>
            <input type="password" id="password" name="password">
        </div>
        <div>
            <button type="submit">Valider</button>
        </div>
        <div id="messageErreur" class="text-danger"></div><br>
    </div>

    <?php
    if (isset($_GET["erreur"])) {
        // Message d'erreur reçu dans l'URL
        $erreur = $_GET["erreur"];
    ?>
        <div class="alert alert-warning role=alert">
            <!-- htmlspecialchars empêche l'injection de code dans la page (faille XSS) -->
            <?php echo htmlspecialchars($erreur) ?>
        </div>
    <?php
    }
    ?>

</form>