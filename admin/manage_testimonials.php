<?php 
require '../inc/db_config.php'; 
require 'inc/auth.php'; 
force_login(); 
include 'inc/header.php'; 
include 'inc/functions.php';  

// Pour la recherche et le tri
$search = isset($_GET['search']) ? $_GET['search'] : '';
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'id';
$order = isset($_GET['order']) ? $_GET['order'] : 'ASC';

$testimonials = get_all($pdo, 'temoignages');

// Si une recherche est effectuée
if (!empty($search)) {
    // Filtrer les témoignages qui contiennent le terme de recherche dans le nom ou la profession
    $testimonials = array_filter($testimonials, function($testimonial) use ($search) {
        return stripos($testimonial['nom'], $search) !== false || stripos($testimonial['profession'], $search) !== false;
    });
}

// Trier les témoignages
if (!empty($testimonials)) {
    $column = array_column($testimonials, $sort);
    $direction = ($order === 'ASC') ? SORT_ASC : SORT_DESC;
    array_multisort($column, $direction, $testimonials);
}

// Calcul des statistiques
$total_testimonials = count($testimonials);

// Calcul des témoignages par profession
$professions = [];
$recent_count = 0;
$current_month = date('m');
$current_year = date('Y');

foreach ($testimonials as $testimonial) {
    $profession = $testimonial['profession'];
    if (!isset($professions[$profession])) {
        $professions[$profession] = 0;
    }
    $professions[$profession]++;
    
    // Compter les témoignages du mois en cours
    $testimonial_month = date('m', strtotime($testimonial['date_creation']));
    $testimonial_year = date('Y', strtotime($testimonial['date_creation']));
    
    if ($testimonial_month == $current_month && $testimonial_year == $current_year) {
        $recent_count++;
    }
}

// Obtenir la profession la plus fréquente
$top_profession = !empty($professions) ? array_search(max($professions), $professions) : 'Aucune';

// Pagination Logic
$items_per_page = 10; // Nombre de projets par page
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // Page actuelle

// Appel de la fonction de pagination
$pagination_data = get_all_paginated($pdo, 'temoignages', $items_per_page, $current_page, 'date_creation');

$testimonials = $pagination_data['items'];
$total_testimonials = $pagination_data['total_items'];
$total_pages = $pagination_data['total_pages'];
$current_page = $pagination_data['current_page'];
// --- End Pagination Logic ---
?>

