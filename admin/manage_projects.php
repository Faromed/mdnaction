<?php 
require '../inc/db_config.php'; 
require 'inc/auth.php'; 
force_login(); 
include 'inc/header.php'; 
include 'inc/functions.php';  

$projets = get_all($pdo, 'projets');

// Pour la recherche et le tri
$search = isset($_GET['search']) ? $_GET['search'] : '';
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'id';
$order = isset($_GET['order']) ? $_GET['order'] : 'ASC';

// Si une recherche est effectuée
if (!empty($search)) {
    // Filtrer les projets qui contiennent le terme de recherche dans le titre
    $projets = array_filter($projets, function($projet) use ($search) {
        return stripos($projet['titre'], $search) !== false;
    });
}

// Trier les projets
if (!empty($projets)) {
    $column = array_column($projets, $sort);
    $direction = ($order === 'ASC') ? SORT_ASC : SORT_DESC;
    array_multisort($column, $direction, $projets);
}

// Calcul des statistiques
$total_projets = count($projets);
$projets_recents = 0;
$date_limite = date('Y-m-d', strtotime('-30 days'));

foreach ($projets as $projet) {
    if (strtotime($projet['date_creation']) > strtotime($date_limite)) {
        $projets_recents++;
    }
}
?>

<div class="container-fluid px-4 py-4">
    <!-- En-tête de la page avec breadcrumb -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="dashboard.php" class="text-decoration-none"><i class="fas fa-tachometer-alt"></i> Tableau de bord</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Projets</li>
                </ol>
            </nav>
            <h1 class="h2 mb-0 mt-2 fw-bold">
                <i class="fas fa-project-diagram text-primary me-2"></i>Gestion des Projets
            </h1>
        </div>
        <div>
            <a href="add_project.php" class="btn btn-primary">
                <i class="fas fa-plus-circle me-2"></i>Nouveau projet
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
                    <div class="rounded-circle bg-primary bg-opacity-10 p-3 me-3">
                        <i class="fas fa-project-diagram text-primary fa-2x"></i>
                    </div>
                    <div>
                        <h6 class="text-muted mb-1">Total des projets</h6>
                        <h3 class="mb-0 fw-bold"><?php echo $total_projets; ?></h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="rounded-circle bg-success bg-opacity-10 p-3 me-3">
                        <i class="fas fa-calendar-check text-success fa-2x"></i>
                    </div>
                    <div>
                        <h6 class="text-muted mb-1">Projets récents (30j)</h6>
                        <h3 class="mb-0 fw-bold"><?php echo $projets_recents; ?></h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="rounded-circle bg-info bg-opacity-10 p-3 me-3">
                        <i class="fas fa-percentage text-info fa-2x"></i>
                    </div>
                    <div>
                        <h6 class="text-muted mb-1">% Projets récents</h6>
                        <h3 class="mb-0 fw-bold"><?php echo $total_projets > 0 ? number_format(($projets_recents / $total_projets) * 100, 1) : 0; ?>%</h3>
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
                            <input type="text" name="search" class="form-control border-start-0" placeholder="Rechercher un projet..." value="<?php echo htmlspecialchars($search); ?>">
                            <button type="submit" class="btn btn-primary">Rechercher</button>
                            <?php if (!empty($search)): ?>
                                <a href="manage_projects.php" class="btn btn-outline-secondary">
                                    <i class="fas fa-times"></i>
                                </a>
                            <?php endif; ?>
                        </div>
                    </form>
                </div>
                <div class="col-md-6 text-md-end">
                    <button id="refreshTable" class="btn btn-outline-primary" type="button">
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
    
    <!-- Tableau avec les projets -->
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
                                    <span>Titre</span>
                                    <a href="?sort=titre&order=<?php echo $sort === 'titre' && $order === 'ASC' ? 'DESC' : 'ASC'; ?><?php echo !empty($search) ? '&search=' . urlencode($search) : ''; ?>" class="ms-2 text-muted">
                                        <i class="fas fa-sort"></i>
                                    </a>
                                </div>
                            </th>
                            <th class="py-3 px-4">
                                <div class="d-flex align-items-center">
                                    <span>Technologies utilisées</span>
                                    <a href="?sort=technologies_utilisees&order=<?php echo $sort === 'technologies_utilisees' && $order === 'ASC' ? 'DESC' : 'ASC'; ?><?php echo !empty($search) ? '&search=' . urlencode($search) : ''; ?>" class="ms-2 text-muted">
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
                        <?php if ($projets): ?>
                            <?php foreach ($projets as $projet): ?>
                                <tr>
                                    <td class="py-3 px-4"><?php echo $projet['id']; ?></td>
                                    
                                    <td class="py-3 px-4">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm bg-primary bg-opacity-10 rounded-circle text-center me-3" style="width: 40px; height: 40px; line-height: 40px;">
                                                <i class="fas fa-briefcase text-primary"></i>
                                            </div>
                                            <div>
                                                <h6 class="mb-0"><?php echo htmlspecialchars($projet['titre']); ?></h6>
                                                <small class="text-muted">Projet #<?php echo $projet['id']; ?></small>
                                            </div>
                                        </div>
                                    </td>
                                    
                                    <td class="py-3 px-4">
                                        <h6 class="badge bg-success bg-opacity-10 text-success px-3 py-2 fw-semibold">
                                            <?php echo htmlspecialchars($projet['technologies_utilisees']); ?>
                                        </h6>
                                    </td>
                                    
                                    <td class="py-3 px-4">
                                        <div class="d-flex align-items-center">
                                            <i class="far fa-calendar-alt text-muted me-2"></i>
                                            <?php echo date('d/m/Y', strtotime($projet['date_creation'])); ?>
                                        </div>
                                    </td>
                                    
                                    <td class="py-3 px-4 text-end">
                                        <div class="btn-group" role="group">
                                            <a href="#" class="btn btn-sm btn-outline-info me-2" data-bs-toggle="tooltip" data-bs-placement="top" title="Aperçu">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="edit_project.php?id=<?php echo $projet['id']; ?>" class="btn btn-sm btn-outline-primary me-2" data-bs-toggle="tooltip" data-bs-placement="top" title="Modifier">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteModal<?php echo $projet['id']; ?>" data-bs-toggle="tooltip" data-bs-placement="top" title="Supprimer">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </div>
                                        
                                        <!-- Modal de confirmation de suppression -->
                                        <div class="modal fade" id="deleteModal<?php echo $projet['id']; ?>" tabindex="-1" aria-labelledby="deleteModalLabel<?php echo $projet['id']; ?>" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                    <div class="modal-header border-0 pb-0">
                                                        <h5 class="modal-title fw-bold" id="deleteModalLabel<?php echo $projet['id']; ?>">Confirmation de suppression</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body text-center py-4">
                                                        <div class="mb-4">
                                                            <span class="icon-circle bg-danger bg-opacity-10 p-4 rounded-circle d-inline-block">
                                                                <i class="fas fa-exclamation-triangle text-danger fa-2x"></i>
                                                            </span>
                                                        </div>
                                                        <h5 class="mb-3">Êtes-vous sûr de vouloir supprimer ce projet ?</h5>
                                                        <p class="text-muted mb-0">
                                                            <strong><?php echo htmlspecialchars($projet['titre']); ?></strong><br>
                                                            Cette action est irréversible et supprimera définitivement le projet.
                                                        </p>
                                                    </div>
                                                    <div class="modal-footer justify-content-center border-0 pt-0">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                            <i class="fas fa-times me-2"></i>Annuler
                                                        </button>
                                                        <a href="delete_project.php?id=<?php echo $projet['id']; ?>" class="btn btn-danger">
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
                                        <i class="fas fa-briefcase fa-4x text-muted mb-3"></i>
                                        <h4>Aucun service disponible</h4>
                                        <p class="text-muted"><?php echo !empty($search) ? 'Aucun résultat ne correspond à votre recherche.' : 'Commencez par ajouter un service à votre catalogue.'; ?></p>
                                        <a href="add_service.php" class="btn btn-primary mt-2">
                                            <i class="fas fa-plus me-2"></i>Ajouter un service
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
        <?php if (count($projets) > 10): ?>
        <div class="card-footer bg-white border-0 py-3">
            <nav aria-label="Page navigation">
                <ul class="pagination justify-content-center mb-0">
                    <li class="page-item disabled">
                        <a class="page-link" href="#" tabindex="-1" aria-disabled="true">Précédent</a>
                    </li>
                    <li class="page-item active"><a class="page-link" href="#">1</a></li>
                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                    <li class="page-item">
                        <a class="page-link" href="#">Suivant</a>
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