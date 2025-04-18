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

$blog_posts = get_all($pdo, 'blog');

// Si une recherche est effectuée
if (!empty($search)) {
    // Filtrer les articles qui contiennent le terme de recherche dans le titre ou l'auteur
    $blog_posts = array_filter($blog_posts, function($post) use ($search) {
        return stripos($post['titre'], $search) !== false || stripos($post['auteur'], $search) !== false;
    });
}

// Trier les articles
if (!empty($blog_posts)) {
    $column = array_column($blog_posts, $sort);
    $direction = ($order === 'ASC') ? SORT_ASC : SORT_DESC;
    array_multisort($column, $direction, $blog_posts);
}

// Calcul des statistiques
$total_posts = count($blog_posts);
$auteurs_distincts = 0;
$auteurs = [];
if (!empty($blog_posts)) {
    foreach ($blog_posts as $post) {
        if (!in_array($post['auteur'], $auteurs)) {
            $auteurs[] = $post['auteur'];
        }
    }
    $auteurs_distincts = count($auteurs);
}

// Récupérer le mois en cours pour les stats
$mois_courant = date('m');
$annee_courante = date('Y');
$posts_ce_mois = 0;

foreach ($blog_posts as $post) {
    $post_month = date('m', strtotime($post['date_publication']));
    $post_year = date('Y', strtotime($post['date_publication']));
    
    if ($post_month == $mois_courant && $post_year == $annee_courante) {
        $posts_ce_mois++;
    }
}
?>

