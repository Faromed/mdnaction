<?php
require '../inc/db_config.php';
require 'inc/auth.php';
force_login();
include 'inc/header.php';
include 'inc/functions.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: manage_services.php');
    exit;
}

$id = $_GET['id'];
$service = get_one($pdo, 'services', $id);

if (!$service) {
    $_SESSION['error_message'] = 'Service non trouvé.';
    header('Location: manage_services.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titre = $_POST['titre'];
    $description = $_POST['description'];
    $image_ou_icone = $_POST['image_ou_icone'];
    $prix = $_POST['prix'];

    try {
        $stmt = $pdo->prepare("UPDATE services SET titre = ?, description = ?, image_ou_icone = ?, prix = ? WHERE id = ?");
        $stmt->execute([$titre, $description, $image_ou_icone, $prix, $id]);
        $_SESSION['success_message'] = 'Service mis à jour avec succès.';
        header('Location: manage_services.php');
        exit;
    } catch (PDOException $e) {
        $_SESSION['error_message'] = 'Erreur lors de la mise à jour du service : ' . $e->getMessage();
        header('Location: manage_services.php');
        exit;
    }
}
?>

<h2>Modifier le Service</h2>

<form method="post">
    <input type="hidden" name="id" value="<?php echo $service['id']; ?>">
    <div class="mb-3">
        <label for="titre" class="form-label">Titre</label>
        <input type="text" class="form-control" id="titre" name="titre" value="<?php echo htmlspecialchars($service['titre']); ?>" required>
    </div>
    <div class="mb-3">
        <label for="description" class="form-label">Description</label>
        <textarea class="form-control" id="description" name="description" rows="5"><?php echo htmlspecialchars($service['description']); ?></textarea>
    </div>
    <div class="mb-3">
        <label for="image_ou_icone" class="form-label">Image ou Icône (Nom du fichier)</label>
        <input type="text" class="form-control" id="image_ou_icone" name="image_ou_icone" value="<?php echo htmlspecialchars($service['image_ou_icone']); ?>">
        <small class="text-muted">Le fichier image doit être téléversé via la page d'upload.</small>
    </div>
    <div class="mb-3">
        <label for="prix" class="form-label">Prix (Optionnel)</label>
        <input type="text" class="form-control" id="prix" name="prix" value="<?php echo htmlspecialchars($service['prix']); ?>">
    </div>
    <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
    <a href="manage_services.php" class="btn btn-secondary">Annuler</a>
</form>

<?php include 'inc/footer.php'; ?>