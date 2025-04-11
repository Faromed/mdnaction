<?php
require '../inc/db_config.php';
require 'inc/auth.php';
force_login();
include 'inc/header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titre = $_POST['titre'];
    $description = $_POST['description'];
    $duree = $_POST['duree'];
    $outils = $_POST['outils'];
    $competences_requises = $_POST['competences_requises'];
    $prix = $_POST['prix'];
    $lien_paiement = $_POST['lien_paiement'];
    $miniature = $_POST['miniature']; // Vous devrez gérer l'upload d'image séparément

    try {
        $stmt = $pdo->prepare("INSERT INTO formations (titre, description, duree, outils, competences_requises, prix, lien_paiement, miniature) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$titre, $description, $duree, $outils, $competences_requises, $prix, $lien_paiement, $miniature]);
        $_SESSION['success_message'] = 'Formation ajoutée avec succès.';
        header('Location: manage_formations.php');
        exit;
    } catch (PDOException $e) {
        $_SESSION['error_message'] = 'Erreur lors de l\'ajout de la formation : ' . $e->getMessage();
        header('Location: manage_formations.php');
        exit;
    }
}
?>

<h2>Ajouter une Formation</h2>

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
        <label for="duree" class="form-label">Durée</label>
        <input type="text" class="form-control" id="duree" name="duree">
    </div>
    <div class="mb-3">
        <label for="outils" class="form-label">Outils</label>
        <input type="text" class="form-control" id="outils" name="outils">
    </div>
    <div class="mb-3">
        <label for="competences_requises" class="form-label">Compétences Requises</label>
        <textarea class="form-control" id="competences_requises" name="competences_requises" rows="3"></textarea>
    </div>
    <div class="mb-3">
        <label for="prix" class="form-label">Prix</label>
        <input type="text" class="form-control" id="prix" name="prix">
    </div>
    <div class="mb-3">
        <label for="lien_paiement" class="form-label">Lien de Paiement</label>
        <input type="url" class="form-control" id="lien_paiement" name="lien_paiement">
    </div>
    <div class="mb-3">
        <label for="miniature" class="form-label">Miniature (Nom du fichier)</label>
        <input type="text" class="form-control" id="miniature" name="miniature">
        <small class="text-muted">Le fichier image doit être téléversé via la page d'upload.</small>
    </div>
    <button type="submit" class="btn btn-primary">Ajouter</button>
    <a href="manage_formations.php" class="btn btn-secondary">Annuler</a>
</form>

<?php include 'inc/footer.php'; ?>