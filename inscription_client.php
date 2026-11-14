<div class="login-container">
    <div class="login-card shadow" style="max-width: 520px;">

        <h1>Inscription</h1>
        <p class="subtitle">Créez votre compte client</p>

        <?php if (isset($_GET["erreur"])) { ?>
            <div class="alert alert-warning" role="alert">
                <?php echo htmlspecialchars($_GET["erreur"]); ?>
            </div>
        <?php } ?>

        <form action="inscription_client_exe.php" method="POST" onsubmit="return verifierCoordonnees() && verifierMotDePasse();">

            <div class="mb-3">
                <label for="pseudo" class="form-label">Pseudo *</label>
                <input type="text" class="form-control custom-input" id="pseudo" name="pseudo" placeholder="Votre pseudo" maxlength="32" required>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="mdp" class="form-label">Mot de passe *</label>
                    <input type="password" class="form-control custom-input" id="mdp" name="mdp" maxlength="128" autocomplete="new-password" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="mdp2" class="form-label">Confirmer *</label>
                    <input type="password" class="form-control custom-input" id="mdp2" name="mdp2" maxlength="128" autocomplete="new-password" required>
                </div>
            </div>

            <p class="small text-muted text-start">
                Mot de passe : 12 caractères minimum, avec une minuscule, une majuscule, un chiffre et un caractère spécial.
            </p>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="nom" class="form-label">Nom *</label>
                    <input type="text" class="form-control custom-input" id="nom" name="nom" maxlength="50" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="prenom" class="form-label">Prénom *</label>
                    <input type="text" class="form-control custom-input" id="prenom" name="prenom" maxlength="50" required>
                </div>
            </div>

            <div class="mb-3">
                <label for="mail" class="form-label">Mail <span class="text-muted">(facultatif)</span></label>
                <input type="email" class="form-control custom-input" id="mail" name="mail" placeholder="votre@mail.com" maxlength="100">
            </div>

            <div class="mb-3">
                <label for="telephone" class="form-label">Téléphone <span class="text-muted">(facultatif)</span></label>
                <input type="tel" class="form-control custom-input" id="telephone" name="telephone" placeholder="06 12 34 56 78" maxlength="20">
            </div>

            <p class="small text-muted text-start">
                Vos données servent à créer votre compte et traiter vos commandes. Vous pouvez y accéder,
                les rectifier ou les supprimer en nous contactant.
                <a href="index.php?page=confidentialite" target="_blank">Politique de confidentialité</a>
            </p>

            <div class="form-check text-start mb-3">
                <input type="checkbox" class="form-check-input" id="consentement" name="consentement" value="1" required>
                <label class="form-check-label" for="consentement">
                    J'ai lu la politique de confidentialité et j'accepte le traitement de mes données *
                </label>
            </div>

            <div id="messageErreur" class="text-danger small mb-2"></div>

            <button type="submit" class="btn btn-cta w-100">Créer mon compte</button>

        </form>

        <div class="text-center mt-3">
            <span class="text-muted">Déjà un compte ?</span><br>
            <a href="index.php?page=connexion_client" class="signup-link">
                Se connecter
            </a>
        </div>
    </div>
</div>

<script src="script.js"></script>