<div class="container-fluid px-4 py-4">
    <!-- En-tête de la page avec breadcrumb -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="dashboard.php" class="text-decoration-none text-success"><i class="fas fa-tachometer-alt"></i> Tableau de bord</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Articles de Blog</li>
                </ol>
            </nav>
            <h1 class="h2 mb-0 mt-2 fw-bold">
                <i class="fas fa-blog text-success me-2"></i>Gestion des Articles de Blog
            </h1>
        </div>
        <div>
            <a href="add_blog.php" class="btn btn-success">
                <i class="fas fa-plus-circle me-2"></i>Nouvel article
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
                        <i class="fas fa-newspaper text-success fa-2x"></i>
                    </div>
                    <div>
                        <h6 class="text-muted mb-1">Total des articles</h6>
                        <h3 class="mb-0 fw-bold"><?php echo $total_posts; ?></h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="rounded-circle bg-success bg-opacity-10 p-3 me-3">
                        <i class="fas fa-user-edit text-success fa-2x"></i>
                    </div>
                    <div>
                        <h6 class="text-muted mb-1">Auteurs distincts</h6>
                        <h3 class="mb-0 fw-bold"><?php echo $auteurs_distincts; ?></h3>
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
                        <h6 class="text-muted mb-1">Articles ce mois-ci</h6>
                        <h3 class="mb-0 fw-bold"><?php echo $posts_ce_mois; ?></h3>
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
                            <input type="text" name="search" class="form-control border-start-0" placeholder="Rechercher un article ou un auteur..." value="<?php echo htmlspecialchars($search); ?>">
                            <button type="submit" class="btn btn-success">Rechercher</button>
                            <?php if (!empty($search)): ?>
                                <a href="manage_blog.php" class="btn btn-outline-secondary">
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
    
    <!-- Tableau avec les articles -->
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
                                    <span>Auteur</span>
                                    <a href="?sort=auteur&order=<?php echo $sort === 'auteur' && $order === 'ASC' ? 'DESC' : 'ASC'; ?><?php echo !empty($search) ? '&search=' . urlencode($search) : ''; ?>" class="ms-2 text-muted">
                                        <i class="fas fa-sort"></i>
                                    </a>
                                </div>
                            </th>
                            <th class="py-3 px-4">
                                <div class="d-flex align-items-center">
                                    <span>Date de publication</span>
                                    <a href="?sort=date_publication&order=<?php echo $sort === 'date_publication' && $order === 'ASC' ? 'DESC' : 'ASC'; ?><?php echo !empty($search) ? '&search=' . urlencode($search) : ''; ?>" class="ms-2 text-muted">
                                        <i class="fas fa-sort"></i>
                                    </a>
                                </div>
                            </th>
                            <th class="py-3 px-4 text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($blog_posts): ?>
                            <?php foreach ($blog_posts as $post): ?>
                                <tr>
                                    <td class="py-3 px-4"><?php echo $post['id']; ?></td>
                                    
                                    <td class="py-3 px-4">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm bg-success bg-opacity-10 rounded-circle text-center me-3" style="width: 40px; height: 40px; line-height: 40px;">
                                                <i class="fas fa-file-alt text-success"></i>
                                            </div>
                                            <div>
                                                <h6 class="mb-0"><?php echo htmlspecialchars($post['titre']); ?></h6>
                                                <small class="text-muted">Article #<?php echo $post['id']; ?></small>
                                            </div>
                                        </div>
                                    </td>
                                    
                                    <td class="py-3 px-4">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm bg-success bg-opacity-10 rounded-circle text-center me-2" style="width: 32px; height: 32px; line-height: 32px;">
                                                <i class="fas fa-user text-success"></i>
                                            </div>
                                            <span><?php echo htmlspecialchars($post['auteur']); ?></span>
                                        </div>
                                    </td>
                                    
                                    <td class="py-3 px-4">
                                        <div class="d-flex align-items-center">
                                            <i class="far fa-calendar-alt text-muted me-2"></i>
                                            <?php echo date('d/m/Y', strtotime($post['date_publication'])); ?>
                                        </div>
                                    </td>
                                    
                                    <td class="py-3 px-4 text-end">
                                        <div class="btn-group" role="group">
                                            <a href="#" class="btn btn-sm btn-outline-info me-2" data-bs-toggle="modal" data-bs-target="#viewModal<?php echo $post['id']; ?>" data-bs-toggle="tooltip" data-bs-placement="top" title="Aperçu">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="edit_blog.php?id=<?php echo $post['id']; ?>" class="btn btn-sm btn-outline-success me-2" data-bs-toggle="tooltip" data-bs-placement="top" title="Modifier">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteModal<?php echo $post['id']; ?>" data-bs-toggle="tooltip" data-bs-placement="top" title="Supprimer">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </div>




                                        <!-- Modal d'aperçu -->
                                        <div class="modal fade" id="viewModal<?php echo $post['id']; ?>" tabindex="-1" aria-labelledby="viewModalLabel<?php echo $post['id']; ?>" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="viewModalLabel<?php echo $post['id']; ?>">
                                                            <i class="fas fa-blog text-success me-2"></i>
                                                            <?php echo htmlspecialchars($post['titre']); ?>
                                                        </h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="d-flex align-items-center mb-3">
                                                            <div class=" bg-success bg-opacity-10 text-center me-3" style="width: 100%; height: auto; line-height: 50px;">
                                                                <img src="../img/<?php echo htmlspecialchars($post['image']); ?>" alt="" style="width: 100%; height: auto; line-height: 50px;">
                                                            </div>
                                                        </div>
                                                        <div class="text-center">
                                                            <h5 class="mb-0"><?php echo 'Auteur : ' . ' ' .htmlspecialchars($post['auteur']); ?></h5>
                                                        </div>
                                                        
                                                        <div class="card bg-light text-center border-0 p-3 mb-3">
                                                            <blockquote class="blockquote fs-6 mb-0">
                                                                <i class="fas fa-quote-left text-success opacity-50 me-2"></i>
                                                                <?php echo nl2br($post['contenu']); ?>
                                                                <i class="fas fa-quote-right text-success opacity-50 ms-2"></i>
                                                            </blockquote>
                                                        </div>
                                                        
                                                        <div class="text-muted small">
                                                            <i class="far fa-calendar-alt me-2"></i> Ajouté le 
                                                            <?php echo date('d/m/Y à H:i', strtotime($post['date_publication'])); ?>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                                                        <a href="edit_blog.php?id=<?php echo $post['id']; ?>" class="btn btn-success">
                                                            <i class="fas fa-edit me-2"></i>Modifier
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- Modal de confirmation de suppression -->
                                        <div class="modal fade" id="deleteModal<?php echo $post['id']; ?>" tabindex="-1" aria-labelledby="deleteModalLabel<?php echo $post['id']; ?>" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                    <div class="modal-header border-0 pb-0">
                                                        <h5 class="modal-title fw-bold" id="deleteModalLabel<?php echo $post['id']; ?>">Confirmation de suppression</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body text-center py-4">
                                                        <div class="mb-4">
                                                            <span class="icon-circle bg-danger bg-opacity-10 p-4 rounded-circle d-inline-block">
                                                                <i class="fas fa-exclamation-triangle text-danger fa-2x"></i>
                                                            </span>
                                                        </div>
                                                        <h5 class="mb-3">Êtes-vous sûr de vouloir supprimer cet article ?</h5>
                                                        <p class="text-muted mb-0">
                                                            <strong><?php echo htmlspecialchars($post['titre']); ?></strong><br>
                                                            Cette action est irréversible et supprimera définitivement l'article.
                                                        </p>
                                                    </div>
                                                    <div class="modal-footer justify-content-center border-0 pt-0">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                            <i class="fas fa-times me-2"></i>Annuler
                                                        </button>
                                                        <a href="delete_blog.php?id=<?php echo $post['id']; ?>" class="btn btn-danger">
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
                                        <i class="fas fa-newspaper fa-4x text-muted mb-3"></i>
                                        <h4>Aucun article de blog disponible</h4>
                                        <p class="text-muted"><?php echo !empty($search) ? 'Aucun résultat ne correspond à votre recherche.' : 'Commencez par ajouter un article à votre blog.'; ?></p>
                                        <a href="add_blog.php" class="btn btn-success mt-2">
                                            <i class="fas fa-plus me-2"></i>Ajouter un article
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
        <?php if (count($blog_posts) > 10): ?>
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