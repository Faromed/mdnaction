<?php
require '../inc/db_config.php';
require 'inc/auth.php';
force_login();
include 'inc/header.php';
include 'inc/functions.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: manage_comments.php');
    exit;
}

$id = $_GET['id'];
$comment = get_one($pdo, 'commentaires', $id);

if (!$comment) {
    $_SESSION['error_message'] = 'Commentaire non trouvé.';
    header('Location: manage_comments.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $est_approuve = isset($_POST['est_approuve']) ? 1 : 0;

    try {
        $stmt = $pdo->prepare("UPDATE commentaires SET est_approuve = ? WHERE id = ?");
        $stmt->execute([$est_approuve, $id]);
        $_SESSION['success_message'] = 'Commentaire mis à jour avec succès.';
        header('Location: manage_comments.php');
        exit;
    } catch (PDOException $e) {
        $_SESSION['error_message'] = 'Erreur lors de la mise à jour du commentaire : ' . $e->getMessage();
        header('Location: manage_comments.php');
        exit;
    }
}
?>

<h2>Modifier le Commentaire</h2>

<form method="post">
    <input type="hidden" name="id" value="<?php echo $comment['id']; ?>">
    <div class="mb-3">
        <label for="nom" class="form-label">Nom</label>
        <input type="text" class="form-control" id="nom" name="nom" value="<?php echo htmlspecialchars($comment['nom']); ?>" readonly>
    </div>
    <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($comment['email']); ?>" readonly>
    </div>
    <div class="mb-3">
        <label for="contenu" class="form-label">Contenu</label>
        <textarea class="form-control" id="contenu" name="contenu" rows="5" readonly><?php echo htmlspecialchars($comment['contenu']); ?></textarea>
    </div>
    <div class="form-check mb-3">
        <input type="checkbox" class="form-check-input" id="est_approuve" name="est_approuve" <?php if ($comment['est_approuve']) echo 'checked'; ?>>
        <label class="form-check-label" for="est_approuve">Approuver le commentaire</label>
    </div>
    <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
    <a href="manage_comments.php" class="btn btn-secondary">Annuler</a>
</form>

<?php include 'inc/footer.php'; ?>