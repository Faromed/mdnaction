<?php 
require '../inc/db_config.php'; 
require 'inc/auth.php'; 
force_login(); 
include 'inc/header.php';

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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titre = $_POST['titre'];
    $description = $_POST['description'];
    $duree = $_POST['duree'];
    $outils = $_POST['outils'];
    $competences_requises = $_POST['competences_requises'];
    $prix = $_POST['prix'];
    $lien_paiement = $_POST['lien_paiement'];
    $miniature = $_POST['miniature'];
     
    try {
        $stmt = $pdo->prepare("INSERT INTO formations (titre, description, duree, outils, competences_requises, prix, lien_paiement, miniature) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$titre, $description, $duree, $outils, $competences_requises, $prix, $lien_paiement, $miniature]);
        $_SESSION['success_message'] = 'Formation ajoutée avec succès.';
        header('Location: manage_formations.php');
        exit;
    } catch (PDOException $e) {
        $_SESSION['error_message'] = 'Erreur lors de l\'ajout de la formation : ' . $e->getMessage();
    }
} 
?>

<!-- CSS amélioré avec Bootstrap 5 -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
<!-- Ajout de l'éditeur Summernote pour un champ description enrichi -->
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">

<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-10">
            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb bg-light p-3 rounded shadow-sm">
                    <li class="breadcrumb-item"><a href="dashboard.php" class="text-decoration-none"><i class="fas fa-home"></i> Tableau de bord</a></li>
                    <li class="breadcrumb-item"><a href="manage_formations.php" class="text-decoration-none">Formations</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Ajouter une formation</li>
                </ol>
            </nav>

            <!-- Carte principale -->
            <div class="card shadow border-0 rounded-3 mb-4">
                <div class="card-header bg-gradient bg-success text-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">
                            <i class="fas fa-graduation-cap me-2"></i>Nouvelle Formation
                        </h4>
                        <a href="manage_formations.php" class="btn btn-light btn-sm rounded-pill px-3">
                            <i class="fas fa-arrow-left me-1"></i>Retour à la liste
                        </a>
                    </div>
                </div>
                
                <div class="card-body p-4">
                    <?php if (isset($_SESSION['error_message'])): ?>
                    <div class="alert alert-danger alert-dismissible fade show border-start border-danger border-4" role="alert">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <?php echo $_SESSION['error_message']; unset($_SESSION['error_message']); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    <?php endif; ?>

                    <!-- Formulaire avec progression -->
                    <form method="post" class="needs-validation" id="formationForm" novalidate>
                        <!-- Barre de progression -->
                        <div class="progress mb-4" style="height: 8px;">
                            <div class="progress-bar bg-success" role="progressbar" style="width: 0%;" 
                                aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" id="formProgress"></div>
                        </div>

                        <!-- Indicateur d'étape -->
                        <div class="mb-4 text-end">
                            <span class="badge bg-success rounded-pill py-2 px-3 shadow-sm">
                                Étape <span id="currentStep">1</span>/3 - Complétion: <span id="completionPercent">0</span>%
                            </span>
                        </div>

                        <!-- Détails principaux -->
                        <div class="row g-3">
                            <div class="col-md-8 mb-3">
                                <label for="titre" class="form-label fw-bold">
                                    <i class="fas fa-heading me-1 text-success"></i>Titre de la formation
                                </label>
                                <input type="text" class="form-control form-control-lg border-success-subtle" 
                                    id="titre" name="titre" placeholder="Ex: Maîtrisez Adobe Photoshop en 15 jours" required>
                                <div class="form-text">Choisissez un titre accrocheur qui reflète le contenu de votre formation.</div>
                                <div class="invalid-feedback">Un titre est requis pour votre formation.</div>
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <label for="prix" class="form-label fw-bold">
                                    <i class="fas fa-tag me-1 text-success"></i>Prix
                                </label>
                                <div class="input-group input-group-lg">
                                    <input type="text" class="form-control border-success-subtle" id="prix" name="prix" placeholder="0.00">
                                    <span class="input-group-text bg-success text-white">€</span>
                                </div>
                                <div class="form-text">Laissez vide pour "Gratuit" ou "Sur demande"</div>
                            </div>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6 mb-3">
                                <label for="duree" class="form-label fw-bold">
                                    <i class="fas fa-clock me-1 text-success"></i>Durée
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                                    <input type="text" class="form-control" id="duree" name="duree" 
                                        placeholder="Ex: 3 jours, 15 heures">
                                </div>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="lien_paiement" class="form-label fw-bold">
                                    <i class="fas fa-link me-1 text-success"></i>Lien de Paiement
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-globe"></i></span>
                                    <input type="url" class="form-control" id="lien_paiement" name="lien_paiement" 
                                        placeholder="https://">
                                </div>
                            </div>
                        </div>
                        
                        <hr class="my-4 bg-secondary">
                        
                        <!-- Description et outils -->
                        <div class="row g-3">
                            <div class="col-12 mb-4">
                                <label for="description" class="form-label fw-bold">
                                    <i class="fas fa-align-left me-1 text-success"></i>Description détaillée
                                </label>
                                <textarea class="form-control" id="description" name="description" rows="6"></textarea>
                                <div class="form-text">Décrivez en détail le contenu de la formation, les objectifs et les bénéfices pour les apprenants.</div>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="outils" class="form-label fw-bold">
                                    <i class="fas fa-tools me-1 text-success"></i>Outils et technologies utilisés
                                </label>
                                <select class="form-control select2-tags" id="outils" name="outils" multiple="multiple" 
                                    data-placeholder="Sélectionnez ou ajoutez des outils...">
                                    <option value="Photoshop">Photoshop</option>
                                    <option value="Illustrator">Illustrator</option>
                                    <option value="HTML/CSS">HTML/CSS</option>
                                    <option value="JavaScript">JavaScript</option>
                                    <option value="PHP">PHP</option>
                                    <option value="WordPress">WordPress</option>
                                    <option value="Adobe XD">Adobe XD</option>
                                    <option value="Figma">Figma</option>
                                </select>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="competences_requises" class="form-label fw-bold">
                                    <i class="fas fa-graduation-cap me-1 text-success"></i>Prérequis
                                </label>
                                <textarea class="form-control" id="competences_requises" name="competences_requises" 
                                    rows="3" placeholder="Ex: Connaissance basique en informatique, Notions de design"></textarea>
                            </div>
                        </div>
                        
                        <hr class="my-4 bg-secondary">
                        
                        <!-- Sélection de l'image -->
                        <div class="row g-3">
                            <div class="col-12 mb-4">
                                <label for="miniature" class="form-label fw-bold">
                                    <i class="fas fa-image me-1 text-success"></i>Image de couverture
                                </label>
                                <div class="card bg-light border-0">
                                    <div class="card-body">
                                        <div class="row align-items-center">
                                            <div class="col-md-8">
                                                <select class="form-select form-select-success border-success image-select" id="miniature" name="miniature">
                                                    <option value="">Sélectionnez une image</option>
                                                    <?php foreach ($images as $image): ?>
                                                        <option value="<?php echo htmlspecialchars($image['nom_fichier']); ?>" 
                                                                data-img-src="<?php echo htmlspecialchars($image['chemin']); ?>">
                                                            <?php echo htmlspecialchars($image['nom_fichier']); ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                            <div class="col-md-4 text-md-end mt-3 mt-md-0">
                                                <a href="upload_image.php" class="btn btn-outline-success w-100" target="_blank">
                                                    <i class="fas fa-upload me-2"></i>Gérer les images
                                                </a>
                                            </div>
                                        </div>
                                        
                                        <div class="mt-4" id="image-preview">
                                            <div class="text-center p-5 border border-dashed rounded">
                                                <i class="fas fa-image fa-3x text-muted mb-3"></i>
                                                <p class="text-muted mb-0">Aucune image sélectionnée</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Boutons d'action -->
                        <div class="d-flex justify-content-between mt-4 pt-3 border-top">
                            <a href="manage_formations.php" class="btn btn-outline-secondary btn-lg px-4">
                                <i class="fas fa-times me-2"></i>Annuler
                            </a>
                            <button type="submit" class="btn btn-success btn-lg px-5">
                                <i class="fas fa-save me-2"></i>Enregistrer la formation
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Card d'aide et conseils -->
            <div class="card border-0 bg-light shadow-sm">
                <div class="card-body p-4">
                    <h5 class="card-title"><i class="fas fa-lightbulb text-warning me-2"></i>Conseils pour une formation réussie</h5>
                    <div class="row mt-3">
                        <div class="col-md-4 mb-3">
                            <div class="d-flex">
                                <div class="flex-shrink-0">
                                    <span class="badge rounded-circle bg-success p-2"><i class="fas fa-check"></i></span>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="mb-1">Titre accrocheur</h6>
                                    <p class="text-muted small mb-0">Soyez précis et mentionnez les bénéfices</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="d-flex">
                                <div class="flex-shrink-0">
                                    <span class="badge rounded-circle bg-success p-2"><i class="fas fa-check"></i></span>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="mb-1">Description détaillée</h6>
                                    <p class="text-muted small mb-0">Incluez le programme et les objectifs</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="d-flex">
                                <div class="flex-shrink-0">
                                    <span class="badge rounded-circle bg-success p-2"><i class="fas fa-check"></i></span>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="mb-1">Image attrayante</h6>
                                    <p class="text-muted small mb-0">Choisissez une image professionnelle</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Scripts -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<!-- Éditeur WYSIWYG Summernote -->
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>

