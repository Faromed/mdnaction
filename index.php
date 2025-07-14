<?php include 'inc/header.php'; ?>

<!-- Hero Section avec animation et image de fond -->
<div class="jumbotron text-white p-5 rounded-3 shadow position-relative overflow-hidden" style="background-image: url('img/banniere1.png'); background-size: cover; background-position: center;">
    <div class="container py-5">
        <div class="row">
            <div class="col-lg-8">
                <h1 class="display-4 fw-bold mb-4">Bienvenue chez MDNAction</h1>
                <p class="lead fs-4 mb-4">Votre partenaire pour des solutions web et logicielles innovantes</p>
                <hr class="my-4 opacity-50">
                <p class="mb-4">Découvrez mes derniers articles, formations et projets.</p>
                <div class="d-flex gap-3">
                    <a class="btn btn-light btn-lg shadow-sm" href="contact.php" role="button">
                        <i class="fas fa-envelope me-2"></i>Contactez-moi
                    </a>
                    <a class="btn btn-outline-light btn-lg" href="portfolio.php" role="button">
                        <i class="fas fa-briefcase me-2"></i>Voir mon portfolio
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="position-absolute bottom-0 end-0 d-none d-lg-block">
        <i class="fas fa-code-branch text-white opacity-25" style="font-size: 180px;"></i>
    </div>
</div>

<!-- Section Blog avec animation au défilement -->
<section class="py-5">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold">
                <i class="fas fa-feather-alt text-success me-2"></i>Derniers Articles du Blog
            </h2>
            <a href="blog.php" class="btn btn-outline-success rounded-pill">
                <i class="fas fa-arrow-right me-2"></i>Tous les articles
            </a>
        </div>
        <div class="row">
            <?php
            require 'inc/db_config.php';
            $stmt = $pdo->query("SELECT * FROM blog ORDER BY date_publication DESC LIMIT 3");
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)):
            ?>
            <div class="col-md-4 mb-4">
                <div class="card h-100 border-0 shadow-sm hover-effect">
                    <div class="position-relative">
                        <img src="img/<?php echo $row['image'] ? $row['image'] : 'default_blog.jpg'; ?>" class="card-img-top" alt="<?php echo $row['titre']; ?>">
                        <div class="position-absolute top-0 end-0 bg-light  px-3 py-1 m-2 rounded-pill">
                            <small><i class="far fa-calendar me-1"></i><?php echo date('d/m/Y', strtotime($row['date_publication'])); ?></small>
                        </div>
                    </div>
                    <div class="card-body">
                        <h5 class="card-title fw-bold"><?php echo $row['titre']; ?></h5>
                        <p class="card-text text-muted"><?php echo substr(strip_tags($row['contenu']), 0, 100); ?>...</p>
                    </div>
                    <div class="card-footer bg-white border-0">
                        <a href="blog_post.php?id=<?php echo $row['id']; ?>" class="btn btn-outline-success btn-sm rounded-pill">
                            <i class="fas fa-book-reader me-2"></i>Lire la suite
                        </a>
                    </div>
                </div>
            </div>
            <?php endwhile; ?> 
        </div>
    </div>
</section>

<!-- Section Formations avec gradient -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold">
                <i class="fas fa-graduation-cap text-success me-2"></i>Dernières Formations
            </h2>
            <a href="formations.php" class="btn btn-outline-success rounded-pill">
                <i class="fas fa-arrow-right me-2"></i>Toutes les formations
            </a>
        </div>
        <div class="row">
            <?php
            $stmt = $pdo->query("SELECT * FROM formations ORDER BY date_creation DESC LIMIT 3");
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)):
            ?>
            <div class="col-md-4 mb-4">
                <div class="card h-100 border-0 shadow-sm hover-effect">
                    <div class="position-relative">
                        <img src="img/<?php echo $row['miniature'] ? $row['miniature'] : 'img/img1.webp'; ?>" class="card-img-top" alt="<?php echo $row['titre']; ?>">
                        <div class="position-absolute border border-light border-2 bottom-0 start-0 bg-warning text-dark px-3 py-1 m-2 rounded-pill">
                            <small><i class="far fa-clock me-1"></i><?php echo $row['duree']; ?></small>
                        </div>
                    </div>
                    <div class="card-body">
                        <h5 class="card-title fw-bold"><?php echo $row['titre']; ?></h5>
                        <p class="card-text text-muted"><?php echo substr($row['description'], 0, 100); ?>...</p>
                    </div>
                    <div class="card-footer bg-white border-0">
                        <a href="formation_details.php?id=<?php echo $row['id']; ?>" class="btn btn-outline-success btn-sm rounded-pill">
                            <i class="fas fa-info-circle me-2"></i>En savoir plus
                        </a>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
    </div>
</section>

<!-- Section des services avec icônes et animations -->
<section class="py-5">
    <div class="container">
        <h2 class="fw-bold text-center mb-5">
            <i class="fas fa-bolt text-warning me-2"></i>Accès Rapide
        </h2>
        <div class="row g-4">
            <div class="col-md-4 mb-4">
                <div class="card border-0 shadow-sm text-center h-100 hover-effect">
                    <div class="card-body p-4">
                        <div class="rounded-circle bg-success bg-opacity-10 p-3 d-inline-block mb-3">
                            <i class="fas fa-briefcase fa-3x text-success"></i>
                        </div>
                        <h5 class="card-title fw-bold">
                            <a href="portfolio.php" class="text-decoration-none text-dark stretched-link">Mes Projets</a>
                        </h5>
                        <p class="card-text text-muted">Découvrez mes réalisations et mon expertise dans divers domaines techniques.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card border-0 shadow-sm text-center h-100 hover-effect">
                    <div class="card-body p-4">
                        <div class="rounded-circle bg-info bg-opacity-10 p-3 d-inline-block mb-3">
                            <i class="fas fa-rss fa-3x text-info"></i>
                        </div>
                        <h5 class="card-title fw-bold">
                            <a href="blog.php" class="text-decoration-none text-dark stretched-link">Mon Blog</a>
                        </h5>
                        <p class="card-text text-muted">Partage de contenu technique, conseils et actualités du monde du développement.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card border-0 shadow-sm text-center h-100 hover-effect">
                    <div class="card-body p-4">
                        <div class="rounded-circle bg-success bg-opacity-10 p-3 d-inline-block mb-3">
                            <i class="fas fa-envelope fa-3x text-success"></i>
                        </div>
                        <h5 class="card-title fw-bold">
                            <a href="contact.php" class="text-decoration-none text-dark stretched-link">Contactez-moi</a>
                        </h5>
                        <p class="card-text text-muted">N'hésitez pas à me contacter pour discuter de vos projets et besoins.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Effet hover pour les cartes
    const cards = document.querySelectorAll('.hover-effect');
    cards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.classList.add('shadow');
            this.style.transform = 'translateY(-5px)';
            this.style.transition = 'all 0.3s ease';
        });
        
        card.addEventListener('mouseleave', function() {
            this.classList.remove('shadow');
            this.style.transform = 'translateY(0)';
        });
    });
});
</script>

<?php include 'inc/footer.php'; ?>