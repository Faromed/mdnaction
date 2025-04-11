<?php include 'inc/header.php'; ?>

<div class="container py-5">
    <!-- En-tête de la page -->
    <div class="bg-white shadow-sm rounded-3 p-4 mb-5">
        <h1 class="display-4 text-center fw-bold text-success">Contactez-moi</h1>
        <div class="text-center">
            <div class="border-bottom border-2 border-success mx-auto mb-3" style="width: 100px;"></div>
        </div>
        <p class="lead text-center mb-0">
            <i class="fas fa-comment-dots text-success me-2"></i>
            N'hésitez pas à me contacter pour toute question ou demande de prestation
        </p>
    </div>

    <div class="row g-4">
        <!-- Colonne de gauche: Formulaire de contact -->
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
                <div class="card-header bg-success text-white p-4 border-0">
                    <h3 class="h4 mb-0">
                        <i class="fas fa-paper-plane me-2"></i>
                        Formulaire de contact
                    </h3>
                </div>
                <div class="card-body p-4">
                    <form action="#" method="post" class="needs-validation" novalidate>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" id="nom" name="nom" placeholder="Votre nom" required>
                                    <label for="nom">
                                        <i class="fas fa-user text-muted me-2"></i>Nom
                                    </label>
                                    <div class="invalid-feedback">
                                        Veuillez entrer votre nom.
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating mb-3">
                                    <input type="email" class="form-control" id="email" name="email" placeholder="Votre email" required>
                                    <label for="email">
                                        <i class="fas fa-envelope text-muted me-2"></i>Adresse Email
                                    </label>
                                    <div class="invalid-feedback">
                                        Veuillez entrer une adresse email valide.
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="sujet" name="sujet" placeholder="Sujet de votre message" required>
                            <label for="sujet">
                                <i class="fas fa-heading text-muted me-2"></i>Sujet
                            </label>
                            <div class="invalid-feedback">
                                Veuillez entrer un sujet pour votre message.
                            </div>
                        </div>
                        
                        <div class="form-floating mb-4">
                            <textarea class="form-control" id="message" name="message" placeholder="Votre message" style="height: 150px" required></textarea>
                            <label for="message">
                                <i class="fas fa-comment text-muted me-2"></i>Message
                            </label>
                            <div class="invalid-feedback">
                                Veuillez entrer votre message.
                            </div>
                        </div>
                        
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-outline-success py-3">
                                <i class="fas fa-paper-plane me-2"></i>Envoyer le message
                            </button>
                        </div>
                    </form>
                    
                    <div class="mt-4">
                        <?php
                        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                            // Ici, vous pouvez ajouter le code pour envoyer l'e-mail
                            // ou enregistrer le message dans une base de données.
                            echo '<div class="alert alert-success d-flex align-items-center" role="alert">
                                <i class="fas fa-check-circle me-2 fa-lg"></i>
                                <div>
                                    Votre message a été envoyé avec succès. Je vous répondrai dans les plus brefs délais.
                                </div>
                            </div>';
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Colonne de droite: Coordonnées et carte -->
        <div class="col-lg-5">
            <!-- Carte d'informations de contact -->
            <div class="card border-0 shadow-sm rounded-3 mb-4">
                <div class="card-header bg-success bg-opacity-25 text-success p-4 border-0">
                    <h3 class="h4 mb-0">
                        <i class="fas fa-address-card me-2"></i>
                        Mes coordonnées
                    </h3>
                </div>
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-4">
                        <div class="rounded-circle bg-success bg-opacity-10 p-3 me-3">
                            <i class="fas fa-envelope text-success"></i>
                        </div>
                        <div>
                            <h4 class="h6 mb-1">Email</h4>
                            <a href="mailto:mdnaction1@gmail.com" class="text-decoration-none text-success">mdnaction1@gmail.com</a>
                        </div>
                    </div>
                    
                    <div class="d-flex align-items-center mb-4">
                        <div class="rounded-circle bg-success bg-opacity-10 p-3 me-3">
                            <i class="fas fa-phone text-success"></i>
                        </div>
                        <div>
                            <h4 class="h6 mb-1">Téléphone</h4>
                            <a href="tel:+22901649797" class="text-decoration-none text-success">+229 01 64 97 97 90</a>
                        </div>
                    </div>
                    
                    <div class="d-flex align-items-center mb-4">
                        <div class="rounded-circle bg-success bg-opacity-10 p-3 me-3">
                            <i class="fas fa-clock text-success"></i>
                        </div>
                        <div>
                            <h4 class="h6 mb-1">Horaires</h4>
                            <p class="mb-0">Lun - Ven: 9h00 - 18h00</p>
                        </div>
                    </div>
                    
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle bg-success bg-opacity-10 p-3 me-3">
                            <i class="fas fa-map-marker-alt text-success"></i>
                        </div>
                        <div>
                            <h4 class="h6 mb-1">Adresse</h4>
                            <p class="mb-0">Cotonou, Bénin</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Carte de localisation -->
            <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
                <div class="card-header bg-success bg-opacity-25 text-success p-4 border-0">
                    <h3 class="h4 mb-0">
                        <i class="fas fa-map-marked-alt me-2"></i>
                        Localisation
                    </h3>
                </div>
                <div class="card-body p-0">
                    <div class="ratio ratio-16x9">
                        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3965.14097347923!2d2.360748474796843!3d6.36315779363995!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x102340a8f1c7b37f%3A0x3a0d5f9a8b8b8b8b!2sVotre%20adresse%20ici!5e0!3m2!1sfr!2sbj!4v1633000000000!5m2!1sfr!2sbj" 
                              style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                    </div>
                </div>
            </div>
            
            <!-- Réseaux sociaux -->
            <div class="card border-0 shadow-sm rounded-3 mt-4">
                <div class="card-body p-4">
                    <h3 class="h5 mb-3">
                        <i class="fas fa-share-alt text-success me-2"></i>
                        Suivez-moi
                    </h3>
                    <div class="d-flex gap-2">
                        <a href="#" class="btn btn-outline-success rounded-circle" title="Facebook">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" class="btn btn-outline-info rounded-circle" title="Twitter">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="#" class="btn btn-outline-danger rounded-circle" title="Instagram">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="#" class="btn btn-outline-success rounded-circle" title="LinkedIn">
                            <i class="fab fa-linkedin-in"></i>
                        </a>
                        <a href="#" class="btn btn-outline-dark rounded-circle" title="GitHub">
                            <i class="fab fa-github"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- FAQ Section -->
    <div class="row mt-5">
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-header bg-success text-white p-4 border-0">
                    <h3 class="h4 mb-0">
                        <i class="fas fa-question-circle me-2"></i>
                        Questions fréquentes
                    </h3>
                </div>
                <div class="card-body p-4">
                    <div class="accordion" id="faqAccordion">
                        <div class="accordion-item border-0 mb-3 shadow-sm">
                            <h2 class="accordion-header" id="headingOne">
                                <button class="accordion-button rounded-3" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                    <i class="fas fa-question-circle text-success me-2"></i>
                                    Dans quels délais répondez-vous aux demandes?
                                </button>
                            </h2>
                            <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    Je m'engage à répondre à toutes les demandes dans un délai de 24 à 48 heures ouvrables. Pour les projets urgents, n'hésitez pas à le mentionner dans votre message.
                                </div>
                            </div>
                        </div>
                        
                        <div class="accordion-item border-0 mb-3 shadow-sm">
                            <h2 class="accordion-header" id="headingTwo">
                                <button class="accordion-button collapsed rounded-3" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                    <i class="fas fa-question-circle text-success me-2"></i>
                                    Comment se déroule une collaboration à distance?
                                </button>
                            </h2>
                            <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    Les collaborations à distance se déroulent en plusieurs étapes : une première réunion pour définir vos besoins, l'établissement d'un devis, des points d'étape réguliers et la livraison finale. La communication se fait principalement par email, téléphone ou visioconférence selon vos préférences.
                                </div>
                            </div>
                        </div>
                        
                        <div class="accordion-item border-0 shadow-sm">
                            <h2 class="accordion-header" id="headingThree">
                                <button class="accordion-button collapsed rounded-3" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                    <i class="fas fa-question-circle text-success me-2"></i>
                                    Proposez-vous un suivi après la réalisation d'un projet?
                                </button>
                            </h2>
                            <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    Oui, tous mes projets incluent une période de suivi et de maintenance après la livraison. Cette période varie selon la nature et l'ampleur du projet. Des prestations de maintenance à plus long terme sont également disponibles.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Script pour la validation du formulaire -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Fetch all forms with the class 'needs-validation'
    var forms = document.querySelectorAll('.needs-validation');
    
    // Loop over them and prevent submission
    Array.prototype.slice.call(forms).forEach(function(form) {
        form.addEventListener('submit', function(event) {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            
            form.classList.add('was-validated');
        }, false);
    });
});
</script>

<?php include 'inc/footer.php'; ?>