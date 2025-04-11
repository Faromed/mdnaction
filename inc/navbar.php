<nav class="navbar navbar-expand-lg navbar-light sticky-top shadow-sm">
    <!-- <div class="container"> -->
        <a class="navbar-brand d-flex align-items-center" href="index.php">
            <img src="img/logoMdn.png" alt="MDNAction Logo" height="40" class="me-2 m-2">
            <!-- <span class="fw-bold text-primary">MDNAction</span> -->
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item mx-1">
                    <a class="nav-link rounded-pill px-3 d-flex align-items-center" href="index.php">
                        <i class="fas fa-home me-2"></i>Accueil
                    </a>
                </li>
                <li class="nav-item mx-1">
                    <a class="nav-link rounded-pill px-3 d-flex align-items-center" href="about.php">
                        <i class="fas fa-info-circle me-2"></i>À propos
                    </a>
                </li>
                <li class="nav-item mx-1">
                    <a class="nav-link rounded-pill px-3 d-flex align-items-center" href="skills.php">
                        <i class="fas fa-tools me-2"></i>Compétences
                    </a>
                </li>
                <li class="nav-item mx-1">
                    <a class="nav-link rounded-pill px-3 d-flex align-items-center" href="formations.php">
                        <i class="fas fa-graduation-cap me-2"></i>Formations
                    </a>
                </li>
                <li class="nav-item mx-1">
                    <a class="nav-link rounded-pill px-3 d-flex align-items-center" href="services.php">
                        <i class="fas fa-concierge-bell me-2"></i>Services
                    </a>
                </li>
                <li class="nav-item mx-1">
                    <a class="nav-link rounded-pill px-3 d-flex align-items-center" href="portfolio.php">
                        <i class="fas fa-briefcase me-2"></i>Portfolio
                    </a>
                </li>
                <li class="nav-item mx-1">
                    <a class="nav-link rounded-pill px-3 d-flex align-items-center" href="blog.php">
                        <i class="fas fa-feather-alt me-2"></i>Blog
                    </a>
                </li>
                <li class="nav-item mx-1">
                    <a class="nav-link rounded-pill px-3 d-flex align-items-center" href="contact.php">
                        <i class="fas fa-envelope me-2"></i>Contact
                    </a>
                </li>
                <li class="nav-item ms-2">
                    <a class="btn btn-outline-success rounded-pill px-3 d-flex align-items-center" href="download_cv.php" download>
                        <i class="fas fa-download me-2"></i>Télécharger CV
                    </a>
                </li>
            </ul>
        </div>
    <!-- </div> -->
</nav>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Détection de la page active
    const currentPage = window.location.pathname.split('/').pop();
    const navLinks = document.querySelectorAll('.navbar-nav .nav-link');
    
    navLinks.forEach(link => {
        if (link.getAttribute('href') === currentPage) {
            link.classList.add('active', 'bg-success', 'bg-opacity-25');
            link.setAttribute('aria-current', 'page');
        }
    });
    
    // Effet de survol
    navLinks.forEach(link => {
        if (!link.classList.contains('btn')) {
            link.addEventListener('mouseenter', function() {
                if (!this.classList.contains('active')) {
                    this.classList.add('bg-light');
                }
            });
            
            link.addEventListener('mouseleave', function() {
                if (!this.classList.contains('active')) {
                    this.classList.remove('bg-light');
                }
            });
        }
    });
    
    // Effet de navbar au défilement
    window.addEventListener('scroll', function() {
        const navbar = document.querySelector('.navbar');
        if (window.scrollY > 50) {
            navbar.classList.add('bg-white');
            navbar.classList.remove('navbar-light');
            navbar.classList.add('navbar-dark', 'bg-primary');
        } else {
            navbar.classList.remove('bg-white');
            navbar.classList.add('navbar-light');
            navbar.classList.remove('navbar-dark', 'bg-primary');
        }
    });
});
</script>