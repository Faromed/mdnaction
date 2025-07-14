<?php
require '../inc/db_config.php';
require 'inc/auth.php';
force_login();

// Démarrer la session et effectuer les vérifications AVANT tout output HTML
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    $_SESSION['error_message'] = 'Identifiant d\'article de blog invalide.';
    header('Location: manage_blog.php');
    exit;
}

$id = $_GET['id'];
$pdo = $pdo ?? null; // Assure que $pdo est défini
if (!$pdo) {
    $_SESSION['error_message'] = 'Erreur de connexion à la base de données.';
    header('Location: manage_blog.php');
    exit;
}

// Inclure les fichiers qui génèrent du HTML APRÈS les redirections

include 'inc/functions.php';

$post = get_one($pdo, 'blog', $id);

if (!$post) {
    $_SESSION['error_message'] = 'Article de blog non trouvé.';
    header('Location: manage_blog.php');
    exit;
}

// Récupérer les images du dossier img
$images = [];
$imgDirectory = '../img/'; // Chemin vers le dossier img
if (is_dir($imgDirectory)) {
    $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp']; // Extensions autorisées
    $files = scandir($imgDirectory);
    
    foreach ($files as $file) {
        $extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));
        if ($file !== '.' && $file !== '..' && in_array($extension, $allowedExtensions)) {
            $images[] = [
                'nom_fichier' => $file,
                'chemin' => $imgDirectory . $file
            ];
        }
    }
}

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titre = trim($_POST['titre']);
    $contenu = trim($_POST['contenu']);
    $auteur = trim($_POST['auteur']);
    // Correction ici: utiliser le champ 'miniature' au lieu de 'image'
    $image = trim($_POST['miniature'] ?? '');
    $est_publie = isset($_POST['est_publie']) ? 1 : 0;

    // Validation des données
    $errors = [];
    if (empty($titre)) {
        $errors[] = "Le titre est obligatoire.";
    }
    if (empty($contenu)) {
        $errors[] = "Le contenu est obligatoire.";
    }
    
    if (empty($errors)) {
        try {
            $stmt = $pdo->prepare("UPDATE blog SET 
                titre = ?, 
                contenu = ?, 
                auteur = ?, 
                image = ?,
                -- est_publie = ?,
                date_modification = NOW()
                WHERE id = ?");
            $stmt->execute([$titre, $contenu, $auteur, $image, $id]);
            
            $_SESSION['success_message'] = 'L\'article <strong>' . htmlspecialchars($titre) . '</strong> a été mis à jour avec succès.';
            header('Location: manage_blog.php');
            exit;
        } catch (PDOException $e) {
            $error_message = 'Erreur lors de la mise à jour de l\'article : ' . $e->getMessage();
        }
    }
}

// Récupérer la liste des images disponibles (optionnel)
$images_directory = '../img/blog/';
$allowed_extensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
$available_images = [];

