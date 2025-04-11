</main>

<footer class="bg-dark bg-opacity-80 text-light py-5 mt-5">
    <div class="container">
        <!-- Section principale du footer -->
        <div class="row g-4">
            <!-- Colonne informations de contact -->
            <div class="col-lg-4 mb-4 mb-lg-0">
                <h5 class="fw-bold mb-3">MDNAction</h5>
                <div class="border-start border-success border-3 ps-3 mb-4">
                    <p class="mb-1 text-light opacity-75">Solutions Web Professionnelles</p>
                </div>
                <div class="d-flex align-items-center mb-3">
                    <div class="me-3 text-success">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                    <div>
                        <p class="mb-0 text-light opacity-75">Cotonou, Bénin</p>
                    </div>
                </div>
                <div class="d-flex align-items-center mb-3">
                    <div class="me-3 text-success">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <div>
                        <a href="mailto:mdnaction1@gmail.com" class="text-light opacity-75 text-decoration-none">mdnaction1@gmail.com</a>
                    </div>
                </div>
                <div class="d-flex align-items-center">
                    <div class="me-3 text-success">
                        <i class="fas fa-phone"></i>
                    </div>
                    <div>
                        <a href="tel:+22901649797" class="text-light opacity-75 text-decoration-none">+229 01 64 97 97 90</a>
                    </div>
                </div>
            </div>
            
            <!-- Colonne liens rapides -->
            <div class="col-md-4 col-lg-2 mb-4 mb-md-0">
                <h5 class="fw-bold mb-3">Liens Rapides</h5>
                <ul class="list-unstyled">
                    <li class="mb-2">
                        <a href="index.php" class="text-light opacity-75 text-decoration-none">
                            <i class="fas fa-angle-right me-2 text-success"></i>Accueil
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="about.php" class="text-light opacity-75 text-decoration-none">
                            <i class="fas fa-angle-right me-2 text-success"></i>À Propos
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="skills.php" class="text-light opacity-75 text-decoration-none">
                            <i class="fas fa-angle-right me-2 text-success"></i>Compétences
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="contact.php" class="text-light opacity-75 text-decoration-none">
                            <i class="fas fa-angle-right me-2 text-success"></i>Contact
                        </a>
                    </li>
                </ul>
            </div>
            
            <!-- Colonne services -->
            <div class="col-md-4 col-lg-2 mb-4 mb-md-0">
                <h5 class="fw-bold mb-3">Services</h5>
                <ul class="list-unstyled">
                    <li class="mb-2">
                        <a href="#" class="text-light opacity-75 text-decoration-none">
                            <i class="fas fa-angle-right me-2 text-success"></i>Développement Web
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="#" class="text-light opacity-75 text-decoration-none">
                            <i class="fas fa-angle-right me-2 text-success"></i>Formation
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="#" class="text-light opacity-75 text-decoration-none">
                            <i class="fas fa-angle-right me-2 text-success"></i>Consulting
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="#" class="text-light opacity-75 text-decoration-none">
                            <i class="fas fa-angle-right me-2 text-success"></i>E-commerce
                        </a>
                    </li>
                </ul>
            </div>
            
            <!-- Colonne newsletter -->
            <div class="col-lg-4">
                <h5 class="fw-bold mb-3">Newsletter</h5>
                <p class="text-light opacity-75">Abonnez-vous pour recevoir mes conseils et actualités</p>
                <form action="#" method="post" class="mb-3">
                    <div class="input-group">
                        <input type="email" class="form-control" placeholder="Votre adresse email" aria-label="Email" required>
                        <button class="btn btn-success" type="submit">
                            <i class="fas fa-paper-plane"></i>
                        </button>
                    </div>
                </form>
                <div class="d-flex gap-2">
                    <a href="#" class="btn btn-sm btn-outline-light rounded-circle" title="Facebook">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a href="#" class="btn btn-sm btn-outline-light rounded-circle" title="Twitter">
                        <i class="fab fa-twitter"></i>
                    </a>
                    <a href="#" class="btn btn-sm btn-outline-light rounded-circle" title="Instagram">
                        <i class="fab fa-instagram"></i>
                    </a>
                    <a href="#" class="btn btn-sm btn-outline-light rounded-circle" title="LinkedIn">
                        <i class="fab fa-linkedin-in"></i>
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Barre de copyright -->
        <div class="row mt-4 pt-4 border-top border-secondary">
            <div class="col-md-6 text-center text-md-start">
                <p class="mb-md-0 text-light opacity-75">&copy; <?php echo date('Y'); ?> MDNAction. Tous droits réservés.</p>
            </div>
            <div class="col-md-6 text-center text-md-end">
                <p class="mb-0 text-light opacity-75">Conçu avec <i class="fas fa-heart text-danger"></i> par MDNAction</p>
            </div>
        </div>
    </div>
</footer>

<script src="js/bootstrap.bundle.min.js"></script>
<script src="js/script.js"></script>

<!-- Script pour activer des effets sur le défilement -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Animation subtile pour les liens du footer
    const footerLinks = document.querySelectorAll('footer a');
    footerLinks.forEach(link => {
        link.addEventListener('mouseenter', function() {
            this.classList.add('text-success');
            this.style.opacity = '1';
        });
        link.addEventListener('mouseleave', function() {
            this.classList.remove('text-success');
            this.style.opacity = '';
        });
    });
});
</script>
</body>
</html>