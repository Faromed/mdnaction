<?php
require '../inc/db_config.php';
require 'inc/auth.php';
force_login();
include 'inc/header.php';
include 'inc/functions.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: manage_formations.php');
    exit;
}

$id = $_GET['id'];
$formation = get_one($pdo, 'formations', $id);

if (!$formation) {
    $_SESSION['error_message'] = 'Formation non trouvée.';
    header('Location: manage_formations.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titre = $_POST['titre'];
    $description = $_POST['description'];
    $duree = $_POST['duree'];
    $outils = $_POST['outils'];
    $competences_requises = $_POST['competences_requises'];
    $prix = $_POST['prix'];
    $lien_paiement = $_POST['lien_paiement'];
    $miniature = $_POST['miniature'];

    try {
        $stmt = $pdo->prepare("UPDATE formations SET titre = ?, description = ?, duree = ?, outils = ?, competences_requises = ?, prix = ?, lien_paiement = ?, miniature = ? WHERE id = ?");
        $stmt->execute([$titre, $description, $duree, $outils, $competences_requises, $prix, $lien_paiement, $miniature, $id]);
        $_SESSION['success_message'] = 'Formation mise à jour avec succès.';
        header('Location: manage_formations.php');
        exit;
    } catch (PDOException $e) {
        $_SESSION['error_message'] = 'Erreur lors de la mise à jour de la formation : ' . $e->getMessage();
        header('Location: manage_formations.php');
        exit;
    }
}
?>

<h2>Modifier la Formation</h2>

<form method="post">
    <input type="hidden" name="id" value="<?php echo $formation['id']; ?>">
    <div class="mb-3">
        <label for="titre" class="form-label">Titre</label>
        <input type="text" class="form-control" id="titre" name="titre" value="<?php echo htmlspecialchars($formation['titre']); ?>" required>
    </div>
    <div class="mb-3">
        <label for="description" class="form-label">Description</label>
        <textarea class="form-control" id="description" name="description" rows="5"><?php echo htmlspecialchars($formation['description']); ?></textarea>
    </div>
    <div class="mb-3">
        <label for="duree" class="form-label">Durée</label>
        <input type="text" class="form-control" id="duree" name="duree" value="<?php echo htmlspecialchars($formation['duree']); ?>">
    </div>
    <div class="mb-3">
        <label for="outils" class="form-label">Outils</label>
        <input type="text" class="form-control" id="outils" name="outils" value="<?php echo htmlspecialchars($formation['outils']); ?>">
    </div>
    <div class="mb-3">
        <label for="competences_requises" class="form-label">Compétences Requises</label>
        <textarea class="form-control" id="competences_requises" name="competences_requises" rows="3"><?php echo htmlspecialchars($formation['competences_requises']); ?></textarea>
    </div>
    <div class="mb-3">
        <label for="prix" class="form-label">Prix</label>
        <input type="text" class="form-control" id="prix" name="prix" value="<?php echo htmlspecialchars($formation['prix']); ?>">
    </div>
    <div class="mb-3">
        <label for="lien_paiement" class="form-label">Lien de Paiement</label>
        <input type="url" class="form-control" id="lien_paiement" name="lien_paiement" value="<?php echo htmlspecialchars($formation['lien_paiement']); ?>">
    </div>
    <div class="mb-3">
        <label for="miniature" class="form-label">Miniature (Nom du fichier)</label>
        <input type="text" class="form-control" id="miniature" name="miniature" value="<?php echo htmlspecialchars($formation['miniature']); ?>">
        <small class="text-muted">Le fichier image doit être téléversé via la page d'upload.</small>
    </div>
    <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
    <a href="manage_formations.php" class="btn btn-secondary">Annuler</a>
</form>

<?php include 'inc/footer.php'; ?>