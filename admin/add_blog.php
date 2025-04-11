<?php
require '../inc/db_config.php';
require 'inc/auth.php';
force_login();
include 'inc/header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titre = $_POST['titre'];
    $contenu = $_POST['contenu'];
    $auteur = $_POST['auteur'];
    $image = $_POST['image']; // Gérer l'upload d'image séparément

    try {
        $stmt = $pdo->prepare("INSERT INTO blog (titre, contenu, auteur, image) VALUES (?, ?, ?, ?)");
        $stmt->execute([$titre, $contenu, $auteur, $image]);
        $_SESSION['success_message'] = 'Article de blog ajouté avec succès.';
        header('Location: manage_blog.php');
        exit;
    } catch (PDOException $e) {
        $_SESSION['error_message'] = 'Erreur lors de l\'ajout de l\'article de blog : ' . $e->getMessage();
        header('Location: manage_blog.php');
        exit;
    }
}
?>

<h2>Ajouter un Article de Blog</h2>

<form method="post">
    <div class="mb-3">
        <label for="titre" class="form-label">Titre</label>
        <input type="text" class="form-control" id="titre" name="titre" required>
    </div>
    <div class="mb-3">
        <label for="contenu" class="form-label">Contenu</label>
        <textarea class="form-control" id="contenu" name="contenu" rows="10"></textarea>
    </div>
    <div class="mb-3">
        <label for="auteur" class="form-label">Auteur</label>
        <input type="text" class="form-control" id="auteur" name="auteur">
    </div>
    <div class="mb-3">
        <label for="image" class="form-label">Image (Nom du fichier)</label>
        <input type="text" class="form-control" id="image" name="image">
        <small class="text-muted">Le fichier image doit être téléversé via la page d'upload.</small>
    </div>
    <button type="submit" class="btn btn-primary">Ajouter</button>
    <a href="manage_blog.php" class="btn btn-secondary">Annuler</a>
</form>

<?php include 'inc/footer.php'; ?>