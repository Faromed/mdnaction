<?php include 'inc/header.php'; ?>

<div class="container py-5">
    <!-- En-tête de section avec style amélioré -->
    <div class="row mb-5">
        <div class="col-12 text-center">
            <div class="mb-4">
                <i class="fas fa-graduation-cap fa-3x text-primary mb-3"></i>
                <h2 class="display-4 fw-bold">Mes Formations</h2>
                <div class="divider mx-auto my-3" style="width: 80px; height: 4px; background-color: #0d6efd;"></div>
                <p class="lead text-muted">Découvrez les formations que je propose pour développer vos compétences</p>
            </div>
        </div>
    </div>

    <!-- Liste des formations avec cartes améliorées -->
    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4 mb-5">
        <?php
        require 'inc/db_config.php';
        $stmt = $pdo->query("SELECT * FROM formations ORDER BY date_creation DESC");
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)):
        ?>
        <div class="col">
            <div class="card h-100 shadow-sm border-0 rounded-3 hover-shadow transition">
                <div class="position-relative">
                    <img src="img/<?php echo $row['miniature'] ? $row['miniature'] : 'default_formation.jpg'; ?>" 
                         class="card-img-top rounded-top" alt="<?php echo $row['titre']; ?>">
                    <div class="badge bg-primary position-absolute top-0 end-0 m-3">
                        Nouvelle formation
                    </div>
                </div>
                <div class="card-body p-4">
                    <h5 class="card-title fw-bold mb-3"><?php echo $row['titre']; ?></h5>
                    <p class="card-text text-muted mb-3"><?php echo substr($row['description'], 0, 100); ?>...</p>
                    <div class="d-flex align-items-center mb-3">
                        <i class="far fa-clock text-primary me-2"></i>
                        <p class="card-text mb-0"><small>Durée : <?php echo $row['duree']; ?></small></p>
                    </div>
                </div>
                <div class="card-footer bg-white border-top-0 p-4">
                    <a href="formation_details.php?id=<?php echo $row['id']; ?>" 
                       class="btn btn-primary w-100">
                        <i class="fas fa-info-circle me-2"></i>En savoir plus
                    </a>
                </div>
            </div>
        </div>
        <?php endwhile; ?>
    </div>

    <!-- Section témoignages améliorée -->
    <section class="py-5 bg-light rounded-3 shadow-sm">
        <div class="container">
            <div class="row mb-4">
                <div class="col-12 text-center">
                    <i class="fas fa-quote-left fa-2x text-primary mb-3"></i>
                    <h2 class="fw-bold">Témoignages</h2>
                    <div class="divider mx-auto my-3" style="width: 60px; height: 3px; background-color: #0d6efd;"></div>
                </div>
            </div>

            <div class="row row-cols-1 row-cols-md-2 g-4">
                <?php
                $stmt_testimonials = $pdo->query("SELECT * FROM temoignages ORDER BY date_creation DESC LIMIT 2");
                while ($testimonial = $stmt_testimonials->fetch(PDO::FETCH_ASSOC)):
                ?>
                <div class="col">
                    <div class="card h-100 border-0 shadow-sm rounded-3">
                        <div class="card-body p-4">
                            <div class="d-flex mb-3">
                                <?php for($i=0; $i<5; $i++): ?>
                                    <i class="fas fa-star text-warning"></i>
                                <?php endfor; ?>
                            </div>
                            <blockquote class="blockquote mb-0">
                                <p class="fs-5 fst-italic">"<?php echo $testimonial['temoignage']; ?>"</p>
                                <div class="d-flex align-items-center mt-3">
                                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-3" 
                                         style="width: 50px; height: 50px;">
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

            <div class="text-center mt-4">
                <a href="#" class="btn btn-outline-primary">
                    <i class="fas fa-comments me-2"></i>Voir tous les témoignages
                </a>
            </div>
        </div>
    </section>
</div>

<?php include 'inc/footer.php'; ?>