<script>
    // Validation du formulaire avec Bootstrap
    (function() {
        'use strict';
        var forms = document.querySelectorAll('.needs-validation');
        Array.prototype.slice.call(forms).forEach(function(form) {
            form.addEventListener('submit', function(event) {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
        });
    })();
    
    // Initialisation des composants
    $(document).ready(function() {
        // Initialisation de Select2 pour les images
        $('.image-select').select2({
            theme: 'bootstrap-5',
            templateResult: formatOption,
            templateSelection: formatOptionSelection,
            dropdownCssClass: 'shadow'
        });
        
        // Initialisation de Select2 pour les tags
        $('.select2-tags').select2({
            theme: 'bootstrap-5',
            tags: true,
            tokenSeparators: [','],
            placeholder: $(this).data('placeholder')
        });
        
        // Initialisation de l'éditeur WYSIWYG
        $('#description').summernote({
            placeholder: 'Rédigez une description détaillée de votre formation...',
            height: 250,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'underline', 'clear']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['insert', ['link']],
                ['view', ['fullscreen', 'codeview', 'help']]
            ],
            callbacks: {
                onChange: function() {
                    updateProgress();
                }
            }
        });
        
        // Afficher l'aperçu de l'image sélectionnée
        updateImagePreview();
        
        // Mettre à jour l'aperçu lorsqu'une nouvelle image est sélectionnée
        $('#miniature').on('change', function() {
            updateImagePreview();
            updateProgress();
        });
        
        // Événements pour mettre à jour la progression
        $('input, textarea').on('input', function() {
            updateProgress();
        });
        
        // Initialiser la progression
        updateProgress();
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
    
    // Fonction pour calculer et mettre à jour la progression du formulaire
    function updateProgress() {
        const formFields = [
            { id: 'titre', weight: 20 },
            { id: 'prix', weight: 10 },
            { id: 'duree', weight: 10 },
            { id: 'lien_paiement', weight: 5 },
            { id: 'description', weight: 30, type: 'wysiwyg' },
            { id: 'outils', weight: 10, type: 'select2' },
            { id: 'competences_requises', weight: 5 },
            { id: 'miniature', weight: 10 }
        ];
        
        let totalValue = 0;
        let currentStepValue = 1;
        
        formFields.forEach(field => {
            let value = 0;
            
            if (field.type === 'wysiwyg') {
                const content = $('#' + field.id).summernote('code');
                if (content && content !== '<p><br></p>' && content.length > 10) {
                    value = field.weight * (Math.min(content.length, 200) / 200);
                }
            } else if (field.type === 'select2') {
                const selected = $('#' + field.id).select2('data');
                if (selected && selected.length) {
                    value = field.weight * Math.min(selected.length, 5) / 5;
                }
            } else {
                const elem = document.getElementById(field.id);
                if (elem && elem.value.trim() !== '') {
                    value = elem.value.length > 5 ? field.weight : field.weight / 2;
                }
            }
            
            totalValue += value;
        });
        
        // Déterminer l'étape actuelle
        if (totalValue > 60) {
            currentStepValue = 3;
        } else if (totalValue > 30) {
            currentStepValue = 2;
        }
        
        // Mettre à jour les affichages
        const progressPercent = Math.round(totalValue);
        $('#formProgress').css('width', progressPercent + '%').attr('aria-valuenow', progressPercent);
        $('#completionPercent').text(progressPercent);
        $('#currentStep').text(currentStepValue);
        
        // Changer la couleur de la barre de progression selon le pourcentage
        const progressBar = $('#formProgress');
        if (progressPercent < 33) {
            progressBar.removeClass('bg-success bg-info').addClass('bg-danger');
        } else if (progressPercent < 66) {
            progressBar.removeClass('bg-success bg-danger').addClass('bg-info');
        } else {
            progressBar.removeClass('bg-info bg-danger').addClass('bg-success');
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