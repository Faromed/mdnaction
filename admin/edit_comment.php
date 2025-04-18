<?php
require '../inc/db_config.php';
require 'inc/auth.php';
force_login();

include 'inc/functions.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    $_SESSION['error_message'] = 'Identifiant de commentaire invalide.';
    header('Location: manage_comments.php');
    exit;
}

$id = $_GET['id'];
$comment = get_one($pdo, 'commentaires', $id);

if (!$comment) {
    $_SESSION['error_message'] = 'Commentaire non trouvé.';
    header('Location: manage_comments.php');
    exit;
}

// Récupérer les infos de l'article associé
$article_id = $comment['article_id'] ?? null;
$article = null;
if ($article_id) {
    $article = get_one($pdo, 'blog', $article_id);
}

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $est_approuve = isset($_POST['est_approuve']) ? 1 : 0;
    $reponse_admin = trim($_POST['reponse_admin'] ?? '');

    try {
        $stmt = $pdo->prepare("UPDATE commentaires SET 
            est_approuve = ?, 
            reponse_admin = ?,
            date_modification = NOW()
            WHERE id = ?");
        $stmt->execute([$est_approuve, $reponse_admin, $id]);
        $_SESSION['success_message'] = 'Le statut du commentaire a été mis à jour avec succès.';
        header('Location: manage_comments.php');
        exit;
    } catch (PDOException $e) {
        $error_message = 'Erreur lors de la mise à jour du commentaire : ' . $e->getMessage();
    }
}
include 'inc/header.php';
?>

