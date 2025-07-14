<?php
require '../inc/db_config.php';
require 'inc/auth.php';
force_login();
include 'inc/functions.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    $_SESSION['error_message'] = 'Identifiant de service invalide.';
    header('Location: manage_services.php');
    exit;
}

$id = $_GET['id'];
$service = get_one($pdo, 'services', $id);

if (!$service) {
    $_SESSION['error_message'] = 'Service non trouvé.';
    header('Location: manage_services.php');
    exit;
}

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titre = trim($_POST['titre']);
    $description = trim($_POST['description']);
    $prix = trim($_POST['prix']);
    $image_ou_icone = trim($_POST['miniature']);

    // Gérer l'upload de la nouvelle image
    if (isset($_FILES['new_image']) && $_FILES['new_image']['error'] == 0) {
        $target_dir = "../img/";
        $target_file = $target_dir . basename($_FILES["new_image"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

        // Allow certain file formats
        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
        && $imageFileType != "gif" ) {
            $_SESSION['error_message'] = "Désolé, seuls les fichiers JPG, JPEG, PNG et GIF sont autorisés.";
            header('Location: edit_service.php?id=' . $id);
            exit;
        }

        if (move_uploaded_file($_FILES["new_image"]["tmp_name"], $target_file)) {
            $image_ou_icone = basename($_FILES["new_image"]["name"]);
        } else {
            $_SESSION['error_message'] = "Désolé, une erreur s'est produite lors du téléchargement de votre fichier.";
            header('Location: edit_service.php?id=' . $id);
            exit;
        }
    }

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
            $stmt = $pdo->prepare("UPDATE services SET 
                titre = ?, 
                description = ?, 
                image_ou_icone = ?,
                prix = ?,
                date_modification = NOW()
                WHERE id = ?");
            $stmt->execute([$titre, $description, $image_ou_icone, $prix, $id]);
            
            $_SESSION['success_message'] = 'Le service <strong>' . htmlspecialchars($titre) . '</strong> a été mis à jour avec succès.';
            header('Location: manage_services.php');
            exit;
        } catch (PDOException $e) {
            $error_message = 'Erreur lors de la mise à jour du service : ' . $e->getMessage();
        }
    }
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

include 'inc/header.php';
?>

<div class="container-fluid px-4 py-4">
    <!-- En-tête de la page avec breadcrumb -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="dashboard.php" class="text-decoration-none"><i class="fas fa-tachometer-alt"></i> Tableau de bord</a></li>
                    <li class="breadcrumb-item"><a href="manage_services.php" class="text-decoration-none">Services</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Modifier</li>
                </ol>
            </nav>
            <h1 class="h2 mb-0 mt-2 fw-bold">
                <i class="fas fa-edit text-primary me-2"></i>Modifier le Service
            </h1>
        </div>
        <div>
            <a href="manage_services.php" class="btn btn-outline-secondary">
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
    
    <!-- Aperçu du service -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light border-0 py-3">
                    <div class="d-flex align-items-center">
                        <span class="icon-circle bg-primary bg-opacity-10 p-3 rounded-circle me-3">
                            <i class="fas fa-cogs text-primary"></i>
                        </span>
                        <div>
                            <h5 class="mb-0 fw-bold"><?php echo htmlspecialchars($service['titre']); ?></h5>
                            <small class="text-muted">ID: <?php echo $service['id']; ?> 
                            <?php if (isset($service['date_creation'])): ?>
                                | Créé le: <?php echo date('d/m/Y', strtotime($service['date_creation'])); ?>
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
            <form method="post" id="updateServiceForm" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?php echo $service['id']; ?>">
                
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
                                    <input type="text" class="form-control" id="titre" name="titre" value="<?php echo htmlspecialchars($service['titre']); ?>" required>
                                    <div class="form-text">Le titre principal du service qui sera affiché aux utilisateurs.</div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="description" class="form-label fw-semibold">Description <span class="text-danger">*</span></label>
                                    <textarea class="form-control" id="description" name="description" rows="6"><?php echo htmlspecialchars($service['description']); ?></textarea>
                                    <div class="form-text">Décrivez en détail ce que comprend ce service.</div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="prix" class="form-label fw-semibold">Prix</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light"><i class="fas fa-euro-sign"></i></span>
                                        <input type="text" class="form-control" id="prix" name="prix" value="<?php echo htmlspecialchars($service['prix']); ?>">
                                    </div>
                                    <div class="form-text">Prix en euros (exemple: 299.99) ou format personnalisé (ex: "À partir de 299€")</div>
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
                                <?php if (!empty($service['image_ou_icone']) && file_exists('../img/' . $service['image_ou_icone'])): ?>
                                    <div class="text-center mb-3">
                                        <img src="<?php echo '../img/' . htmlspecialchars($service['image_ou_icone']); ?>" alt="Image du service" class="img-fluid rounded shadow-sm" style="max-height: 200px;">
                                    </div>
                                <?php endif; ?>
                                
                                <div class="mb-3">
                                    <label for="miniature" class="form-label fw-semibold">Image existante</label>
                                    <div class="input-group">
                                        <select class="form-select form-select-success border-success image-select" id="miniature" name="miniature">
                                            <option value="">Sélectionnez une image</option>
                                            <?php foreach ($images as $image): ?>
                                                <option value="<?php echo htmlspecialchars($image['nom_fichier']); ?>" 
                                                        data-img-src="<?php echo htmlspecialchars($image['chemin']); ?>"
                                                        <?php if ($service['image_ou_icone'] == $image['nom_fichier']) echo 'selected'; ?>>
                                                    <?php echo htmlspecialchars($image['nom_fichier']); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="new_image" class="form-label fw-semibold">Ou téléverser une nouvelle image</label>
                                    <input type="file" class="form-control" id="new_image" name="new_image">
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
                        
                    </div>
                </div>
                
                <!-- Boutons d'action -->
                <div class="d-flex justify-content-between border-top mt-4 pt-4">
                    <a href="manage_services.php" class="btn btn-outline-secondary px-4">
                        <i class="fas fa-times me-2"></i>Annuler
                    </a>
                    <div>
                        <a href="preview_service.php?id=<?php echo $service['id']; ?>" target="_blank" class="btn btn-outline-info me-2">
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
        const form = document.getElementById('updateServiceForm');
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
        document.getElementById('image_ou_icone').value = filename;
        // Mettre en évidence l'image sélectionnée
        const imageItems = document.querySelectorAll('.image-item');
        imageItems.forEach(item => {
            item.classList.remove('border', 'border-primary');
        });
        event.currentTarget.classList.add('border', 'border-primary');

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
        background-image: linear-gradient(to right, #0d6efd, #0a58ca);
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