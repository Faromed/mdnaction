<?php
require '../inc/db_config.php';
require 'inc/auth.php';
force_login();
include 'inc/header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titre = $_POST['titre'];
    $description = $_POST['description'];
    $image_ou_icone = $_POST['image_ou_icone']; // Vous devrez gérer l'upload d'image séparément
    $prix = $_POST['prix'];

    try {
        $stmt = $pdo->prepare("INSERT INTO services (titre, description, image_ou_icone, prix) VALUES (?, ?, ?, ?)");
        $stmt->execute([$titre, $description, $image_ou_icone, $prix]);
        $_SESSION['success_message'] = 'Service ajouté avec succès.';
        header('Location: manage_services.php');
        exit;
    } catch (PDOException $e) {
        $_SESSION['error_message'] = 'Erreur lors de l\'ajout du service : ' . $e->getMessage();
        header('Location: manage_services.php');
        exit;
    }
}
?>

<h2>Ajouter un Service</h2>

<form method="post">
    <div class="mb-3">
        <label for="titre" class="form-label">Titre</label>
        <input type="text" class="form-control" id="titre" name="titre" required>
    </div>
    <div class="mb-3">
        <label for="description" class="form-label">Description</label>
        <textarea class="form-control" id="description" name="description" rows="5"></textarea>
    </div>
    <div class="mb-3">
        <label for="image_ou_icone" class="form-label">Image ou Icône (Nom du fichier)</label>
        <input type="text" class="form-control" id="image_ou_icone" name="image_ou_icone">
        <small class="text-muted">Le fichier image doit être téléversé via la page d'upload.</small>
    </div>
    <div class="mb-3">
        <label for="prix" class="form-label">Prix (Optionnel)</label>
        <input type="text" class="form-control" id="prix" name="prix">
    </div>
    <button type="submit" class="btn btn-primary">Ajouter</button>
    <a href="manage_services.php" class="btn btn-secondary">Annuler</a>
</form>

<?php include 'inc/footer.php'; ?>