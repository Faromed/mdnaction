<?php
require '../inc/db_config.php';
require 'inc/auth.php';
force_login();
include 'inc/header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titre = $_POST['titre'];
    $image = $_POST['image']; // Gérer l'upload d'image séparément
    $description = $_POST['description'];
    $technologies_utilisees = $_POST['technologies_utilisees'];
    $lien_live = $_POST['lien_live'];

    try {
        $stmt = $pdo->prepare("INSERT INTO projets (titre, image, description, technologies_utilisees, lien_live) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$titre, $image, $description, $technologies_utilisees, $lien_live]);
        $_SESSION['success_message'] = 'Projet ajouté avec succès.';
        header('Location: manage_projects.php');
        exit;
    } catch (PDOException $e) {
        $_SESSION['error_message'] = 'Erreur lors de l\'ajout du projet : ' . $e->getMessage();
        header('Location: manage_projects.php');
        exit;
    }
}
?>

<h2>Ajouter un Projet</h2>

<form method="post">
    <div class="mb-3">
        <label for="titre" class="form-label">Titre</label>
        <input type="text" class="form-control" id="titre" name="titre" required>
    </div>
    <div class="mb-3">
        <label for="image" class="form-label">Image (Nom du fichier)</label>
        <input type="text" class="form-control" id="image" name="image">
        <small class="text-muted">Le fichier image doit être téléversé via la page d'upload.</small>
    </div>
    <div class="mb-3">
        <label for="description" class="form-label">Description</label>
        <textarea class="form-control" id="description" name="description" rows="5"></textarea>
    </div>
    <div class="mb-3">
        <label for="technologies_utilisees" class="form-label">Technologies Utilisées</label>
        <input type="text" class="form-control" id="technologies_utilisees" name="technologies_utilisees">
    </div>
    <div class="mb-3">
        <label for="lien_live" class="form-label">Lien Live</label>
        <input type="url" class="form-control" id="lien_live" name="lien_live">
    </div>
    <button type="submit" class="btn btn-primary">Ajouter</button>
    <a href="manage_projects.php" class="btn btn-secondary">Annuler</a>
</form>

<?php include 'inc/footer.php'; ?>