<div class="container-fluid px-4 py-4">
    <!-- En-tête de la page avec breadcrumb -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="dashboard.php" class="text-decoration-none"><i class="fas fa-tachometer-alt"></i> Tableau de bord</a></li>
                    <li class="breadcrumb-item"><a href="manage_comments.php" class="text-decoration-none">Commentaires</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Modérer</li>
                </ol>
            </nav>
            <h1 class="h2 mb-0 mt-2 fw-bold">
                <i class="fas fa-comment text-primary me-2"></i>Modération du Commentaire
            </h1>
        </div>
        <div>
            <a href="manage_comments.php" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Retour
            </a>
        </div>
    </div>
    
    <!-- Alertes d'erreur -->
    <?php if (isset($error_message)): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>
            <?php echo $error_message; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    
    <div class="row">
        <!-- Informations sur le commentaire -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-light border-0 py-3">
                    <div class="d-flex align-items-center">
                        <span class="icon-circle bg-primary bg-opacity-10 p-3 rounded-circle me-3">
                            <i class="fas fa-comment-alt text-primary"></i>
                        </span>
                        <div>
                            <h5 class="mb-0 fw-bold">Commentaire #<?php echo $comment['id']; ?></h5>
                            <small class="text-muted">Posté le: <?php echo date('d/m/Y à H:i', strtotime($comment['date_creation'])); ?></small>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="mb-4 p-3 border rounded bg-light">
                        <div class="mb-2 d-flex justify-content-between align-items-center">
                            <div>
                                <strong class="text-primary">
                                    <i class="fas fa-user-circle me-2"></i><?php echo htmlspecialchars($comment['nom']); ?>
                                </strong>
                                <span class="text-muted ms-2">
                                    <i class="fas fa-envelope me-1"></i><?php echo htmlspecialchars($comment['email']); ?>
                                </span>
                            </div>
                            <span class="badge <?php echo $comment['est_approuve'] ? 'bg-success' : 'bg-warning'; ?>">
                                <?php echo $comment['est_approuve'] ? 'Approuvé' : 'En attente'; ?>
                            </span>
                        </div>
                        <div class="border-top pt-2 mt-2">
                            <?php echo nl2br(htmlspecialchars($comment['contenu'])); ?>
                        </div>
                    </div>

                    <?php if ($article): ?>
                    <div class="mb-4">
                        <h6 class="fw-bold mb-2">Article associé:</h6>
                        <div class="d-flex align-items-center border rounded p-3 bg-light">
                            <?php if (!empty($article['image']) && file_exists('../img/blog/' . $article['image'])): ?>
                                <img src="<?php echo '../img/blog/' . htmlspecialchars($article['image']); ?>" alt="<?php echo htmlspecialchars($article['titre']); ?>" class="img-thumbnail me-3" style="width: 80px;">
                            <?php else: ?>
                                <div class="bg-secondary bg-opacity-10 d-flex align-items-center justify-content-center me-3" style="width: 80px; height: 60px;">
                                    <i class="fas fa-image text-secondary"></i>
                                </div>
                            <?php endif; ?>
                            <div>
                                <h6 class="mb-1"><?php echo htmlspecialchars($article['titre']); ?></h6>
                                <a href="edit_blog.php?id=<?php echo $article['id']; ?>" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-edit me-1"></i>Modifier l'article
                                </a>
                                <a href="../article.php?id=<?php echo $article['id']; ?>" target="_blank" class="btn btn-sm btn-outline-secondary ms-1">
                                    <i class="fas fa-eye me-1"></i>Voir
                                </a>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>

                    <form method="post" id="updateCommentForm">
                        <input type="hidden" name="id" value="<?php echo $comment['id']; ?>">
                        
                        <div class="card border bg-light mb-4">
                            <div class="card-header bg-light border-bottom-0">
                                <h5 class="mb-0"><i class="fas fa-reply me-2"></i>Réponse de l'administration</h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <textarea class="form-control" id="reponse_admin" name="reponse_admin" rows="4" placeholder="Ajoutez une réponse publique à ce commentaire (optionnel)"><?php echo htmlspecialchars($comment['reponse_admin'] ?? ''); ?></textarea>
                                    <div class="form-text">Cette réponse sera affichée publiquement sous le commentaire de l'utilisateur.</div>
                                </div>
                            </div>
                        </div>

                        <div class="card border bg-light mb-4">
                            <div class="card-header bg-light border-bottom-0">
                                <h5 class="mb-0"><i class="fas fa-shield-alt me-2"></i>Action de modération</h5>
                            </div>
                            <div class="card-body">
                                <div class="form-check form-switch mb-3">
                                    <input class="form-check-input" type="checkbox" id="est_approuve" name="est_approuve" <?php if ($comment['est_approuve']) echo 'checked'; ?>>
                                    <label class="form-check-label fw-semibold" for="est_approuve">Approuver ce commentaire</label>
                                    <div class="form-text">Les commentaires approuvés sont visibles publiquement sur le site.</div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Boutons d'action -->
                        <div class="d-flex justify-content-between border-top mt-4 pt-4">
                            <a href="manage_comments.php" class="btn btn-outline-secondary px-4">
                                <i class="fas fa-times me-2"></i>Annuler
                            </a>
                            <div>
                                <button type="submit" class="btn btn-primary px-4">
                                    <i class="fas fa-save me-2"></i>Enregistrer les modifications
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Panneau d'information latéral -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-light border-0 py-3">
                    <h5 class="mb-0 fw-bold"><i class="fas fa-info-circle me-2"></i>Actions rapides</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <button type="button" class="btn btn-success" onclick="document.getElementById('est_approuve').checked = true; document.getElementById('updateCommentForm').submit();">
                            <i class="fas fa-check-circle me-2"></i>Approuver et publier
                        </button>
                        <button type="button" class="btn btn-warning" onclick="document.getElementById('est_approuve').checked = false; document.getElementById('updateCommentForm').submit();">
                            <i class="fas fa-ban me-2"></i>Rejeter et masquer
                        </button>
                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
                            <i class="fas fa-trash-alt me-2"></i>Supprimer définitivement
                        </button>
                    </div>
                </div>
            </div>
            
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light border-0 py-3">
                    <h5 class="mb-0 fw-bold"><i class="fas fa-lightbulb me-2"></i>Conseils de modération</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item bg-transparent px-0">
                            <i class="fas fa-check-circle text-success me-2"></i>
                            <strong>Approuver</strong> les commentaires constructifs et pertinents
                        </li>
                        <li class="list-group-item bg-transparent px-0">
                            <i class="fas fa-times-circle text-danger me-2"></i>
                            <strong>Rejeter</strong> les spam, insultes ou propos inappropriés
                        </li>
                        <li class="list-group-item bg-transparent px-0">
                            <i class="fas fa-reply text-primary me-2"></i>
                            <strong>Répondre</strong> aux questions et clarifier les malentendus
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de confirmation de suppression -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
            <h5 class="modal-title" id="deleteModalLabel"><i class="fas fa-exclamation-triangle me-2"></i>Confirmation de suppression</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Êtes-vous sûr de vouloir supprimer définitivement ce commentaire ? Cette action est irréversible.</p>
                <div class="alert alert-warning">
                    <i class="fas fa-info-circle me-2"></i>Commentaire de <strong><?php echo htmlspecialchars($comment['nom']); ?></strong> posté le <?php echo date('d/m/Y', strtotime($comment['date_creation'])); ?>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>Annuler
                </button>
                <a href="delete_comment.php?id=<?php echo $comment['id']; ?>&token=<?php echo $_SESSION['csrf_token']; ?>" class="btn btn-danger">
                    <i class="fas fa-trash-alt me-2"></i>Supprimer définitivement
                </a>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Animation du bouton de sauvegarde
        const form = document.getElementById('updateCommentForm');
        form.addEventListener('submit', function() {
            const saveButton = this.querySelector('button[type="submit"]');
            const icon = saveButton.querySelector('i');
            const originalText = saveButton.innerHTML;
            
            saveButton.disabled = true;
            icon.classList.remove('fa-save');
            icon.classList.add('fa-spinner', 'fa-spin');
            saveButton.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Enregistrement...';
        });
        
        // Auto-fermeture des alertes après 5 secondes
        const alerts = document.querySelectorAll('.alert:not(.alert-warning)');
        alerts.forEach(alert => {
            setTimeout(() => {
                if (alert) {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                }
            }, 5000);
        });
    });
</script>

<?php include 'inc/footer.php'; ?>