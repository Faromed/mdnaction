<?php include 'inc/header.php'; ?>

<div class="container py-5">
    <!-- Section d'en-tête avec fond subtil -->
    <div class="bg-white shadow-sm rounded-3 p-4 mb-5">
        <h1 class="display-4 text-center mb-3 fw-bold text-primary">À Propos de Moi</h1>
        <div class="text-center">
            <div class="border-bottom border-2 border-primary mx-auto mb-4" style="width: 100px;"></div>
        </div>
    </div>
    
    <div class="row g-4 align-items-center">
        <!-- Colonne photo -->
        <div class="col-lg-4">
            <div class="position-relative mb-4">
                <div class="bg-primary position-absolute rounded-circle" style="width: 95%; height: 95%; bottom: -10px; right: -10px; z-index: 0;"></div>
                <img src="img/mdn.jpeg" alt="Votre Photo" class="img-fluid rounded-circle shadow position-relative" style="z-index: 1;">
            </div>
            
            <!-- Carte de contact rapide -->
            <div class="card border-0 shadow-sm rounded-3 p-3 mb-4">
                <div class="d-flex align-items-center mb-3">
                    <i class="fas fa-envelope text-primary me-3 fa-lg"></i>
                    <div>
                        <small class="text-muted d-block">Email</small>
                        <span>contact@votre-domaine.com</span>
                    </div>
                </div>
                <div class="d-flex align-items-center mb-3">
                    <i class="fas fa-phone text-primary me-3 fa-lg"></i>
                    <div>
                        <small class="text-muted d-block">Téléphone</small>
                        <span>+33 6 XX XX XX XX</span>
                    </div>
                </div>
                <div class="d-flex align-items-center">
                    <i class="fas fa-map-marker-alt text-primary me-3 fa-lg"></i>
                    <div>
                        <small class="text-muted d-block">Localisation</small>
                        <span>Paris, France</span>
                    </div>
                </div>
            </div>
            
            <!-- Réseaux sociaux -->
            <div class="d-flex justify-content-center mt-4">
                <a href="#" class="btn btn-light rounded-circle shadow-sm mx-2" title="LinkedIn">
                    <i class="fab fa-linkedin text-primary"></i>
                </a>
                <a href="#" class="btn btn-light rounded-circle shadow-sm mx-2" title="GitHub">
                    <i class="fab fa-github text-primary"></i>
                </a>
                <a href="#" class="btn btn-light rounded-circle shadow-sm mx-2" title="Twitter">
                    <i class="fab fa-twitter text-primary"></i>
                </a>
            </div>
        </div>
        
        <!-- Colonne biographie -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-3 p-4">
                <h2 class="h3 mb-4">
                    <i class="fas fa-user-circle text-primary me-2"></i>
                    Biographie Professionnelle
                </h2>
                
                <div class="mb-4">
                    <p class="lead">
                        Passionné par le développement web et l'innovation technologique, je mets mon expertise au service de vos projets numériques.
                    </p>
                </div>
                
                <div class="mb-4">
                    <p>
                        Ici, vous pouvez écrire une description de vous-même, de votre parcours professionnel, de votre expertise et de votre mission.
                        Parlez de votre expérience dans le domaine du développement web et de la formation.
                        Mettez en avant vos compétences et ce qui vous passionne dans votre travail.
                    </p>
                    <p>
                        Vous pouvez également mentionner vos valeurs, votre approche de travail et ce que les clients ou les étudiants peuvent attendre de vous.
                        N'hésitez pas à partager quelques détails personnels si vous le souhaitez pour rendre cette section plus engageante.
                    </p>
                </div>
                
                <!-- Citation -->
                <div class="bg-light p-4 rounded-3 mb-4 border-start border-4 border-primary">
                    <p class="fst-italic mb-0">
                        "Passionné par le développement web depuis 2015, j'ai acquis une solide expérience dans la création de sites web performants et intuitifs. Mon objectif est d'aider les autres à maîtriser les outils du web pour développer leurs propres projets et atteindre leurs objectifs professionnels."
                    </p>
                </div>
                
                <!-- Boutons d'action -->
                <div class="d-flex flex-wrap gap-3 mt-4">
                    <a href="img/votre_cv.pdf" class="btn btn-primary shadow-sm" download>
                        <i class="fas fa-file-download me-2"></i>Télécharger mon CV
                    </a>
                    <a href="contact.php" class="btn btn-outline-primary">
                        <i class="fas fa-paper-plane me-2"></i>Me contacter
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Section des valeurs -->
    <div class="row mt-5 g-4">
        <div class="col-12">
            <h2 class="h3 text-center mb-4">
                <i class="fas fa-star text-primary me-2"></i>
                Mes Valeurs
            </h2>
        </div>
        
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-3 p-4 h-100 text-center">
                <div class="rounded-circle bg-primary bg-opacity-10 p-3 d-inline-flex mx-auto mb-3" style="width: 80px; height: 80px;">
                    <i class="fas fa-lightbulb text-primary m-auto fa-2x"></i>
                </div>
                <h3 class="h5 mb-3">Innovation</h3>
                <p class="mb-0">Toujours à l'affût des dernières technologies pour proposer des solutions modernes et performantes.</p>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-3 p-4 h-100 text-center">
                <div class="rounded-circle bg-primary bg-opacity-10 p-3 d-inline-flex mx-auto mb-3" style="width: 80px; height: 80px;">
                    <i class="fas fa-handshake text-primary m-auto fa-2x"></i>
                </div>
                <h3 class="h5 mb-3">Engagement</h3>
                <p class="mb-0">Dédié à l'excellence et à la satisfaction client pour des résultats qui dépassent vos attentes.</p>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-3 p-4 h-100 text-center">
                <div class="rounded-circle bg-primary bg-opacity-10 p-3 d-inline-flex mx-auto mb-3" style="width: 80px; height: 80px;">
                    <i class="fas fa-comments text-primary m-auto fa-2x"></i>
                </div>
                <h3 class="h5 mb-3">Communication</h3>
                <p class="mb-0">Une approche transparente et des échanges clairs pour une collaboration efficace et sereine.</p>
            </div>
        </div>
    </div>
</div>

<?php include 'inc/footer.php'; ?>