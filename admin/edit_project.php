<?php
require '../inc/db_config.php';
require 'inc/auth.php';
force_login();
include 'inc/header.php';
include 'inc/functions.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: manage_projects.php');
    exit;
}

$id = $_GET['id'];
$projet = get_one($pdo, 'projets', $id);

if (!$projet) {
    $_SESSION['error_message'] = 'Projet non trouvé.';
    header('Location: manage_projects.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titre = $_POST['titre'];
    $image = $_POST['image'];
    $description = $_POST['description'];
    $technologies_utilisees = $_POST['technologies_utilisees'];
    $lien_live = $_POST['lien_live'];

    try {
        $stmt = $pdo->prepare("UPDATE projets SET titre = ?, image = ?, description = ?, technologies_utilisees = ?, lien_live = ? WHERE id = ?");
        $stmt->execute([$titre, $image, $description, $technologies_utilisees, $lien_live, $id]);
        $_SESSION['success_message'] = 'Projet mis à jour avec succès.';
        header('Location: manage_projects.php');
        exit;
    } catch (PDOException $e) {
        $_SESSION['error_message'] = 'Erreur lors de la mise à jour du projet : ' . $e->getMessage();
        header('Location: manage_projects.php');
        exit;
    }
}
?>

<h2>Modifier le Projet</h2>

<form method="post">
    <input type="hidden" name="id" value="<?php echo $projet['id']; ?>">
    <div class="mb-3">
        <label for="titre" class="form-label">Titre</label>
        <input type="text" class="form-control" id="titre" name="titre" value="<?php echo htmlspecialchars($projet['titre']); ?>" required>
    </div>
    <div class="mb-3">
        <label for="image" class="form-label">Image (Nom du fichier)</label>
        <input type="text" class="form-control" id="image" name="image" value="<?php echo htmlspecialchars($projet['image']); ?>">
        <small class="text-muted">Le fichier image doit être téléversé via la page d'upload.</small>
    </div>
    <div class="mb-3">
        <label for="description" class="form-label">Description</label>
        <textarea class="form-control" id="description" name="description" rows="5"><?php echo htmlspecialchars($projet['description']); ?></textarea>
    </div>
    <div class="mb-3">
        <label for="technologies_utilisees" class="form-label">Technologies Utilisées</label>
        <input type="text" class="form-control" id="technologies_utilisees" name="technologies_utilisees" value="<?php echo htmlspecialchars($projet['technologies_utilisees']); ?>">
    </div>
    <div class="mb-3">
        <label for="lien_live" class="form-label">Lien Live</label>
        <input type="url" class="form-control" id="lien_live" name="lien_live" value="<?php echo htmlspecialchars($projet['lien_live']); ?>">
    </div>
    <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
    <a href="manage_projects.php" class="btn btn-secondary">Annuler</a>
</form>

<?php include 'inc/footer.php'; ?>