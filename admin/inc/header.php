<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - MDNAction</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"/>
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold d-flex align-items-center" href="dashboard.php">
            <span class="bg-light text-dark p-1 rounded me-2">
                <i class="fas fa-gauge-high"></i>
            </span>
            MDNAction Admin
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item px-1">
                    <a class="nav-link rounded-3 d-flex align-items-center active" href="dashboard.php">
                        <i class="fas fa-home me-2"></i>Tableau de Bord
                    </a>
                </li>
                <li class="nav-item px-1">
                    <a class="nav-link rounded-3 d-flex align-items-center" href="manage_formations.php">
                        <i class="fas fa-graduation-cap me-2"></i>Formations
                    </a>
                </li>
                <li class="nav-item px-1">
                    <a class="nav-link rounded-3 d-flex align-items-center" href="manage_services.php">
                        <i class="fas fa-briefcase me-2"></i>Services
                    </a>
                </li>
                <li class="nav-item px-1">
                    <a class="nav-link rounded-3 d-flex align-items-center" href="manage_projects.php">
                        <i class="fas fa-project-diagram me-2"></i>Projets
                    </a>
                </li>
                <li class="nav-item px-1">
                    <a class="nav-link rounded-3 d-flex align-items-center" href="manage_blog.php">
                        <i class="fas fa-blog me-2"></i>Blog
                    </a>
                </li>
                <li class="nav-item px-1">
                    <a class="nav-link rounded-3 d-flex align-items-center" href="manage_testimonials.php">
                        <i class="fas fa-quote-right me-2"></i>Témoignages
                    </a>
                </li>
                <li class="nav-item px-1">
                    <a class="nav-link rounded-3 d-flex align-items-center" href="manage_comments.php">
                        <i class="fas fa-comments me-2"></i>Commentaires
                    </a>
                </li>
            </ul>
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link btn btn-danger btn-sm rounded-3 d-flex align-items-center" href="logout.php">
                        <i class="fas fa-sign-out-alt me-2"></i>Déconnexion
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container mt-4">
    <!-- Contenu de la page -->
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Ajouter la classe active au lien de navigation actuel
    document.addEventListener('DOMContentLoaded', function() {
        const currentLocation = window.location.pathname;
        const navLinks = document.querySelectorAll('.navbar-nav .nav-link');
        
        navLinks.forEach(link => {
            if (link.getAttribute('href') === currentLocation.split('/').pop()) {
                link.classList.add('active');
                link.classList.add('bg-primary');
            } else {
                link.classList.remove('active');
                link.classList.remove('bg-primary');
            }
        });
        
        // Effet de survol pour les liens
        navLinks.forEach(link => {
            link.addEventListener('mouseenter', function() {
                if (!this.classList.contains('active')) {
                    this.classList.add('bg-secondary', 'bg-opacity-25');
                }
            });
            
            link.addEventListener('mouseleave', function() {
                if (!this.classList.contains('active')) {
                    this.classList.remove('bg-secondary', 'bg-opacity-25');
                }
            });
        });
    });
</script>
</body>
</html>