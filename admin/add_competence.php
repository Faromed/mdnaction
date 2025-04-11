<?php
require '../inc/db_config.php';
require 'inc/auth.php';
force_login();
include 'inc/header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titre = $_POST['titre'];
    $description = $_POST['description'];
    $icone = $_POST['icone']; // Gérer l'upload d'image séparément

    try {
        $stmt = $pdo->prepare("INSERT INTO competences (titre, description, icone) VALUES (?, ?, ?)");
        $stmt->execute([$titre, $description, $icone]);
        $_SESSION['success_message'] = 'Compétence ajoutée avec succès.';
        header('Location: manage_competences.php');
        exit;
    } catch (PDOException $e) {
        $_SESSION['error_message'] = 'Erreur lors de l\'ajout de la compétence : ' . $e->getMessage();
        header('Location: manage_competences.php');
        exit;
    }
}
?>

<h2>Ajouter une Compétence</h2>

<form method="post">
    <div class="mb-3">
        <label for="titre" class="form-label">Titre</label>
        <input type="text" class="form-control" id="titre" name="titre" required>
    </div>
    <div class="mb-3">
        <label for="description" class="form-label">Description</label>
        <textarea class="form-control" id="description" name="description" rows="3"></textarea>
    </div>
    <div class="mb-3">
        <label for="icone" class="form-label">Icône (Nom du fichier)</label>
        <input type="text" class="form-control" id="icone" name="icone">
        <small class="text-muted">Le fichier image doit être téléversé via la page d'upload.</small>
    </div>
    <button type="submit" class="btn btn-primary">Ajouter</button>
    <a href="manage_competences.php" class="btn btn-secondary">Annuler</a>
</form>

<?php include 'inc/footer.php'; ?>