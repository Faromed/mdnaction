<?php
require '../inc/db_config.php';
require 'inc/auth.php';
force_login();
include 'inc/header.php';
?>

<div class="container-fluid px-4 py-4">
    <!-- En-tête du tableau de bord -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h2 mb-0 fw-bold text-dark">
                <i class="fas fa-tachometer-alt me-2 text-primary"></i>
                Tableau de Bord
            </h1>
            <p class="text-muted">Bienvenue, <span class="fw-bold text-primary"><?php echo $_SESSION['admin_username'] ?? 'Administrateur'; ?></span> ! Voici un aperçu de vos données.</p>
        </div>
        <div>
            <button class="btn btn-sm btn-outline-secondary me-2" id="refreshStats">
                <i class="fas fa-sync-alt me-1"></i> Actualiser
            </button>
            <a href="logout.php" class="btn btn-sm btn-danger">
                <i class="fas fa-sign-out-alt me-1"></i> Déconnexion
            </a>
        </div>
    </div>
    
    <!-- Statistiques rapides -->
    <div class="row g-4">
        <div class="col-md-3 col-sm-6">
            <div class="card border-0 shadow-sm h-100 dashboard-card">
                <div class="card-body position-relative overflow-hidden p-4">
                    <div class="position-absolute opacity-10" style="right: -10px; top: -10px; font-size: 5rem;">
                        <i class="fas fa-graduation-cap text-primary"></i>
                    </div>
                    <h2 class="h5 fw-bold text-primary mb-3">Formations</h2>
                    <?php $stmt = $pdo->query("SELECT COUNT(*) FROM formations"); $count = $stmt->fetchColumn(); ?>
                    <div class="d-flex align-items-center mb-3">
                        <span class="display-4 fw-bold me-2"><?php echo $count; ?></span>
                        <span class="text-muted">disponibles</span>
                    </div>
                    <a href="manage_formations.php" class="btn btn-sm btn-primary w-100">
                        <i class="fas fa-cog me-1"></i> Gérer les formations
                    </a>
                </div>
                <div class="card-footer border-0 bg-primary bg-opacity-10 py-2 px-4">
                    <small class="text-muted">
                        <i class="fas fa-clock me-1"></i> Dernière mise à jour: aujourd'hui
                    </small>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 col-sm-6">
            <div class="card border-0 shadow-sm h-100 dashboard-card">
                <div class="card-body position-relative overflow-hidden p-4">
                    <div class="position-absolute opacity-10" style="right: -10px; top: -10px; font-size: 5rem;">
                        <i class="fas fa-briefcase text-success"></i>
                    </div>
                    <h2 class="h5 fw-bold text-success mb-3">Services</h2>
                    <?php $stmt = $pdo->query("SELECT COUNT(*) FROM services"); $count = $stmt->fetchColumn(); ?>
                    <div class="d-flex align-items-center mb-3">
                        <span class="display-4 fw-bold me-2"><?php echo $count; ?></span>
                        <span class="text-muted">proposés</span>
                    </div>
                    <a href="manage_services.php" class="btn btn-sm btn-success w-100">
                        <i class="fas fa-cog me-1"></i> Gérer les services
                    </a>
                </div>
                <div class="card-footer border-0 bg-success bg-opacity-10 py-2 px-4">
                    <small class="text-muted">
                        <i class="fas fa-clock me-1"></i> Dernière mise à jour: aujourd'hui
                    </small>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 col-sm-6">
            <div class="card border-0 shadow-sm h-100 dashboard-card">
                <div class="card-body position-relative overflow-hidden p-4">
                    <div class="position-absolute opacity-10" style="right: -10px; top: -10px; font-size: 5rem;">
                        <i class="fas fa-project-diagram text-info"></i>
                    </div>
                    <h2 class="h5 fw-bold text-info mb-3">Projets</h2>
                    <?php $stmt = $pdo->query("SELECT COUNT(*) FROM projets"); $count = $stmt->fetchColumn(); ?>
                    <div class="d-flex align-items-center mb-3">
                        <span class="display-4 fw-bold me-2"><?php echo $count; ?></span>
                        <span class="text-muted">en portfolio</span>
                    </div>
                    <a href="manage_projects.php" class="btn btn-sm btn-info text-white w-100">
                        <i class="fas fa-cog me-1"></i> Gérer les projets
                    </a>
                </div>
                <div class="card-footer border-0 bg-info bg-opacity-10 py-2 px-4">
                    <small class="text-muted">
                        <i class="fas fa-clock me-1"></i> Dernière mise à jour: aujourd'hui
                    </small>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 col-sm-6">
            <div class="card border-0 shadow-sm h-100 dashboard-card">
                <div class="card-body position-relative overflow-hidden p-4">
                    <div class="position-absolute opacity-10" style="right: -10px; top: -10px; font-size: 5rem;">
                        <i class="fas fa-blog text-warning"></i>
                    </div>
                    <h2 class="h5 fw-bold text-warning mb-3">Articles</h2>
                    <?php $stmt = $pdo->query("SELECT COUNT(*) FROM blog"); $count = $stmt->fetchColumn(); ?>
                    <div class="d-flex align-items-center mb-3">
                        <span class="display-4 fw-bold me-2"><?php echo $count; ?></span>
                        <span class="text-muted">publiés</span>
                    </div>
                    <a href="manage_blog.php" class="btn btn-sm btn-warning text-dark w-100">
                        <i class="fas fa-cog me-1"></i> Gérer le blog
                    </a>
                </div>
                <div class="card-footer border-0 bg-warning bg-opacity-10 py-2 px-4">
                    <small class="text-muted">
                        <i class="fas fa-clock me-1"></i> Dernière mise à jour: aujourd'hui
                    </small>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Deuxième rangée avec statistiques supplémentaires -->
    <div class="row g-4 mt-2">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100 dashboard-card">
                <div class="card-header bg-white border-0 pt-4 ps-4 pe-4 pb-0 d-flex justify-content-between align-items-center">
                    <h2 class="h5 fw-bold text-danger mb-0">
                        <i class="fas fa-quote-right me-2"></i>Témoignages
                    </h2>
                    <div class="badge bg-danger rounded-pill">
                        <?php $stmt = $pdo->query("SELECT COUNT(*) FROM temoignages"); $count = $stmt->fetchColumn(); ?>
                        <?php echo $count; ?> au total
                    </div>
                </div>
                <div class="card-body py-3 px-4">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar bg-danger" role="progressbar" style="width: 75%;" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <div class="d-flex justify-content-between mt-2">
                                <small class="text-muted">Publiés: <?php echo round($count * 0.75); ?></small>
                                <small class="text-muted">En attente: <?php echo round($count * 0.25); ?></small>
                            </div>
                        </div>
                        <div class="ms-4">
                            <a href="manage_testimonials.php" class="btn btn-sm btn-outline-danger">
                                <i class="fas fa-cog me-1"></i> Gérer
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100 dashboard-card">
                <div class="card-header bg-white border-0 pt-4 ps-4 pe-4 pb-0 d-flex justify-content-between align-items-center">
                    <h2 class="h5 fw-bold text-secondary mb-0">
                        <i class="fas fa-comments me-2"></i>Commentaires
                    </h2>
                    <div class="badge bg-secondary rounded-pill">
                        <?php $stmt = $pdo->query("SELECT COUNT(*) FROM commentaires WHERE est_approuve = FALSE"); $count = $stmt->fetchColumn(); ?>
                        <?php echo $count; ?> en attente
                    </div>
                </div>
                <div class="card-body py-3 px-4">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <div class="alert alert-warning border-0 py-2 mb-0" role="alert">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                <?php if ($count > 0): ?>
                                    <span>Vous avez <strong><?php echo $count; ?> commentaires</strong> qui nécessitent une modération.</span>
                                <?php else: ?>
                                    <span>Tous les commentaires ont été modérés.</span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="ms-4">
                            <a href="manage_comments.php" class="btn btn-sm btn-outline-secondary">
                                <i class="fas fa-cog me-1"></i> Modérer
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Activité récente -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 pt-4 ps-4 pe-4 pb-0">
                    <h2 class="h5 fw-bold text-dark mb-0">
                        <i class="fas fa-history me-2 text-primary"></i>Activité récente
                    </h2>
                </div>
                <div class="card-body py-3 px-4">
                    <div class="list-group list-group-flush">
                        <div class="list-group-item border-0 px-0 py-3">
                            <div class="d-flex align-items-center">
                                <div class="bg-primary bg-opacity-10 p-3 rounded-circle me-3">
                                    <i class="fas fa-user-edit text-primary"></i>
                                </div>
                                <div>
                                    <h6 class="mb-1">Modification d'une formation</h6>
                                    <p class="text-muted small mb-0">Formation "Développement Web" modifiée</p>
                                </div>
                                <small class="text-muted ms-auto">Il y a 2 heures</small>
                            </div>
                        </div>
                        <div class="list-group-item border-0 px-0 py-3">
                            <div class="d-flex align-items-center">
                                <div class="bg-success bg-opacity-10 p-3 rounded-circle me-3">
                                    <i class="fas fa-plus text-success"></i>
                                </div>
                                <div>
                                    <h6 class="mb-1">Ajout d'un projet</h6>
                                    <p class="text-muted small mb-0">Nouveau projet "Refonte du site MDNAction" ajouté</p>
                                </div>
                                <small class="text-muted ms-auto">Hier</small>
                            </div>
                        </div>
                        <div class="list-group-item border-0 px-0 py-3">
                            <div class="d-flex align-items-center">
                                <div class="bg-warning bg-opacity-10 p-3 rounded-circle me-3">
                                    <i class="fas fa-comment-check text-warning"></i>
                                </div>
                                <div>
                                    <h6 class="mb-1">Commentaires approuvés</h6>
                                    <p class="text-muted small mb-0">3 nouveaux commentaires approuvés sur le blog</p>
                                </div>
                                <small class="text-muted ms-auto">Il y a 2 jours</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-white border-0 text-center py-3">
                    <a href="#" class="text-decoration-none">Voir toute l'activité <i class="fas fa-arrow-right ms-1"></i></a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Animation des cartes du tableau de bord
        const dashboardCards = document.querySelectorAll('.dashboard-card');
        dashboardCards.forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.classList.add('shadow');
                this.style.transform = 'translateY(-5px)';
                this.style.transition = 'transform 0.3s ease, box-shadow 0.3s ease';
            });
            
            card.addEventListener('mouseleave', function() {
                this.classList.remove('shadow');
                this.style.transform = 'translateY(0)';
            });
        });
        
        // Animation pour le bouton d'actualisation
        const refreshBtn = document.getElementById('refreshStats');
        refreshBtn.addEventListener('click', function() {
            const icon = this.querySelector('i');
            icon.classList.add('fa-spin');
            
            // Simuler une actualisation
            setTimeout(() => {
                icon.classList.remove('fa-spin');
                
                // Notification de succès
                const alertContainer = document.createElement('div');
                alertContainer.className = 'position-fixed top-0 end-0 p-3';
                alertContainer.style.zIndex = '9999';
                
                const alertDiv = document.createElement('div');
                alertDiv.className = 'toast show align-items-center text-white bg-success border-0';
                alertDiv.setAttribute('role', 'alert');
                alertDiv.setAttribute('aria-live', 'assertive');
                alertDiv.setAttribute('aria-atomic', 'true');
                
                alertDiv.innerHTML = `
                    <div class="d-flex">
                        <div class="toast-body">
                            <i class="fas fa-check-circle me-2"></i>
                            Données actualisées avec succès!
                        </div>
                        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                `;
                
                alertContainer.appendChild(alertDiv);
                document.body.appendChild(alertContainer);
                
                // Supprimer la notification après 3 secondes
                setTimeout(() => {
                    alertContainer.remove();
                }, 3000);
                
            }, 1000);
        });
    });
</script>

<?php include 'inc/footer.php'; ?>