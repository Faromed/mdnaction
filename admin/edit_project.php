<?php
require '../inc/db_config.php';
require 'inc/auth.php';
force_login();
include 'inc/header.php';
include 'inc/functions.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    $_SESSION['error_message'] = 'Identifiant de projet invalide.';
    header('Location: manage_projects.php');
    exit;
}

$id = $_GET['id'];
$projet = get_one($pdo, 'projets', $id);

if (!$projet) {
    $_SESSION['error_message'] = 'Projet non trouvé.';
    header('Location: manage_projects.php');
    exit;
}

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titre = trim($_POST['titre']);
    $image = trim($_POST['image']);
    $description = trim($_POST['description']);
    $technologies_utilisees = trim($_POST['technologies_utilisees']);
    $lien_live = trim($_POST['lien_live']);
    $lien_github = isset($_POST['lien_github']) ? trim($_POST['lien_github']) : '';
    $categorie = isset($_POST['categorie']) ? trim($_POST['categorie']) : '';

    // Validation des données
    $errors = [];
    if (empty($titre)) {
        $errors[] = "Le titre est obligatoire.";
    }
    if (empty($description)) {
        $errors[] = "La description est obligatoire.";
    }
    
    if (empty($errors)) {
        try {
            $stmt = $pdo->prepare("UPDATE projets SET 
                titre = ?, 
                image = ?, 
                description = ?, 
                technologies_utilisees = ?, 
                lien_live = ?,
                lien_github = ?,
                categorie = ?,
                date_modification = NOW()
                WHERE id = ?");
            $stmt->execute([$titre, $image, $description, $technologies_utilisees, $lien_live, $lien_github, $categorie, $id]);
            
            $_SESSION['success_message'] = 'Le projet <strong>' . htmlspecialchars($titre) . '</strong> a été mis à jour avec succès.';
            header('Location: manage_projects.php');
            exit;
        } catch (PDOException $e) {
            $error_message = 'Erreur lors de la mise à jour du projet : ' . $e->getMessage();
        }
    }
}

// Récupérer la liste des images disponibles
$images_directory = '../img/projects/';
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

// Liste des catégories de projets (à adapter selon vos besoins)
$categories = [
    'web' => 'Développement Web',
    'mobile' => 'Application Mobile',
    'desktop' => 'Application Desktop',
    'api' => 'API / Backend',
    'design' => 'Design / UI/UX',
    'autre' => 'Autre'
];
?>

