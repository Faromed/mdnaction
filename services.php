<?php include 'inc/header.php'; ?>

<div class="container py-5">
    <!-- En-tête de section -->
    <div class="row mb-5">
        <div class="col-12 text-center">
            <div class="mb-4">
                <i class="fas fa-cogs fa-3x text-primary mb-3"></i>
                <h2 class="display-4 fw-bold">Mes Services</h2>
                <div class="divider mx-auto my-3" style="width: 80px; height: 4px; background-color: #0d6efd;"></div>
                <p class="lead text-muted">Découvrez les prestations que je propose pour vous accompagner dans vos projets</p>
            </div>
        </div>
    </div>

    <!-- Services -->
    <div class="row row-cols-1 row-cols-md-2 g-4 mb-5">
        <?php
        require 'inc/db_config.php';
        $stmt = $pdo->query("SELECT * FROM services ORDER BY date_creation DESC");
        $count = 0;
        
        // Icônes par défaut pour différents types de services
        $default_icons = [
            'web' => 'fas fa-laptop-code',
            'design' => 'fas fa-paint-brush',
            'marketing' => 'fas fa-bullhorn',
            'formation' => 'fas fa-chalkboard-teacher',
            'consulting' => 'fas fa-comments',
            'support' => 'fas fa-headset'
        ];
        
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)):
            $count++;
            
            // Choisir une icône selon le titre du service
            $icon_class = 'fas fa-cogs'; // Icône par défaut
            foreach ($default_icons as $keyword => $icon) {
                if (stripos($row['titre'], $keyword) !== false) {
                    $icon_class = $icon;
                    break;
                }
            }
            
            // Déterminer la couleur de la carte
            $colors = ['primary', 'info', 'success', 'warning']; 
            $card_color = $colors[($count - 1) % count($colors)];
        ?>
        <div class="col">
            <div class="card h-100 border-0 shadow-sm rounded-3 service-card">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-4">
                        <div class="service-icon bg-<?php echo $card_color; ?> text-white">
                            <?php if ($row['image_ou_icone']): ?>
                                <img src="img/<?php echo $row['image_ou_icone']; ?>" alt="<?php echo $row['titre']; ?>" class="img-fluid">
                            <?php else: ?>
                                <i class="<?php echo $icon_class; ?> fa-2x"></i>
                            <?php endif; ?>
                        </div>
                        <h3 class="card-title h4 ms-3 mb-0"><?php echo $row['titre']; ?></h3>
                    </div>
                    <p class="card-text text-muted"><?php echo $row['description']; ?></p>
                    
                    <?php if (!empty($row['caracteristiques'])): ?>
                        <div class="mt-4">
                            <h5 class="h6 fw-bold mb-3"><i class="fas fa-check text-<?php echo $card_color; ?> me-2"></i>Ce qui est inclus</h5>
                            <ul class="list-unstyled mb-0">
                                <?php 
                                $features = explode(',', $row['caracteristiques']);
                                foreach ($features as $feature):
                                    $feature = trim($feature);
                                    if (!empty($feature)):
                                ?>
                                    <li class="mb-2">
                                        <i class="fas fa-check-circle text-<?php echo $card_color; ?> me-2"></i>
                                        <?php echo $feature; ?>
                                    </li>
                                <?php 
                                    endif;
                                endforeach; 
                                ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="card-footer bg-white border-top-0 p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <?php if ($row['prix']): ?>
                            <div class="price-tag">
                                <span class="h4 fw-bold text-<?php echo $card_color; ?> mb-0"><?php echo $row['prix']; ?></span>
                                <?php if (strpos($row['prix'], '€/') !== false || strpos($row['prix'], '€ /') !== false): ?>
                                    <span class="text-muted">par mois</span>
                                <?php endif; ?>
                            </div>
                        <?php else: ?>
                            <div class="price-tag">
                                <span class="text-muted">Prix sur demande</span>
                            </div>
                        <?php endif; ?>
                        <a href="contact.php?service=<?php echo urlencode($row['titre']); ?>" class="btn btn-outline-<?php echo $card_color; ?>">
                            <i class="fas fa-arrow-right me-2"></i>En savoir plus
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <?php endwhile; ?>
    </div>
    
    <!-- Comment ça marche -->
    <section class="bg-light rounded-3 p-5 mb-5 shadow-sm">
        <div class="row mb-4">
            <div class="col-12 text-center">
                <h3 class="h2 fw-bold"><i class="fas fa-question-circle text-primary me-2"></i>Comment ça marche ?</h3>
                <p class="text-muted">Une méthodologie simple et efficace pour répondre au mieux à vos besoins</p>
            </div>
        </div>
        
        <div class="row row-cols-1 row-cols-md-3 g-4">
            <div class="col">
                <div class="card h-100 border-0 rounded-3">
                    <div class="card-body text-center p-4">
                        <div class="process-icon mb-3">
                            <span class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center mx-auto">
                                <i class="fas fa-comments fa-2x"></i>
                            </span>
                            <div class="process-step">1</div>
                        </div>
                        <h4 class="card-title h5 mb-3">Consultation initiale</h4>
                        <p class="card-text text-muted">Nous discutons de votre projet, de vos objectifs et de vos besoins spécifiques.</p>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card h-100 border-0 rounded-3">
                    <div class="card-body text-center p-4">
                        <div class="process-icon mb-3">
                            <span class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center mx-auto">
                                <i class="fas fa-file-contract fa-2x"></i>
                            </span>
                            <div class="process-step">2</div>
                        </div>
                        <h4 class="card-title h5 mb-3">Proposition personnalisée</h4>
                        <p class="card-text text-muted">Je vous propose une solution sur mesure avec un devis détaillé et un calendrier.</p>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card h-100 border-0 rounded-3">
                    <div class="card-body text-center p-4">
                        <div class="process-icon mb-3">
                            <span class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center mx-auto">
                                <i class="fas fa-rocket fa-2x"></i>
                            </span>
                            <div class="process-step">3</div>
                        </div>
                        <h4 class="card-title h5 mb-3">Réalisation et suivi</h4>
                        <p class="card-text text-muted">Je réalise le projet en vous tenant informé à chaque étape de l'avancement.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Témoignages -->
    <section class="mb-5">
        <div class="row mb-4">
            <div class="col-12 text-center">
                <h3 class="h2 fw-bold"><i class="fas fa-star text-warning me-2"></i>Ce que disent mes clients</h3>
                <p class="text-muted">Découvrez les retours d'expérience de ceux qui m'ont fait confiance</p>
            </div>
        </div>
        
        <div class="testimonial-slider">
            <div class="row">
                <?php
                $stmt_testimonials = $pdo->query("SELECT * FROM temoignages ORDER BY date_creation DESC LIMIT 3");
                while ($testimonial = $stmt_testimonials->fetch(PDO::FETCH_ASSOC)):
                ?>
                <div class="col-md-4 mb-4">
                    <div class="card h-100 border-0 shadow-sm rounded-3">
                        <div class="card-body p-4">
                            <div class="d-flex mb-3">
                                <?php for($i=0; $i<5; $i++): ?>
                                    <i class="fas fa-star text-warning"></i>
                                <?php endfor; ?>
                            </div>
                            <blockquote class="blockquote mb-0">
                                <p class="fs-6 fst-italic">"<?php echo $testimonial['temoignage']; ?>"</p>
                                <div class="d-flex align-items-center mt-3">
                                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-3" 
                                         style="width: 40px; height: 40px;">
                                        <span class="fw-bold">
                                            <?php echo substr($testimonial['nom'], 0, 1); ?>
                                        </span>
                                    </div>
                                    <footer class="blockquote-footer mb-0">
                                        <strong><?php echo $testimonial['nom']; ?></strong><br>
                                        <cite title="Source Title"><?php echo $testimonial['profession']; ?></cite>
                                    </footer>
                                </div>
                            </blockquote>
                        </div>
                    </div>
                </div>
                <?php endwhile; ?>
            </div>
        </div>
    </section>
    
    <!-- Carte FAQ -->
    <section class="bg-light rounded-3 p-5 shadow-sm">
        <div class="row mb-4">
            <div class="col-12 text-center">
                <h3 class="h2 fw-bold"><i class="fas fa-question-circle text-primary me-2"></i>Questions fréquentes</h3>
                <p class="text-muted">Trouvez rapidement des réponses à vos interrogations</p>
            </div>
        </div>
        
        <div class="row">
            <div class="col-lg-6 mb-4">
                <div class="accordion" id="faqAccordion1">
                    <div class="accordion-item shadow-sm border-0 rounded mb-3">
                        <h2 class="accordion-header" id="headingOne">
                            <button class="accordion-button rounded" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                <i class="fas fa-question-circle text-primary me-2"></i>Comment se déroule notre collaboration ?
                            </button>
                        </h2>
                        <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#faqAccordion1">
                            <div class="accordion-body">
                                Notre collaboration débute par un échange pour comprendre vos besoins, suivi d'une proposition personnalisée. Une fois le projet lancé, je vous tiens régulièrement informé de son avancement et reste disponible pour toute question.
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item shadow-sm border-0 rounded mb-3">
                        <h2 class="accordion-header" id="headingTwo">
                            <button class="accordion-button collapsed rounded" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                <i class="fas fa-question-circle text-primary me-2"></i>Quels sont vos délais moyens de réalisation ?
                            </button>
                        </h2>
                        <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#faqAccordion1">
                            <div class="accordion-body">
                                Les délais varient selon la complexité du projet. Un site vitrine simple peut être réalisé en 2-3 semaines, tandis qu'un projet plus complexe peut prendre 1 à 3 mois. Je m'engage toujours à respecter les échéances fixées ensemble.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="accordion" id="faqAccordion2">
                    <div class="accordion-item shadow-sm border-0 rounded mb-3">
                        <h2 class="accordion-header" id="headingThree">
                            <button class="accordion-button collapsed rounded" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                <i class="fas fa-question-circle text-primary me-2"></i>Proposez-vous un suivi après la livraison du projet ?
                            </button>
                        </h2>
                        <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#faqAccordion2">
                            <div class="accordion-body">
                                Oui, tous mes services incluent une période de suivi après livraison pour s'assurer que tout fonctionne correctement. Je propose également des forfaits de maintenance pour un accompagnement à long terme.
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item shadow-sm border-0 rounded">
                        <h2 class="accordion-header" id="headingFour">
                            <button class="accordion-button collapsed rounded" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                                <i class="fas fa-question-circle text-primary me-2"></i>Comment sont déterminés vos tarifs ?
                            </button>
                        </h2>
                        <div id="collapseFour" class="accordion-collapse collapse" aria-labelledby="headingFour" data-bs-parent="#faqAccordion2">
                            <div class="accordion-body">
                                Mes tarifs sont calculés en fonction de la complexité du projet, du temps estimé pour sa réalisation et des fonctionnalités demandées. Je privilégie la transparence avec des devis détaillés sans frais cachés.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row mt-4">
            <div class="col-12 text-center">
                <p class="mb-4">Vous avez d'autres questions ?</p>
                <a href="contact.php" class="btn btn-primary">
                    <i class="fas fa-envelope me-2"></i>Me contacter
                </a>
            </div>
        </div>
    </section>
</div>

<style>
    .service-card {
        transition: transform 0.3s ease;
    }
    
    .service-card:hover {
        transform: translateY(-5px);
    }
    
    .service-icon {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .process-icon {
        position: relative;
    }
    
    .process-icon span {
        width: 80px;
        height: 80px;
        position: relative;
        z-index: 1;
    }
    
    .process-step {
        position: absolute;
        top: -10px;
        right: calc(50% - 55px);
        width: 30px;
        height: 30px;
        border-radius: 50%;
        background-color: #dc3545;
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        z-index: 2;
    }
</style>

<?php include 'inc/footer.php'; ?>