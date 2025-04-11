<?php
require '../inc/db_config.php';
require 'inc/auth.php';
force_login();
include 'inc/header.php';
include 'inc/functions.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: manage_blog.php');
    exit;
}

$id = $_GET['id'];
$post = get_one($pdo, 'blog', $id);

if (!$post) {
    $_SESSION['error_message'] = 'Article de blog non trouvé.';
    header('Location: manage_blog.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titre = $_POST['titre'];
    $contenu = $_POST['contenu'];
    $auteur = $_POST['auteur'];
    $image = $_POST['image'];

    try {
        $stmt = $pdo->prepare("UPDATE blog SET titre = ?, contenu = ?, auteur = ?, image = ? WHERE id = ?");
        $stmt->execute([$titre, $contenu, $auteur, $image, $id]);
        $_SESSION['success_message'] = 'Article de blog mis à jour avec succès.';
        header('Location: manage_blog.php');
        exit;
    } catch (PDOException $e) {
        $_SESSION['error_message'] = 'Erreur lors de la mise à jour de l\'article de blog : ' . $e->getMessage();
        header('Location: manage_blog.php');
        exit;
    }
}
?>

<h2>Modifier l'Article de Blog</h2>

<form method="post">
    <input type="hidden" name="id" value="<?php echo $post['id']; ?>">
    <div class="mb-3">
        <label for="titre" class="form-label">Titre</label>
        <input type="text" class="form-control" id="titre" name="titre" value="<?php echo htmlspecialchars($post['titre']); ?>" required>
    </div>
    <div class="mb-3">
        <label for="contenu" class="form-label">Contenu</label>
        <textarea class="form-control" id="contenu" name="contenu" rows="10"><?php echo htmlspecialchars($post['contenu']); ?></textarea>
    </div>
    <div class="mb-3">
        <label for="auteur" class="form-label">Auteur</label>
        <input type="text" class="form-control" id="auteur" name="auteur" value="<?php echo htmlspecialchars($post['auteur']); ?>">
    </div>
    <div class="mb-3">
        <label for="image" class="form-label">Image (Nom du fichier)</label>
        <input type="text" class="form-control" id="image" name="image" value="<?php echo htmlspecialchars($post['image']); ?>">
        <small class="text-muted">Le fichier image doit être téléversé via la page d'upload.</small>
    </div>
    <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
    <a href="manage_blog.php" class="btn btn-secondary">Annuler</a>
</form>

<?php include 'inc/footer.php'; ?>