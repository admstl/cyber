<div class="container contact-container">

    <div class="contact-card shadow">

        <h1 class="text-center">Contact</h1>
        <p class="text-center subtitle">Une question ? Notre équipe vous répond rapidement.</p>

        <form>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Nom</label>
                    <input type="text" class="form-control custom-input" placeholder="Votre nom">
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Prénom</label>
                    <input type="text" class="form-control custom-input" placeholder="Votre prénom">
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">E-mail</label>
                <input type="email" class="form-control custom-input" placeholder="exemple@mail.com">
            </div>

            <div class="mb-3">
                <label class="form-label">Téléphone</label>
                <input type="tel" class="form-control custom-input" placeholder="06 12 34 56 78">
            </div>

            <div class="mb-3">
                <label class="form-label">Message</label>
                <textarea class="form-control custom-input" rows="4" placeholder="Votre message..."></textarea>
            </div>

            <div class="form-check mb-3">
                <input type="checkbox" class="form-check-input" id="newsletter">
                <label class="form-check-label" for="newsletter">
                    S’inscrire à la newsletter
                </label>
            </div>

            <button type="submit" class="btn btn-cta w-100" onclick="verifierCoordonné()">
                Envoyer le message
            </button>

            <div id="messageErreur" class="text-danger text-center mt-3"></div>

        </form>

    </div>

</div>