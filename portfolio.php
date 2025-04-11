<?php include 'inc/header.php'; ?>

<div class="container py-5">
    <!-- En-tête de section -->
    <div class="row mb-5">
        <div class="col-12 text-center">
            <div class="mb-4">
                <i class="fas fa-briefcase fa-3x text-success mb-3"></i>
                <h2 class="display-4 fw-bold">Mon Portfolio</h2>
                <div class="divider mx-auto my-3" style="width: 80px; height: 4px; background-color: #009891;"></div>
                <p class="lead text-muted">Découvrez mes projets et réalisations récentes</p>
            </div>
        </div>
    </div>

    <!-- Filtres de portfolio -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="d-flex flex-wrap justify-content-center gap-2">
                <button type="button" class="btn btn-success rounded-pill px-4 mb-2" data-filter="all">
                    <i class="fas fa-th-large me-2"></i>Tous les projets
                </button>
                <button type="button" class="btn btn-outline-success rounded-pill px-4 mb-2" data-filter="web">
                    <i class="fas fa-globe me-2"></i>Sites web
                </button>
                <button type="button" class="btn btn-outline-success rounded-pill px-4 mb-2" data-filter="app">
                    <i class="fas fa-mobile-alt me-2"></i>Applications
                </button>
                <button type="button" class="btn btn-outline-success rounded-pill px-4 mb-2" data-filter="design">
                    <i class="fas fa-paint-brush me-2"></i>Design
                </button>
            </div>
        </div>
    </div>

    <!-- Grille de projets -->
    <div class="row row-cols-1 row-cols-md-2 g-4 mb-5">
        <?php
        require 'inc/db_config.php';
        $stmt = $pdo->query("SELECT * FROM projets ORDER BY date_creation DESC");
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)):
            // Déterminer l'icône selon le type de projet
            $icon = 'fas fa-globe';
            if (stripos($row['technologies_utilisees'], 'mobil') !== false) {
                $icon = 'fas fa-mobile-alt';
            } elseif (stripos($row['technologies_utilisees'], 'design') !== false) {
                $icon = 'fas fa-paint-brush';
            }
        ?>
        <div class="col portfolio-item" data-category="web">
            <div class="card h-100 border-0 shadow-sm rounded-3 overflow-hidden">
                <div class="position-relative project-card">
                    <img src="img/<?php echo $row['image'] ? $row['image'] : 'default_project.jpg'; ?>" 
                         class="card-img-top project-image" alt="<?php echo $row['titre']; ?>"
                         style="height: 280px; object-fit: cover;">
                    <div class="project-overlay d-flex flex-column justify-content-center align-items-center p-4 text-white text-center">
                        <h3 class="h5 mb-3"><?php echo $row['titre']; ?></h3>
                        <p class="mb-4"><?php echo substr($row['description'], 0, 120); ?>...</p>
                        <div class="d-flex gap-2">
                            <?php if ($row['lien_live']): ?>
                                <a href="<?php echo $row['lien_live']; ?>" class="btn btn-light btn-sm" target="_blank">
                                    <i class="fas fa-external-link-alt me-1"></i>Voir le projet
                                </a>
                            <?php endif; ?>
                            <button type="button" class="btn btn-outline-light btn-sm" data-bs-toggle="modal" data-bs-target="#projectModal<?php echo $row['id']; ?>">
                                <i class="fas fa-info-circle me-1"></i>Détails
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0"><?php echo $row['titre']; ?></h5>
                    <span class="badge bg-light text-dark rounded-pill">
                        <i class="<?php echo $icon; ?> me-1"></i><?php echo $row['technologies_utilisees']; ?>
                    </span>
                </div>
            </div>
        </div>

        <!-- Modal pour ce projet -->
        <div class="modal fade" id="projectModal<?php echo $row['id']; ?>" tabindex="-1" aria-labelledby="projectModalLabel<?php echo $row['id']; ?>" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content border-0">
                    <div class="modal-header bg-success text-white">
                        <h5 class="modal-title" id="projectModalLabel<?php echo $row['id']; ?>">
                            <i class="<?php echo $icon; ?> me-2"></i><?php echo $row['titre']; ?>
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-0">
                        <div class="row g-0">
                            <div class="col-md-6">
                                <img src="img/<?php echo $row['image'] ? $row['image'] : 'default_project.jpg'; ?>" 
                                     class="img-fluid h-100" style="object-fit: cover;" alt="<?php echo $row['titre']; ?>">
                            </div>
                            <div class="col-md-6">
                                <div class="p-4">
                                    <h4 class="fw-bold mb-3">Description du projet</h4>
                                    <p><?php echo $row['description']; ?></p>
                                    
                                    <div class="mb-4">
                                        <h5 class="h6 fw-bold"><i class="fas fa-code me-2 text-success"></i>Technologies utilisées</h5>
                                        <div class="d-flex flex-wrap gap-2 mt-2">
                                            <?php 
                                            $techs = explode(',', $row['technologies_utilisees']);
                                            foreach($techs as $tech): 
                                                $tech = trim($tech);
                                            ?>
                                                <span class="badge bg-light text-dark"><?php echo $tech; ?></span>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                    
                                    <?php if ($row['lien_live']): ?>
                                    <div class="d-grid gap-2">
                                        <a href="<?php echo $row['lien_live']; ?>" class="btn btn-success" target="_blank">
                                            <i class="fas fa-external-link-alt me-2"></i>Visiter le projet
                                        </a>
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endwhile; ?>
    </div>
    
    <!-- Pagination -->
    <div class="row">
        <div class="col-12">
            <nav aria-label="Portfolio pagination">
                <ul class="pagination justify-content-center">
                    <li class="page-item disabled">
                        <a class="page-link" href="#" tabindex="-1" aria-disabled="true">
                            <i class="fas fa-chevron-left"></i>
                        </a>
                    </li>
                    <li class="page-item active"><a class="page-link text-success" href="#">1</a></li>
                    <li class="page-item"><a class="page-link text-success" href="#">2</a></li>
                    <li class="page-item"><a class="page-link text-success" href="#">3</a></li>
                    <li class="page-item">
                        <a class="page-link" href="#">
                            <i class="fas fa-chevron-right"></i>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
    
    <!-- CTA -->
    <div class="row mt-5">
        <div class="col-12">
            <div class="card bg-success text-white border-0 rounded-3 shadow">
                <div class="card-body p-5 text-center">
                    <h3 class="card-title mb-3">Vous avez un projet en tête ?</h3>
                    <p class="card-text mb-4">Je serais ravi de vous aider à le concrétiser. Contactez-moi pour en discuter !</p>
                    <a href="contact.php" class="btn btn-light btn-lg">
                        <i class="fas fa-paper-plane me-2"></i>Me contacter
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- CSS pour les effets de survol -->
<style>
    .project-card {
        position: relative;
        overflow: hidden;
    }
    
    .project-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: #009891;
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    
    .project-card:hover .project-overlay {
        opacity: 1;
    }
    
    .portfolio-item {
        transition: transform 0.3s ease;
    }
    
    .portfolio-item:hover {
        transform: translateY(-5px);
    }
</style>

<!-- Script pour les filtres -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const filterButtons = document.querySelectorAll('[data-filter]');
    
    filterButtons.forEach(button => {
        button.addEventListener('click', function() {
            const filterValue = this.getAttribute('data-filter');
            
            // Mise à jour des classes des boutons
            filterButtons.forEach(btn => {
                btn.classList.remove('btn-success');
                btn.classList.add('btn-outline-success');
            });
            
            this.classList.remove('btn-outline-success');
            this.classList.add('btn-success');
            
            // Logique de filtrage (à implémenter selon vos besoins)
        });
    });
});
</script>

<?php include 'inc/footer.php'; ?>