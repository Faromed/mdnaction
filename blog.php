<?php include 'inc/header.php'; ?>

<div class="container py-5">
    <!-- Header de la page -->
    <div class="row mb-5">
        <div class="col-lg-8 mx-auto text-center">
            <h1 class="fw-bold display-5 mb-3">
                <i class="fas fa-blog text-primary me-2"></i>Mon Blog
            </h1>
            <p class="lead text-muted">Retrouvez ici mes articles techniques, tutoriels et réflexions sur le monde du développement web et logiciel.</p>
        </div>
    </div>

    <!-- Filtres et recherche -->
    <div class="card border-0 shadow-sm mb-5">
        <div class="card-body p-4">
            <div class="row g-3 align-items-center">
                <div class="col-md-8">
                    <form class="d-flex">
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0">
                                <i class="fas fa-search text-muted"></i>
                            </span>
                            <input type="text" class="form-control border-start-0 bg-light" placeholder="Rechercher un article...">
                        </div>
                        <button type="submit" class="btn btn-primary ms-2 px-4">Rechercher</button>
                    </form>
                </div>
                <div class="col-md-4">
                    <select class="form-select">
                        <option selected>Trier par date</option>
                        <option>Les plus récents</option>
                        <option>Les plus anciens</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- Liste des articles -->
    <div class="row row-cols-1 row-cols-md-2 g-4">
        <?php
        require 'inc/db_config.php';
        $stmt = $pdo->query("SELECT * FROM blog ORDER BY date_publication DESC");
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)):
        ?>
        <div class="col">
            <div class="card h-100 border-0 shadow-sm hover-card">
                <div class="position-relative">
                    <img src="img/<?php echo $row['image'] ? $row['image'] : 'default_blog.jpg'; ?>" class="card-img-top" alt="<?php echo $row['titre']; ?>">
                    <div class="position-absolute top-0 end-0 bg-primary text-white px-3 py-1 m-3 rounded-pill">
                        <small><i class="far fa-calendar-alt me-1"></i><?php echo date('d/m/Y', strtotime($row['date_publication'])); ?></small>
                    </div>
                </div>
                <div class="card-body p-4">
                    <h4 class="card-title fw-bold mb-3"><?php echo $row['titre']; ?></h4>
                    <p class="card-text text-muted mb-4"><?php echo substr(strip_tags($row['contenu']), 0, 150); ?>...</p>
                    <div class="d-flex justify-content-between align-items-center">
                        <a href="blog_post.php?id=<?php echo $row['id']; ?>" class="btn btn-outline-primary rounded-pill px-4">
                            <i class="fas fa-book-reader me-2"></i>Lire la suite
                        </a>
                        <?php
                        // Afficher le nombre de commentaires
                        $comments_count = $pdo->query("SELECT COUNT(*) FROM commentaires WHERE article_id = {$row['id']} AND est_approuve = TRUE")->fetchColumn();
                        ?>
                        <span class="text-muted">
                            <i class="fas fa-comments me-1"></i> <?php echo $comments_count; ?> commentaire<?php echo $comments_count > 1 ? 's' : ''; ?>
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <?php endwhile; ?>
    </div>

    <!-- Pagination -->
    <nav class="mt-5">
        <ul class="pagination justify-content-center">
            <li class="page-item disabled">
                <a class="page-link" href="#" tabindex="-1" aria-disabled="true">
                    <i class="fas fa-chevron-left me-1"></i>Précédent
                </a>
            </li>
            <li class="page-item active" aria-current="page">
                <a class="page-link" href="#">1</a>
            </li>
            <li class="page-item">
                <a class="page-link" href="#">2</a>
            </li>
            <li class="page-item">
                <a class="page-link" href="#">3</a>
            </li>
            <li class="page-item">
                <a class="page-link" href="#">
                    Suivant<i class="fas fa-chevron-right ms-1"></i>
                </a>
            </li>
        </ul>
    </nav>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Effet de survol pour les cartes
    const cards = document.querySelectorAll('.hover-card');
    cards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-10px)';
            this.style.transition = 'transform 0.3s ease';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });
});
</script>

<?php include 'inc/footer.php'; ?>