<div class="login-container">

    <div class="login-card shadow">

        <h1>Connexion</h1>
        <p class="subtitle">Accédez à votre espace client</p>

        <form action="connexion_client_exe.php" method="POST">

            <div class="mb-3">
                <label for="pseudo" class="form-label">Pseudo</label>
                <input type="text" class="form-control custom-input" id="pseudo" name="pseudo" placeholder="Votre pseudo" required>
            </div>

            <div class="mb-2">
                <label class="form-label">Mot de passe</label>
                <input type="password" class="form-control custom-input" id="mdp" name="mdp" required>
            </div>

            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="remember">
                    <label class="form-check-label" for="remember">Se souvenir de moi</label>
                </div>

                <a href="#" class="forgot-link">Mot de passe oublié ?</a>
            </div>

            <button type="submit" class="btn btn-cta w-100">Se connecter</button>

        </form>

        <div class="text-center mt-3">
            <span class="text-muted">Pas encore de compte ?</span><br>
            <a href="index.php?page=inscription_client" class="signup-link">
                Créer un compte
            </a>
        </div>

    </div>

</div>