<div class="container-fluid px-4 py-4">
    <!-- En-tête de la page avec breadcrumb -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="dashboard.php" class="text-decoration-none"><i class="fas fa-tachometer-alt"></i> Tableau de bord</a></li>
                    <li class="breadcrumb-item"><a href="manage_projects.php" class="text-decoration-none">Projets</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Modifier</li>
                </ol>
            </nav>
            <h1 class="h2 mb-0 mt-2 fw-bold">
                <i class="fas fa-edit text-primary me-2"></i>Modifier le Projet
            </h1>
        </div>
        <div>
            <a href="manage_projects.php" class="btn btn-outline-secondary">
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
    
    <!-- Aperçu du projet -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light border-0 py-3">
                    <div class="d-flex align-items-center">
                        <span class="icon-circle bg-primary bg-opacity-10 p-3 rounded-circle me-3">
                            <i class="fas fa-project-diagram text-primary"></i>
                        </span>
                        <div>
                            <h5 class="mb-0 fw-bold"><?php echo htmlspecialchars($projet['titre']); ?></h5>
                            <small class="text-muted">ID: <?php echo $projet['id']; ?> 
                            <?php if (isset($projet['date_creation'])): ?>
                                | Créé le: <?php echo date('d/m/Y', strtotime($projet['date_creation'])); ?>
                            <?php endif; ?>
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Formulaire de modification -->
    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
            <form method="post" id="updateProjectForm">
                <input type="hidden" name="id" value="<?php echo $projet['id']; ?>">
                
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
                                    <input type="text" class="form-control" id="titre" name="titre" value="<?php echo htmlspecialchars($projet['titre']); ?>" required>
                                    <div class="form-text">Le titre principal du projet qui sera affiché aux utilisateurs.</div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="description" class="form-label fw-semibold">Description <span class="text-danger">*</span></label>
                                    <textarea class="form-control" id="description" name="description" rows="6"><?php echo htmlspecialchars($projet['description']); ?></textarea>
                                    <div class="form-text">Décrivez en détail ce projet, ses objectifs et ses fonctionnalités.</div>
                                </div>
                                
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="technologies_utilisees" class="form-label fw-semibold">Technologies Utilisées</label>
                                        <input type="text" class="form-control" id="technologies_utilisees" name="technologies_utilisees" value="<?php echo htmlspecialchars($projet['technologies_utilisees']); ?>">
                                        <div class="form-text">Liste des technologies séparées par des virgules (ex: HTML, CSS, JavaScript)</div>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="categorie" class="form-label fw-semibold">Catégorie</label>
                                        <select class="form-select" id="categorie" name="categorie">
                                            <option value="">-- Sélectionner --</option>
                                            <?php foreach($categories as $key => $value): ?>
                                                <option value="<?php echo $key; ?>" <?php echo (isset($projet['categorie']) && $projet['categorie'] == $key) ? 'selected' : ''; ?>>
                                                    <?php echo $value; ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                        <div class="form-text">Classez votre projet dans une catégorie</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="card border bg-light">
                            <div class="card-header bg-light border-bottom-0">
                                <h5 class="mb-0"><i class="fas fa-link me-2"></i>Liens externes</h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="lien_live" class="form-label fw-semibold">Lien Live</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light"><i class="fas fa-globe"></i></span>
                                        <input type="url" class="form-control" id="lien_live" name="lien_live" placeholder="https://" value="<?php echo htmlspecialchars($projet['lien_live']); ?>">
                                    </div>
                                    <div class="form-text">URL du projet en ligne (site web, démo, etc.)</div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="lien_github" class="form-label fw-semibold">Lien GitHub/GitLab</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light"><i class="fab fa-github"></i></span>
                                        <input type="url" class="form-control" id="lien_github" name="lien_github" placeholder="https://" value="<?php echo isset($projet['lien_github']) ? htmlspecialchars($projet['lien_github']) : ''; ?>">
                                    </div>
                                    <div class="form-text">Lien vers le dépôt du code source</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Colonne droite -->
                    <div class="col-lg-4">
                        <div class="card border bg-light mb-4">
                            <div class="card-header bg-light border-bottom-0">
                                <h5 class="mb-0"><i class="fas fa-image me-2"></i>Image du projet</h5>
                            </div>
                            <div class="card-body">
                                <?php if (!empty($projet['image']) && file_exists($images_directory . $projet['image'])): ?>
                                    <div class="text-center mb-3">
                                        <img src="<?php echo '../img/projects/' . htmlspecialchars($projet['image']); ?>" alt="Image du projet" class="img-fluid rounded shadow-sm" style="max-height: 200px;">
                                    </div>
                                <?php endif; ?>
                                
                                <div class="mb-3">
                                    <label for="image" class="form-label fw-semibold">Nom du fichier image</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light"><i class="fas fa-file-image"></i></span>
                                        <input type="text" class="form-control" id="image" name="image" value="<?php echo htmlspecialchars($projet['image']); ?>">
                                    </div>
                                    <div class="form-text">
                                        <a href="upload_image.php" target="_blank" class="text-decoration-none">
                                            <i class="fas fa-upload me-1"></i>Téléverser une nouvelle image
                                        </a>
                                    </div>
                                </div>
                                
                                <?php if (!empty($available_images)): ?>
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Images disponibles</label>
                                    <div class="border rounded p-2 bg-white" style="max-height: 200px; overflow-y: auto;">
                                        <div class="d-flex flex-wrap gap-2">
                                            <?php foreach($available_images as $image): ?>
                                            <div class="image-item" onclick="selectImage('<?php echo htmlspecialchars($image); ?>')">
                                                <img src="<?php echo '../img/projects/' . htmlspecialchars($image); ?>" alt="<?php echo htmlspecialchars($image); ?>" class="img-thumbnail" style="width: 60px; cursor: pointer;">
                                            </div>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                    <div class="form-text">Cliquez sur une image pour la sélectionner.</div>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Boutons d'action -->
                <div class="d-flex justify-content-between border-top mt-4 pt-4">
                    <a href="manage_projects.php" class="btn btn-outline-secondary px-4">
                        <i class="fas fa-times me-2"></i>Annuler
                    </a>
                    <div>
                        <a href="preview_project.php?id=<?php echo $projet['id']; ?>" target="_blank" class="btn btn-outline-info me-2">
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
        const form = document.getElementById('updateProjectForm');
        form.addEventListener('submit', function() {
            const saveButton = this.querySelector('button[type="submit"]');
            const icon = saveButton.querySelector('i');
            const originalText = saveButton.innerHTML;
            
            saveButton.disabled = true;
            icon.classList.remove('fa-save');
            icon.classList.add('fa-spinner', 'fa-spin');
            saveButton.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Enregistrement...';
            
            // Le formulaire sera soumis normalement
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
        document.getElementById('image').value = filename;
        // Mettre en évidence l'image sélectionnée
        const imageItems = document.querySelectorAll('.image-item');
        imageItems.forEach(item => {
            item.classList.remove('border', 'border-primary');
        });
        event.currentTarget.classList.add('border', 'border-primary');
    }
</script>

<?php include 'inc/footer.php'; ?>