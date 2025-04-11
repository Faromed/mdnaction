<?php
require '../inc/db_config.php';
require 'inc/auth.php';
force_login();
include 'inc/header.php';
?>

<h2>Tableau de Bord de l'Administration</h2>
<p>Bienvenue, Administrateur !</p>

<div class="row">
    <div class="col-md-3 mb-3">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <h5 class="card-title">Formations</h5>
                <?php $stmt = $pdo->query("SELECT COUNT(*) FROM formations"); $count = $stmt->fetchColumn(); ?>
                <p class="card-text"><?php echo $count; ?> formations</p>
                <a href="manage_formations.php" class="btn btn-sm btn-light">Gérer</a>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <h5 class="card-title">Services</h5>
                <?php $stmt = $pdo->query("SELECT COUNT(*) FROM services"); $count = $stmt->fetchColumn(); ?>
                <p class="card-text"><?php echo $count; ?> services</p>
                <a href="manage_services.php" class="btn btn-sm btn-light">Gérer</a>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card bg-info text-white">
            <div class="card-body">
                <h5 class="card-title">Projets</h5>
                <?php $stmt = $pdo->query("SELECT COUNT(*) FROM projets"); $count = $stmt->fetchColumn(); ?>
                <p class="card-text"><?php echo $count; ?> projets</p>
                <a href="manage_projects.php" class="btn btn-sm btn-light">Gérer</a>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card bg-warning text-dark">
            <div class="card-body">
                <h5 class="card-title">Blog</h5>
                <?php $stmt = $pdo->query("SELECT COUNT(*) FROM blog"); $count = $stmt->fetchColumn(); ?>
                <p class="card-text"><?php echo $count; ?> articles</p>
                <a href="manage_blog.php" class="btn btn-sm btn-light">Gérer</a>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card bg-danger text-white">
            <div class="card-body">
                <h5 class="card-title">Témoignages</h5>
                <?php $stmt = $pdo->query("SELECT COUNT(*) FROM temoignages"); $count = $stmt->fetchColumn(); ?>
                <p class="card-text"><?php echo $count; ?> témoignages</p>
                <a href="manage_testimonials.php" class="btn btn-sm btn-light">Gérer</a>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card bg-secondary text-white">
            <div class="card-body">
                <h5 class="card-title">Commentaires</h5>
                <?php $stmt = $pdo->query("SELECT COUNT(*) FROM commentaires WHERE est_approuve = FALSE"); $count = $stmt->fetchColumn(); ?>
                <p class="card-text"><?php echo $count; ?> en attente</p>
                <a href="manage_comments.php" class="btn btn-sm btn-light">Modérer</a>
            </div>
        </div>
    </div>
</div>

<p><a href="logout.php" class="btn btn-dark">Se déconnecter</a></p>

<?php include 'inc/footer.php'; ?>