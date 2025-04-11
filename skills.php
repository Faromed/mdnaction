<?php include 'inc/header.php'; ?>

<div class="container py-5">
    <!-- En-tête de la page -->
    <div class="bg-white shadow-sm rounded-3 p-4 mb-5">
        <h1 class="display-4 text-center fw-bold text-primary">Mes Compétences</h1>
        <div class="text-center">
            <div class="border-bottom border-2 border-primary mx-auto mb-3" style="width: 100px;"></div>
        </div>
        <p class="lead text-center mb-0">
            <i class="fas fa-quote-left text-primary me-2 opacity-50"></i>
            Découvrez mon expertise technique et professionnelle
            <i class="fas fa-quote-right text-primary ms-2 opacity-50"></i>
        </p>
    </div>
    
    <!-- Introduction -->
    <div class="row mb-5">
        <div class="col-lg-8 mx-auto">
            <div class="card border-0 shadow-sm rounded-3 p-4 bg-gradient" style="background: linear-gradient(45deg, #f8f9fa, #ffffff);">
                <div class="d-flex align-items-center mb-3">
                    <div class="rounded-circle bg-primary p-2 me-3">
                        <i class="fas fa-code text-white"></i>
                    </div>
                    <h2 class="h4 mb-0">Expertise Technique</h2>
                </div>
                <p>
                    Au fil de mon parcours professionnel, j'ai développé une expertise approfondie dans plusieurs domaines clés du développement web et de la formation. Mes compétences sont régulièrement mises à jour pour rester à la pointe de l'innovation technologique.
                </p>
            </div>
        </div>
    </div>
    
    <!-- Filtre des compétences -->
    <div class="d-flex justify-content-center flex-wrap gap-2 mb-4">
        <button class="btn btn-outline-primary rounded-pill active" data-filter="all">
            <i class="fas fa-th me-2"></i>Toutes
        </button>
        <button class="btn btn-outline-primary rounded-pill" data-filter="frontend">
            <i class="fas fa-laptop-code me-2"></i>Frontend
        </button>
        <button class="btn btn-outline-primary rounded-pill" data-filter="backend">
            <i class="fas fa-server me-2"></i>Backend
        </button>
        <button class="btn btn-outline-primary rounded-pill" data-filter="autres">
            <i class="fas fa-tools me-2"></i>Autres
        </button>
    </div>

    <!-- Grille des compétences -->
    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
        <?php
        require 'inc/db_config.php';
        $stmt = $pdo->query("SELECT * FROM competences ORDER BY titre ASC");
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)):
            // Définition de catégories fictives pour la démo (à adapter selon vos données réelles)
            $categories = ['frontend', 'backend', 'autres'];
            $category = $categories[array_rand($categories)]; // Attribue une catégorie aléatoire
            
            // Définition d'icônes par défaut selon la catégorie
            $defaultIcons = [
                'frontend' => 'fa-palette',
                'backend' => 'fa-database',
                'autres' => 'fa-cogs'
            ];
            
            // Couleurs de fond pour les icônes selon la catégorie
            $bgColors = [
                'frontend' => 'bg-info',
                'backend' => 'bg-success',
                'autres' => 'bg-warning'
            ];
        ?>
        <div class="col skill-item" data-category="<?php echo $category; ?>">
            <div class="card h-100 border-0 shadow-sm hover-shadow transition-300">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-3">
                        <?php if ($row['icone']): ?>
                            <div class="rounded-circle <?php echo $bgColors[$category]; ?> bg-opacity-25 p-3 me-3">
                                <img src="img/icons/<?php echo $row['icone']; ?>" class="img-fluid" style="width: 30px; height: 30px;" alt="<?php echo $row['titre']; ?>">
                            </div>
                        <?php else: ?>
                            <div class="rounded-circle <?php echo $bgColors[$category]; ?> bg-opacity-25 p-3 me-3">
                                <i class="fas <?php echo $defaultIcons[$category]; ?> text-<?php echo str_replace('bg-', '', $bgColors[$category]); ?>"></i>
                            </div>
                        <?php endif; ?>
                        <h3 class="h5 card-title mb-0"><?php echo $row['titre']; ?></h3>
                    </div>
                    
                    <p class="card-text"><?php echo $row['description']; ?></p>
                    
                    <!-- Barre de niveau (valeur aléatoire pour la démo) -->
                    <?php 
                        $level = rand(60, 95); // Niveau aléatoire entre 60 et 95%
                        $levelClass = $level > 85 ? 'bg-success' : ($level > 70 ? 'bg-primary' : 'bg-info');
                    ?>
                    <div class="progress mt-3" style="height: 8px;">
                        <div class="progress-bar <?php echo $levelClass; ?>" role="progressbar" style="width: <?php echo $level; ?>%" aria-valuenow="<?php echo $level; ?>" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <div class="d-flex justify-content-between mt-1">
                        <small class="text-muted">Niveau</small>
                        <small class="text-muted"><?php echo $level; ?>%</small>
                    </div>
                </div>
            </div>
        </div>
        <?php endwhile; ?>
    </div>
    
    <!-- Section de contact / CTA -->
    <div class="row mt-5">
        <div class="col-lg-8 mx-auto">
            <div class="card border-0 shadow-sm rounded-3 p-4 bg-primary bg-opacity-10">
                <div class="card-body text-center">
                    <h3 class="h4 mb-3">Vous avez un projet en tête ?</h3>
                    <p class="mb-4">N'hésitez pas à me contacter pour discuter de vos besoins et comment je peux vous aider à les réaliser.</p>
                    <a href="contact.php" class="btn btn-primary">
                        <i class="fas fa-envelope me-2"></i>Me contacter
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Script pour le filtrage des compétences -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const filterButtons = document.querySelectorAll('[data-filter]');
    const skillItems = document.querySelectorAll('.skill-item');
    
    filterButtons.forEach(button => {
        button.addEventListener('click', function() {
            // Retirer la classe active de tous les boutons
            filterButtons.forEach(btn => btn.classList.remove('active'));
            // Ajouter la classe active au bouton cliqué
            this.classList.add('active');
            
            const filter = this.getAttribute('data-filter');
            
            skillItems.forEach(item => {
                if (filter === 'all' || item.getAttribute('data-category') === filter) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    });
    
    // Effet de survol pour les cartes
    const cards = document.querySelectorAll('.hover-shadow');
    cards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-5px)';
            this.style.boxShadow = '0 .5rem 1rem rgba(0,0,0,.15)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
            this.style.boxShadow = '0 .125rem .25rem rgba(0,0,0,.075)';
        });
    });
});
</script>

<!-- CSS personnalisé -->
<style>
.transition-300 {
    transition: all 0.3s ease;
}

.hover-shadow {
    transition: all 0.3s ease;
}
</style>

<?php include 'inc/footer.php'; ?>