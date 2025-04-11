<?php include 'inc/header.php'; ?>

<h2>Contactez-moi</h2>
<p class="lead">N'hésitez pas à me contacter pour toute question ou demande de prestation.</p>

<div class="row">
    <div class="col-md-6">
        <h3>Formulaire de contact</h3>
        <form action="#" method="post">
            <div class="mb-3">
                <label for="nom" class="form-label">Nom</label>
                <input type="text" class="form-control" id="nom" name="nom" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Adresse Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="mb-3">
                <label for="sujet" class="form-label">Sujet</label>
                <input type="text" class="form-control" id="sujet" name="sujet" required>
            </div>
            <div class="mb-3">
                <label for="message" class="form-label">Message</label>
                <textarea class="form-control" id="message" name="message" rows="5" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Envoyer le message</button>
        </form>
        <div class="mt-3">
            <?php
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                // Ici, vous pouvez ajouter le code pour envoyer l'e-mail
                // ou enregistrer le message dans une base de données.
                echo '<div class="alert alert-success">Votre message a été envoyé. Je vous répondrai dans les plus brefs délais.</div>';
            }
            ?>
        </div>
    </div>
    <div class="col-md-6">
        <h3>Mes coordonnées</h3>
        <p><i class="fas fa-envelope"></i> <strong>Email :</strong> <a href="mailto:mdnaction1@gmail.com">mdnaction1@gmail.com</a></p>
        <p><i class="fas fa-phone"></i> <strong>Téléphone :</strong> +229 01 64 97 97 90</p>
        <h3 class="mt-4">Localisation</h3>
        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3965.14097347923!2d2.360748474796843!3d6.36315779363995!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x102340a8f1c7b37f%3A0x3a0d5f9a8b8b8b8b!2sVotre%20adresse%20ici!5e0!3m2!1sfr!2sbj!4v1633000000000!5m2!1sfr!2sbj" width="100%" height="300" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
        <p class="text-muted mt-2"><small>Vous pouvez remplacer l'URL de la carte par votre localisation.</small></p>
    </div>
</div>

<?php include 'inc/footer.php'; ?>