<div class="container-fluid px-4 py-4">
    <!-- En-tête de la page avec breadcrumb -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="dashboard.php" class="text-decoration-none text-success"><i class="fas fa-tachometer-alt"></i> Tableau de bord</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Témoignages</li>
                </ol>
            </nav>
            <h1 class="h2 mb-0 mt-2 fw-bold">
                <i class="fas fa-comments text-success me-2"></i>Gestion des Témoignages
            </h1>
        </div>
        <div>
            <a href="add_testimonial.php" class="btn btn-success">
                <i class="fas fa-plus-circle me-2"></i>Nouveau témoignage
            </a>
        </div>
    </div>
    
    <!-- Alertes de succès ou d'erreur -->
    <div id="alerts-container">
        <?php if (isset($_SESSION['success_message'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>
                <?php echo $_SESSION['success_message']; unset($_SESSION['success_message']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
        
        <?php if (isset($_SESSION['error_message'])): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>
                <?php echo $_SESSION['error_message']; unset($_SESSION['error_message']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
    </div>
    
    <!-- Cartes de statistiques -->
    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="rounded-circle bg-success bg-opacity-10 p-3 me-3">
                        <i class="fas fa-comment-dots text-success fa-2x"></i>
                    </div>
                    <div>
                        <h6 class="text-muted mb-1">Total des témoignages</h6>
                        <h3 class="mb-0 fw-bold"><?php echo $total_testimonials; ?></h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="rounded-circle bg-success bg-opacity-10 p-3 me-3">
                        <i class="fas fa-briefcase text-success fa-2x"></i>
                    </div>
                    <div>
                        <h6 class="text-muted mb-1">Profession la plus fréquente</h6>
                        <h3 class="mb-0 fw-bold"><?php echo htmlspecialchars($top_profession); ?></h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="rounded-circle bg-success bg-opacity-10 p-3 me-3">
                        <i class="fas fa-calendar-alt text-success fa-2x"></i>
                    </div>
                    <div>
                        <h6 class="text-muted mb-1">Ajoutés ce mois-ci</h6>
                        <h3 class="mb-0 fw-bold"><?php echo $recent_count; ?></h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Barre d'outils -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-3">
            <div class="row g-3 align-items-center">
                <div class="col-md-6">
                    <form action="" method="get" class="d-flex">
                        <div class="input-group">
                            <span class="input-group-text bg-light">
                                <i class="fas fa-search text-muted"></i>
                            </span>
                            <input type="text" name="search" class="form-control border-start-0" placeholder="Rechercher un nom ou une profession..." value="<?php echo htmlspecialchars($search); ?>">
                            <button type="submit" class="btn btn-success">Rechercher</button>
                            <?php if (!empty($search)): ?>
                                <a href="manage_testimonials.php" class="btn btn-outline-secondary">
                                    <i class="fas fa-times"></i>
                                </a>
                            <?php endif; ?>
                        </div>
                    </form>
                </div>
                <div class="col-md-6 text-md-end">
                    <button id="refreshTable" class="btn btn-outline-success" type="button">
                        <i class="fas fa-sync-alt me-1"></i> Actualiser
                    </button>
                    <div class="btn-group ms-2" role="group">
                        <button type="button" class="btn btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-file-export me-1"></i> Exporter
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#"><i class="fas fa-file-csv me-2"></i>CSV</a></li>
                            <li><a class="dropdown-item" href="#"><i class="fas fa-file-excel me-2"></i>Excel</a></li>
                            <li><a class="dropdown-item" href="#"><i class="fas fa-file-pdf me-2"></i>PDF</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Tableau avec les témoignages -->
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="py-3 px-4">
                                <div class="d-flex align-items-center">
                                    <span>ID</span>
                                    <a href="?sort=id&order=<?php echo $sort === 'id' && $order === 'ASC' ? 'DESC' : 'ASC'; ?><?php echo !empty($search) ? '&search=' . urlencode($search) : ''; ?>" class="ms-2 text-muted">
                                        <i class="fas fa-sort"></i>
                                    </a>
                                </div>
                            </th>
                            <th class="py-3 px-4">
                                <div class="d-flex align-items-center">
                                    <span>Nom</span>
                                    <a href="?sort=nom&order=<?php echo $sort === 'nom' && $order === 'ASC' ? 'DESC' : 'ASC'; ?><?php echo !empty($search) ? '&search=' . urlencode($search) : ''; ?>" class="ms-2 text-muted">
                                        <i class="fas fa-sort"></i>
                                    </a>
                                </div>
                            </th>
                            <th class="py-3 px-4">
                                <div class="d-flex align-items-center">
                                    <span>Profession</span>
                                    <a href="?sort=profession&order=<?php echo $sort === 'profession' && $order === 'ASC' ? 'DESC' : 'ASC'; ?><?php echo !empty($search) ? '&search=' . urlencode($search) : ''; ?>" class="ms-2 text-muted">
                                        <i class="fas fa-sort"></i>
                                    </a>
                                </div>
                            </th>
                            <th class="py-3 px-4">
                                <div class="d-flex align-items-center">
                                    <span>Date de création</span>
                                    <a href="?sort=date_creation&order=<?php echo $sort === 'date_creation' && $order === 'ASC' ? 'DESC' : 'ASC'; ?><?php echo !empty($search) ? '&search=' . urlencode($search) : ''; ?>" class="ms-2 text-muted">
                                        <i class="fas fa-sort"></i>
                                    </a>
                                </div>
                            </th>
                            <th class="py-3 px-4 text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($testimonials): ?>
                            <?php foreach ($testimonials as $testimonial): ?>
                                <tr>
                                    <td class="py-3 px-4"><?php echo $testimonial['id']; ?></td>
                                    
                                    <td class="py-3 px-4">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm bg-success bg-opacity-10 rounded-circle text-center me-3" style="width: 40px; height: 40px; line-height: 40px;">
                                                <i class="fas fa-user text-success"></i>
                                            </div>
                                            <div>
                                                <h6 class="mb-0"><?php echo htmlspecialchars($testimonial['nom']); ?></h6>
                                                <small class="text-muted">Client #<?php echo $testimonial['id']; ?></small>
                                            </div>
                                        </div>
                                    </td>
                                    
                                    <td class="py-3 px-4">
                                        <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 fw-semibold">
                                            <i class="fas fa-briefcase me-1"></i>
                                            <?php echo htmlspecialchars($testimonial['profession']); ?>
                                        </span>
                                    </td>
                                    
                                    <td class="py-3 px-4">
                                        <div class="d-flex align-items-center">
                                            <i class="far fa-calendar-alt text-muted me-2"></i>
                                            <?php echo date('d/m/Y', strtotime($testimonial['date_creation'])); ?>
                                        </div>
                                    </td>
                                    
                                    <td class="py-3 px-4 text-end">
                                        <div class="btn-group" role="group">
                                            <a href="#" class="btn btn-sm btn-outline-info me-2" data-bs-toggle="modal" data-bs-target="#viewModal<?php echo $testimonial['id']; ?>" data-bs-toggle="tooltip" data-bs-placement="top" title="Aperçu">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="edit_testimonial.php?id=<?php echo $testimonial['id']; ?>" class="btn btn-sm btn-outline-success me-2" data-bs-toggle="tooltip" data-bs-placement="top" title="Modifier">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteModal<?php echo $testimonial['id']; ?>" data-bs-toggle="tooltip" data-bs-placement="top" title="Supprimer">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </div>
                                        
                                        <!-- Modal d'aperçu -->
                                        <div class="modal fade" id="viewModal<?php echo $testimonial['id']; ?>" tabindex="-1" aria-labelledby="viewModalLabel<?php echo $testimonial['id']; ?>" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="viewModalLabel<?php echo $testimonial['id']; ?>">
                                                            <i class="fas fa-comment-dots text-success me-2"></i>
                                                            Témoignage de <?php echo htmlspecialchars($testimonial['nom']); ?>
                                                        </h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="d-flex align-items-center mb-3">
                                                            <div class="avatar-sm bg-success bg-opacity-10 rounded-circle text-center me-3" style="width: 50px; height: 50px; line-height: 50px;">
                                                                <i class="fas fa-user text-success fa-lg"></i>
                                                            </div>
                                                            <div>
                                                                <h5 class="mb-0"><?php echo htmlspecialchars($testimonial['nom']); ?></h5>
                                                                <span class="badge bg-info bg-opacity-10 text-info px-2 py-1 mt-1">
                                                                    <i class="fas fa-briefcase me-1"></i>
                                                                    <?php echo htmlspecialchars($testimonial['profession']); ?>
                                                                </span>
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="card bg-light border-0 p-3 mb-3">
                                                            <blockquote class="blockquote fs-6 mb-0">
                                                                <i class="fas fa-quote-left text-success opacity-50 me-2"></i>
                                                                <?php echo nl2br(htmlspecialchars($testimonial['temoignage'])); ?>
                                                                <i class="fas fa-quote-right text-success opacity-50 ms-2"></i>
                                                            </blockquote>
                                                        </div>
                                                        
                                                        <div class="text-muted small">
                                                            <i class="far fa-calendar-alt me-2"></i> Ajouté le 
                                                            <?php echo date('d/m/Y à H:i', strtotime($testimonial['date_creation'])); ?>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                                                        <a href="edit_testimonial.php?id=<?php echo $testimonial['id']; ?>" class="btn btn-success">
                                                            <i class="fas fa-edit me-2"></i>Modifier
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- Modal de confirmation de suppression -->
                                        <div class="modal fade" id="deleteModal<?php echo $testimonial['id']; ?>" tabindex="-1" aria-labelledby="deleteModalLabel<?php echo $testimonial['id']; ?>" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                    <div class="modal-header border-0 pb-0">
                                                        <h5 class="modal-title fw-bold" id="deleteModalLabel<?php echo $testimonial['id']; ?>">Confirmation de suppression</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body text-center py-4">
                                                        <div class="mb-4">
                                                            <span class="icon-circle bg-danger bg-opacity-10 p-4 rounded-circle d-inline-block">
                                                                <i class="fas fa-exclamation-triangle text-danger fa-2x"></i>
                                                            </span>
                                                        </div>
                                                        <h5 class="mb-3">Êtes-vous sûr de vouloir supprimer ce témoignage ?</h5>
                                                        <p class="text-muted mb-0">
                                                            <strong><?php echo htmlspecialchars($testimonial['nom']); ?></strong> - 
                                                            <?php echo htmlspecialchars($testimonial['profession']); ?><br>
                                                            Cette action est irréversible et supprimera définitivement le témoignage.
                                                        </p>
                                                    </div>
                                                    <div class="modal-footer justify-content-center border-0 pt-0">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                            <i class="fas fa-times me-2"></i>Annuler
                                                        </button>
                                                        <a href="delete_testimonial.php?id=<?php echo $testimonial['id']; ?>" class="btn btn-danger">
                                                            <i class="fas fa-trash-alt me-2"></i>Supprimer
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center py-5">
                                    <div class="py-4">
                                        <i class="fas fa-comments fa-4x text-muted mb-3"></i>
                                        <h4>Aucun témoignage disponible</h4>
                                        <p class="text-muted"><?php echo !empty($search) ? 'Aucun résultat ne correspond à votre recherche.' : 'Commencez par ajouter un témoignage de vos clients.'; ?></p>
                                        <a href="add_testimonial.php" class="btn btn-success mt-2">
                                            <i class="fas fa-plus me-2"></i>Ajouter un témoignage
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- Pagination -->
        <?php if ($total_pages > 1): // Affiche la pagination seulement s'il y a plus d'une page ?>
        <div class="card-footer bg-white border-0 py-3">
            <nav aria-label="Page navigation">
                <ul class="pagination justify-content-center mb-0">

                    <li class="page-item <?php if ($current_page <= 1) echo 'disabled'; // Désactive si sur la première page ?>">
                    <a class="page-link text-success" href="?page=<?php echo $current_page - 1; ?>&sort=<?php echo $sort; ?>&order=<?php echo $order; ?>&search=<?php echo urlencode($search); ?>">Précédent</a>
                    </li>

                    <?php for ($i = 1; $i <= $total_pages; $i++): // Boucle pour générer un lien pour chaque page ?>
                        <li class="page-item <?php if ($i === $current_page) echo 'active'; // Ajoute la classe 'active' pour la page courante ?>">
                            <a class="page-link bg-success text-light border border-light" href="?page=<?php echo $i; // Lien vers la page 'i' ?>"><?php echo $i; ?></a>
                        </li>
                    <?php endfor; ?>

                    <li class="page-item <?php if ($current_page >= $total_pages) echo 'disabled'; // Désactive si sur la dernière page ?>">
                        <a class="page-link text-success" href="?page=<?php echo $current_page + 1; // Lien vers la page suivante ?>" aria-disabled="<?php if ($current_page >= $total_pages) echo 'true'; ?>">Suivant</a>
                    </li>
                </ul>
            </nav>
        </div>
        <?php endif; ?>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialiser les tooltips
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        const tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });
        
        // Animation du bouton d'actualisation
        const refreshBtn = document.getElementById('refreshTable');
        refreshBtn.addEventListener('click', function() {
            const icon = this.querySelector('i');
            icon.classList.add('fa-spin');
            
            // Simuler une actualisation après 1 seconde
            setTimeout(() => {
                window.location.reload();
            }, 1000);
        });
        
        // Auto-fermeture des alertes après 5 secondes
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            setTimeout(() => {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            }, 5000);
        });
        
        // Animation des lignes du tableau au survol
        const tableRows = document.querySelectorAll('tbody tr');
        tableRows.forEach(row => {
            row.addEventListener('mouseenter', function() {
                this.style.transition = 'background-color 0.3s';
                this.style.backgroundColor = 'rgba(0, 123, 255, 0.05)';
            });
            
            row.addEventListener('mouseleave', function() {
                this.style.backgroundColor = '';
            });
        });
    });
</script>

<?php include 'inc/footer.php'; ?>