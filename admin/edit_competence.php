<?php
require '../inc/db_config.php';
require 'inc/auth.php';
force_login();
include 'inc/header.php';
include 'inc/functions.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: manage_competences.php');
    exit;
}

$id = $_GET['id'];
$competence = get_one($pdo, 'competences', $id);

if (!$competence) {
    $_SESSION['error_message'] = 'Compétence non trouvée.';
    header('Location: manage_competences.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titre = $_POST['titre'];
    $description = $_POST['description'];
    $icone = $_POST['icone'];

    try {
        $stmt = $pdo->prepare("UPDATE competences SET titre = ?, description = ?, icone = ? WHERE id = ?");
        $stmt->execute([$titre, $description, $icone, $id]);
        $_SESSION['success_message'] = 'Compétence mise à jour avec succès.';
        header('Location: manage_competences.php');
        exit;
    } catch (PDOException $e) {
        $_SESSION['error_message'] = 'Erreur lors de la mise à jour de la compétence : ' . $e->getMessage();
        header('Location: manage_competences.php');
        exit;
    }
}
?>

<h2>Modifier la Compétence</h2>

<form method="post">
    <input type="hidden" name="id" value="<?php echo $competence['id']; ?>">
    <div class="mb-3">
        <label for="titre" class="form-label">Titre</label>
        <input type="text" class="form-control" id="titre" name="titre" value="<?php echo htmlspecialchars($competence['titre']); ?>" required>
    </div>
    <div class="mb-3">
        <label for="description" class="form-label">Description</label>
        <textarea class="form-control" id="description" name="description" rows="3"><?php echo htmlspecialchars($competence['description']); ?></textarea>
    </div>
    <div class="mb-3">
        <label for="icone" class="form-label">Icône (Nom du fichier)</label>
        <input type="text" class="form-control" id="icone" name="icone" value="<?php echo htmlspecialchars($competence['icone']); ?>">
        <small class="text-muted">Le fichier image doit être téléversé via la page d'upload.</small>
    </div>
    <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
    <a href="manage_competences.php" class="btn btn-secondary">Annuler</a>
</form>

<?php include 'inc/footer.php'; ?>