if (is_dir($images_directory)) {
    $files = scandir($images_directory);
    foreach ($files as $file) {
        $extension = pathinfo($file, PATHINFO_EXTENSION);
        if ($file !== '.' && $file !== '..' && in_array(strtolower($extension), $allowed_extensions)) {
            $available_images[] = $file;
        }
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
                    <li class="breadcrumb-item"><a href="manage_blog.php" class="text-decoration-none">Blog</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Modifier</li>
                </ol>
            </nav>
            <h1 class="h2 mb-0 mt-2 fw-bold">
                <i class="fas fa-edit text-primary me-2"></i>Modifier l'Article
            </h1>
        </div>
        <div>
            <a href="manage_blog.php" class="btn btn-outline-secondary">
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
    
    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>
            <strong>Veuillez corriger les erreurs suivantes :</strong>
            <ul class="mb-0 mt-2">
                <?php foreach ($errors as $error): ?>
                    <li><?php echo $error; ?></li>
                <?php endforeach; ?>
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    
    <!-- Aperçu de l'article -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light border-0 py-3">
                    <div class="d-flex align-items-center">
                        <span class="icon-circle bg-primary bg-opacity-10 p-3 rounded-circle me-3">
                            <i class="fas fa-newspaper text-primary"></i>
                        </span>
                        <div>
                            <h5 class="mb-0 fw-bold"><?php echo htmlspecialchars($post['titre']); ?></h5>
                            <small class="text-muted">ID: <?php echo $post['id']; ?> | Créé le: <?php echo date('d/m/Y', strtotime($post['date_publication'])); ?></small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Formulaire de modification -->
    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
            <form method="post" id="updateBlogForm">
                <input type="hidden" name="id" value="<?php echo $post['id']; ?>">
                
                <div class="row g-4">
                    <!-- Colonne gauche -->
                    <div class="col-lg-8">
                        <div class="card border bg-light mb-4">
                            <div class="card-header bg-light border-bottom-0">
                                <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Informations principales</h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="titre" class="form-label fw-semibold">Titre <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="titre" name="titre" value="<?php echo htmlspecialchars($post['titre']); ?>" required>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="contenu" class="form-label fw-semibold">Contenu <span class="text-danger">*</span></label>
                                    <textarea class="form-control" id="contenu" name="contenu" rows="12"><?php echo htmlspecialchars($post['contenu']); ?></textarea>
                                    <div class="form-text">Vous pouvez utiliser le HTML pour mettre en forme votre contenu.</div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="card border bg-light">
                            <div class="card-header bg-light border-bottom-0">
                                <h5 class="mb-0"><i class="fas fa-tags me-2"></i>Métadonnées</h5>
                            </div>
                            <div class="card-body">
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="auteur" class="form-label fw-semibold">Auteur</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light"><i class="fas fa-user"></i></span>
                                            <input type="text" class="form-control" id="auteur" name="auteur" value="<?php echo htmlspecialchars($post['auteur']); ?>">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Colonne droite -->
                    <div class="col-lg-4">
                        <div class="card border bg-light mb-4">
                            <div class="card-header bg-light border-bottom-0">
                                <h5 class="mb-0"><i class="fas fa-image me-2"></i>Image principale</h5>
                            </div>
                            <div class="card-body">
                                <?php if (!empty($post['image']) && file_exists($images_directory . $post['image'])): ?>
                                    <div class="text-center mb-3">
                                        <img src="<?php echo '../img/blog/' . htmlspecialchars($post['image']); ?>" alt="Image de l'article" class="img-fluid rounded shadow-sm" style="max-height: 200px;">
                                    </div>
                                <?php endif; ?>
                                
                                <div class="mb-3">
                                    <label for="miniature" class="form-label fw-semibold">Nom du fichier image</label>
                                    <div class="input-group">
                                        <select class="form-select form-select-success border-success image-select" id="miniature" name="miniature">
                                            <option value="">Sélectionnez une image</option>
                                            <?php foreach ($images as $image): ?>
                                                <option value="<?php echo htmlspecialchars($image['nom_fichier']); ?>" 
                                                        data-img-src="<?php echo htmlspecialchars($image['chemin']); ?>"
                                                        <?php if ($post['image'] == $image['nom_fichier']) echo 'selected'; ?>>
                                                    <?php echo htmlspecialchars($image['nom_fichier']); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="form-text">
                                        <a href="upload_image.php" target="_blank" class="text-decoration-none">
                                            <i class="fas fa-upload me-1"></i>Téléverser une nouvelle image
                                        </a>
                                    </div>
                                </div>
                                <div class="mt-4" id="image-preview">
                                    <div class="text-center p-5 border border-dashed rounded">
                                        <i class="fas fa-image fa-3x text-muted mb-3"></i>
                                        <p class="text-muted mb-0">Aucune image sélectionnée</p>
                                    </div>
                                </div>
                                
                                <?php if (!empty($available_images)): ?>
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Images disponibles</label>
                                    <div class="border rounded p-2 bg-white" style="max-height: 200px; overflow-y: auto;">
                                        <div class="d-flex flex-wrap gap-2">
                                            <?php foreach($available_images as $image): ?>
                                            <div class="image-item" onclick="selectImage('<?php echo htmlspecialchars($image); ?>')">
                                                <img src="<?php echo '../img/blog/' . htmlspecialchars($image); ?>" alt="<?php echo htmlspecialchars($image); ?>" class="img-thumbnail" style="width: 60px; cursor: pointer;">
                                            </div>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                    <div class="form-text">Cliquez sur une image pour la sélectionner.</div>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <div class="card border bg-light mb-4">
                            <div class="card-header bg-light border-bottom-0">
                                <h5 class="mb-0"><i class="fas fa-cog me-2"></i>Paramètres de publication</h5>
                            </div>
                            <div class="card-body">
                                <div class="form-check form-switch mb-3">
                                    <input class="form-check-input" type="checkbox" id="est_publie" name="est_publie" <?php if (isset($post['est_publie']) && $post['est_publie']) echo 'checked'; ?>>
                                    <label class="form-check-label fw-semibold" for="est_publie">Publier cet article</label>
                                    <div class="form-text">Décochez pour enregistrer comme brouillon.</div>
                                </div>
                                
                                <div class="mb-3">
                                    <div class="d-flex justify-content-between mb-2">
                                        <span class="fw-semibold">Date de création:</span>
                                        <span><?php echo date('d/m/Y H:i', strtotime($post['date_publication'])); ?></span>
                                    </div>
                                    <?php if (!empty($post['date_modification'])): ?>
                                    <div class="d-flex justify-content-between">
                                        <span class="fw-semibold">Dernière modification:</span>
                                        <span><?php echo date('d/m/Y H:i', strtotime($post['date_modification'])); ?></span>
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Boutons d'action -->
                <div class="d-flex justify-content-between border-top mt-4 pt-4">
                    <a href="manage_blog.php" class="btn btn-outline-secondary px-4">
                        <i class="fas fa-times me-2"></i>Annuler
                    </a>
                    <div>
                        <a href="preview_blog.php?id=<?php echo $post['id']; ?>" target="_blank" class="btn btn-outline-info me-2">
                            <i class="fas fa-eye me-2"></i>Aperçu
                        </a>
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="fas fa-save me-2"></i>Enregistrer les modifications
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>


<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Animation du bouton de sauvegarde
        const form = document.getElementById('updateBlogForm');
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
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            setTimeout(() => {
                if (alert) {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                }
            }, 5000);
        });
    });
    
    // Fonction pour sélectionner une image
    function selectImage(filename) {
        document.getElementById('miniature').value = filename;
        // Mettre en évidence l'image sélectionnée
        const imageItems = document.querySelectorAll('.image-item');
        imageItems.forEach(item => {
            item.classList.remove('border', 'border-success');
        });
        event.currentTarget.classList.add('border', 'border-success');
        
        // Déclencher l'événement change pour mettre à jour l'aperçu
        $('#miniature').trigger('change');
    }

    // Initialisation des composants
    $(document).ready(function() {
        // Initialisation de Select2 pour les images
        $('.image-select').select2({
            theme: 'bootstrap-5',
            templateResult: formatOption,
            templateSelection: formatOptionSelection,
            dropdownCssClass: 'shadow'
        });
        // Afficher l'aperçu de l'image sélectionnée
        updateImagePreview();
        
        // Mettre à jour l'aperçu lorsqu'une nouvelle image est sélectionnée
        $('#miniature').on('change', function() {
            updateImagePreview();
        });

    });

        // Fonction pour formater les options dans la liste déroulante
    function formatOption(option) {
        if (!option.id) {
            return option.text;
        }
        
        var imgSrc = $(option.element).data('img-src');
        var $option = $(
            '<div class="d-flex align-items-center">' +
                '<img src="' + imgSrc + '" class="img-fluid rounded" style="width: 60px; height: 40px; object-fit: cover;" />' +
                '<span class="ms-3">' + option.text + '</span>' +
            '</div>'
        );
        
        return $option;
    }

    // Fonction pour formater l'option sélectionnée
    function formatOptionSelection(option) {
        if (!option.id) {
            return option.text;
        }
        
        var imgSrc = $(option.element).data('img-src');
        var $option = $(
            '<div class="d-flex align-items-center">' +
                '<img src="' + imgSrc + '" class="rounded" style="width: 25px; height: 25px; object-fit: cover;" />' +
                '<span class="ms-2">' + option.text + '</span>' +
            '</div>'
        );
        
        return $option;
    }
    
    // Fonction pour mettre à jour l'aperçu de l'image
    function updateImagePreview() {
        const select = document.getElementById('miniature');
        const previewDiv = document.getElementById('image-preview');
        
        if (select.value) {
            const selectedOption = select.options[select.selectedIndex];
            const imgSrc = $(selectedOption).data('img-src');
            
            previewDiv.innerHTML = `
                <div class="text-center p-3">
                    <div class="card">
                    <div class="card-body p-2">
                        <p class="text-muted mb-1">Aperçu de l'image sélectionnée :</p>
                        <img src="${imgSrc}" class="img-thumbnail" style="max-height: 150px;" alt="${select.value}">
                    </div>
                </div>
                    <div class="text-muted small">Cette image sera affichée comme couverture de votre formation</div>
                </div>
            `;
        } else {
            previewDiv.innerHTML = `
                <div class="text-center p-5 border border-dashed rounded">
                    <i class="fas fa-image fa-3x text-muted mb-3"></i>
                    <p class="text-muted mb-0">Aucune image sélectionnée</p>
                </div>
            `;
        }
    }

</script>

<style>
    /* Styles améliorés */
    .border-dashed {
        border-style: dashed !important;
    }
    
    .select2-container--bootstrap-5 .select2-selection {
        padding: 0.375rem 0.75rem;
        height: auto;
        min-height: 38px;
    }
    
    .card-header.bg-gradient {
        background-image: linear-gradient(to right,rgb(19, 112, 6),rgb(19, 112, 6));
    }
    
    .form-control:focus, .form-select:focus {
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.15);
    }
    
    /* Animation pour les conseils */
    .badge.rounded-circle {
        transition: all 0.3s ease;
    }
    .badge.rounded-circle:hover {
        transform: scale(1.2);
    }
     /* Changer la couleur de focus des éléments select standards */
     .form-select:focus {
        border-color: #198754;
        box-shadow: 0 0 0 0.25rem rgba(25, 135, 84, 0.25);
    }
</style>

<?php include 'inc/footer.php'; ?>