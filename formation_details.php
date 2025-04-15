<?php include 'inc/header.php'; ?>

<div class="container py-5">
    <?php 
    require 'inc/db_config.php'; 
    if (isset($_GET['id']) && is_numeric($_GET['id'])) {
        $id = $_GET['id'];
        $stmt = $pdo->prepare("SELECT * FROM formations WHERE id = ?");
        $stmt->execute([$id]);
        $formation = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($formation): 
    ?>
        <!-- Fil d'Ariane -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php" class="text-decoration-none text-success"><i class="fas fa-home me-1"></i>Accueil</a></li>
                <li class="breadcrumb-item"><a href="formations.php" class="text-decoration-none text-success">Formations</a></li>
                <li class="breadcrumb-item active" aria-current="page"><?php echo $formation['titre']; ?></li>
            </ol>
        </nav>

        <!-- Titre avec badge -->
        <div class="mb-5 text-center">
            <h1 class="display-5 fw-bold mb-3"><?php echo $formation['titre']; ?></h1>
            <div class="divider mx-auto my-3" style="width: 80px; height: 4px; background-color: #009891;"></div>
        </div>

        <div class="row g-5">
            <!-- Image de la formation -->
            <div class="col-lg-5">
                <div class="position-relative mb-4">
                    <img src="img/<?php echo $formation['miniature'] ? $formation['miniature'] : 'default_formation.jpg'; ?>" 
                         class="img-fluid rounded-3 shadow" alt="<?php echo $formation['titre']; ?>">
                    <div class="position-absolute top-0 end-0 bg-success text-white px-3 py-2 rounded-bottom-start rounded-top-end">
                        <i class="fas fa-certificate me-1"></i> Formation certifiante
                    </div>
                </div>

                <!-- Carte informations clés -->
                <div class="card border-0 shadow-sm rounded-3 mb-4">
                    <div class="card-header bg-success text-white py-3">
                        <h3 class="h5 mb-0"><i class="fas fa-info-circle me-2"></i>Informations clés</h3>
                    </div>
                    <div class="card-body">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between align-items-center border-0 px-0 pb-3">
                                <div><i class="far fa-clock text-success me-2"></i> Durée</div>
                                <span class="badge bg-light text-dark rounded-pill"><?php echo $formation['duree']; ?></span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center border-0 px-0 pb-3">
                                <div><i class="fas fa-tools text-success me-2"></i> Outils</div>
                                <span class="badge bg-light text-dark"><?php echo $formation['outils']; ?></span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center border-0 px-0 pb-3">
                                <div><i class="fas fa-graduation-cap text-success me-2"></i> Niveau requis</div>
                                <span class="badge bg-light text-dark"><?php echo $formation['competences_requises']; ?></span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center border-0 px-0">
                                <div><i class="fas fa-tag text-success me-2"></i> Prix</div>
                                <span class="fw-bold text-success"><?php echo $formation['prix']; ?></span>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Actions -->
                <div class="text-center">
                    <?php if ($formation['lien_paiement']): ?>
                        <a href="<?php echo $formation['lien_paiement']; ?>" class="btn btn-success btn-lg w-100 mb-3" target="_blank">
                            <i class="fas fa-shopping-cart me-2"></i>S'inscrire à la formation
                        </a>
                    <?php else: ?>
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle me-2"></i>Le lien de paiement n'est pas encore disponible.
                        </div>
                    <?php endif; ?>
                    <a href="#" class="btn btn-outline-success w-100">
                        <i class="far fa-calendar-alt me-2"></i>Demander un rendez-vous
                    </a>
                </div>
            </div>

            <!-- Contenu détaillé de la formation -->
            <div class="col-lg-7">
                <!-- Onglets -->
                <ul class="nav nav-tabs mb-4" id="formationTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link text-success active" id="description-tab" data-bs-toggle="tab" data-bs-target="#description" type="button" role="tab" aria-controls="description" aria-selected="true">
                            <i class="fas fa-align-left me-2"></i>Description
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link text-success" id="programme-tab" data-bs-toggle="tab" data-bs-target="#programme" type="button" role="tab" aria-controls="programme" aria-selected="false">
                            <i class="fas fa-list-check me-2"></i>Programme
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link text-success" id="formateur-tab" data-bs-toggle="tab" data-bs-target="#formateur" type="button" role="tab" aria-controls="formateur" aria-selected="false">
                            <i class="fas fa-user-tie me-2"></i>Formateur
                        </button>
                    </li>
                </ul>

                <!-- Contenu des onglets -->
                <div class="tab-content" id="formationTabContent">
                    <!-- Description -->
                    <div class="tab-pane fade show active" id="description" role="tabpanel" aria-labelledby="description-tab">
                        <div class="bg-light p-4 rounded-3 mb-4">
                            <h3 class="h4 mb-3"><i class="fas fa-quote-left me-2 text-success"></i>Description</h3>
                            <p class="text-muted"><?php echo $formation['description']; ?></p>
                        </div>

                        <!-- Objectifs -->
                        <div class="mb-4">
                            <h3 class="h4 mb-3"><i class="fas fa-bullseye text-success me-2"></i>Objectifs de la formation</h3>
                            <div class="row">
                                <div class="col-md-6">
                                    <ul class="list-group list-group-flush">
                                        <li class="list-group-item border-0 ps-0">
                                            <i class="fas fa-check-circle text-success me-2"></i>Maîtriser les fondamentaux
                                        </li>
                                        <li class="list-group-item border-0 ps-0">
                                            <i class="fas fa-check-circle text-success me-2"></i>Développer des compétences avancées
                                        </li>
                                    </ul>
                                </div>
                                <div class="col-md-6">
                                    <ul class="list-group list-group-flush">
                                        <li class="list-group-item border-0 ps-0">
                                            <i class="fas fa-check-circle text-success me-2"></i>Mettre en pratique sur des cas réels
                                        </li>
                                        <li class="list-group-item border-0 ps-0">
                                            <i class="fas fa-check-circle text-success me-2"></i>Obtenir une certification reconnue
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- Public cible -->
                        <div>
                            <h3 class="h4 mb-3"><i class="fas fa-users text-success me-2"></i>À qui s'adresse cette formation ?</h3>
                            <div class="d-flex flex-wrap gap-2 mb-3">
                                <span class="badge bg-light text-dark p-2"><i class="fas fa-user-graduate me-1"></i> Débutants</span>
                                <span class="badge bg-light text-dark p-2"><i class="fas fa-briefcase me-1"></i> Professionnels</span>
                                <span class="badge bg-light text-dark p-2"><i class="fas fa-user-tie me-1"></i> Entrepreneurs</span>
                            </div>
                            <p class="text-muted">Cette formation est idéale pour toute personne souhaitant développer ses compétences dans ce domaine, quel que soit son niveau initial.</p>
                        </div>
                    </div>

                    <!-- Programme -->
                    <div class="tab-pane fade" id="programme" role="tabpanel" aria-labelledby="programme-tab">
                        <div class="accordion" id="programmeAccordion">
                            <div class="accordion-item border-0 mb-3 shadow-sm">
                                <h2 class="accordion-header" id="headingOne">
                                    <button class="accordion-button rounded" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                        <i class="fas fa-book me-2"></i>Module 1 : Les fondamentaux
                                    </button>
                                </h2>
                                <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#programmeAccordion">
                                    <div class="accordion-body">
                                        <ul class="list-unstyled mb-0">
                                            <li class="mb-2"><i class="fas fa-circle text-success me-2" style="font-size: 0.5rem; vertical-align: middle;"></i> Introduction et présentation des outils</li>
                                            <li class="mb-2"><i class="fas fa-circle text-success me-2" style="font-size: 0.5rem; vertical-align: middle;"></i> Concepts de base et méthodologie</li>
                                            <li><i class="fas fa-circle text-success me-2" style="font-size: 0.5rem; vertical-align: middle;"></i> Exercices pratiques</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item border-0 mb-3 shadow-sm">
                                <h2 class="accordion-header" id="headingTwo">
                                    <button class="accordion-button collapsed rounded" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                        <i class="fas fa-laptop-code me-2"></i>Module 2 : Techniques avancées
                                    </button>
                                </h2>
                                <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#programmeAccordion">
                                    <div class="accordion-body">
                                        <ul class="list-unstyled mb-0">
                                            <li class="mb-2"><i class="fas fa-circle text-success me-2" style="font-size: 0.5rem; vertical-align: middle;"></i> Approfondissement des concepts</li>
                                            <li class="mb-2"><i class="fas fa-circle text-success me-2" style="font-size: 0.5rem; vertical-align: middle;"></i> Études de cas concrets</li>
                                            <li><i class="fas fa-circle text-success me-2" style="font-size: 0.5rem; vertical-align: middle;"></i> Projets guidés</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item border-0 shadow-sm">
                                <h2 class="accordion-header" id="headingThree">
                                    <button class="accordion-button collapsed rounded" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                        <i class="fas fa-project-diagram me-2"></i>Module 3 : Projet final
                                    </button>
                                </h2>
                                <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#programmeAccordion">
                                    <div class="accordion-body">
                                        <ul class="list-unstyled mb-0">
                                            <li class="mb-2"><i class="fas fa-circle text-success me-2" style="font-size: 0.5rem; vertical-align: middle;"></i> Mise en pratique des acquis</li>
                                            <li class="mb-2"><i class="fas fa-circle text-success me-2" style="font-size: 0.5rem; vertical-align: middle;"></i> Développement d'un projet personnel</li>
                                            <li><i class="fas fa-circle text-success me-2" style="font-size: 0.5rem; vertical-align: middle;"></i> Évaluation et certification</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Formateur -->
                    <div class="tab-pane fade" id="formateur" role="tabpanel" aria-labelledby="formateur-tab">
                        <div class="text-center mb-4">
                            <img src="img/moi.jpg" alt="Formateur" class="rounded-circle img-thumbnail mb-3" style="width: 150px; height: 150px; object-fit: cover;">
                            <h3 class="h4">Farel Aubin MEDENOU</h3>
                            <p class="text-muted mb-3">Expert en développement web & formateur</p>
                            <div class="d-flex justify-content-center gap-2 mb-4">
                                <a href="#" class="btn btn-sm btn-outline-success rounded-circle"><i class="fab fa-linkedin-in"></i></a>
                                <a href="#" class="btn btn-sm btn-outline-success rounded-circle"><i class="fab fa-facebook"></i></a>
                                <a href="#" class="btn btn-sm btn-outline-success rounded-circle"><i class="fab fa-github"></i></a>
                            </div>
                        </div>
                        <div class="bg-light p-4 rounded-3">
                            <h4 class="h5 mb-3"><i class="fas fa-user-circle text-success me-2"></i>À propos du formateur</h4>
                            <p class="text-muted mb-0">Formateur expérimenté avec plus de 10 ans d'expérience dans le domaine. Passionné par le partage de connaissances et l'accompagnement des apprenants dans leur parcours de développement professionnel.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    <?php
        else:
            echo '<div class="alert alert-warning p-4 rounded-3 shadow-sm">
                <div class="d-flex">
                    <div class="me-3">
                        <i class="fas fa-exclamation-triangle fa-2x text-warning"></i>
                    </div>
                    <div>
                        <h4 class="alert-heading">Formation non trouvée</h4>
                        <p class="mb-0">Désolé, la formation que vous recherchez n\'existe pas ou a été supprimée.</p>
                    </div>
                </div>
            </div>';
        endif;
    } else {
        echo '<div class="alert alert-danger p-4 rounded-3 shadow-sm">
            <div class="d-flex">
                <div class="me-3">
                    <i class="fas fa-times-circle fa-2x text-danger"></i>
                </div>
                <div>
                    <h4 class="alert-heading">ID de formation invalide</h4>
                    <p class="mb-0">L\'identifiant de formation fourni n\'est pas valide. Veuillez vérifier votre lien.</p>
                </div>
            </div>
        </div>';
    }
    ?>
    
    <!-- Formations similaires -->
    <section class="mt-5 pt-5 border-top">
        <h3 class="h4 mb-4 text-center"><i class="fas fa-thumbtack me-2"></i>Formations similaires</h3>
        <div class="row row-cols-1 row-cols-md-3 g-4">
            <?php
            // Récupérer quelques formations aléatoires (simulé ici)
            $stmt_similar = $pdo->query("SELECT * FROM formations ORDER BY RAND() LIMIT 3");
            while ($similar = $stmt_similar->fetch(PDO::FETCH_ASSOC)):
            ?>
            <div class="col">
                <div class="card h-100 shadow-sm border-0 rounded-3">
                    <img src="img/<?php echo $similar['miniature'] ? $similar['miniature'] : 'default_formation.jpg'; ?>" 
                         class="card-img-top" alt="<?php echo $similar['titre']; ?>">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo $similar['titre']; ?></h5>
                        <div class="d-flex align-items-center mb-2">
                            <i class="far fa-clock text-success me-2"></i>
                            <small><?php echo $similar['duree']; ?></small>
                        </div>
                        <a href="formation_details.php?id=<?php echo $similar['id']; ?>" class="btn btn-sm btn-outline-success">
                            <i class="fas fa-info-circle me-1"></i>Détails
                        </a>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
    </section>
</div>

<?php include 'inc/footer.php'; ?>