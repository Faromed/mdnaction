<?php
require '../inc/db_config.php';
require 'inc/auth.php';
force_login();
include 'inc/header.php';
include 'inc/functions.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: manage_testimonials.php');
    exit;
}

$id = $_GET['id'];
$testimonial = get_one($pdo, 'temoignages', $id);

if (!$testimonial) {
    $_SESSION['error_message'] = 'Témoignage non trouvé.';
    header('Location: manage_testimonials.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = $_POST['nom'];
    $temoignage = $_POST['temoignage'];
    $profession = $_POST['profession'];

    try {
        $stmt = $pdo->prepare("UPDATE temoignages SET nom = ?, temoignage = ?, profession = ? WHERE id = ?");
        $stmt->execute([$nom, $temoignage, $profession, $id]);
        $_SESSION['success_message'] = 'Témoignage mis à jour avec succès.';
        header('Location: manage_testimonials.php');
        exit;
    } catch (PDOException $e) {
        $_SESSION['error_message'] = 'Erreur lors de la mise à jour du témoignage : ' . $e->getMessage();
        header('Location: manage_testimonials.php');
        exit;
    }
}
?>

<h2>Modifier le Témoignage</h2>

<form method="post">
    <input type="hidden" name="id" value="<?php echo $testimonial['id']; ?>">
    <div class="mb-3">
        <label for="nom" class="form-label">Nom</label>
        <input type="text" class="form-control" id="nom" name="nom" value="<?php echo htmlspecialchars($testimonial['nom']); ?>" required>
    </div>
    <div class="mb-3">
        <label for="temoignage" class="form-label">Témoignage</label>
        <textarea class="form-control" id="temoignage" name="temoignage" rows="5" required><?php echo htmlspecialchars($testimonial['temoignage']); ?></textarea>
    </div>
    <div class="mb-3">
        <label for="profession" class="form-label">Profession</label>
        <input type="text" class="form-control" id="profession" name="profession" value="<?php echo htmlspecialchars($testimonial['profession']); ?>">
    </div>
    <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
    <a href="manage_testimonials.php" class="btn btn-secondary">Annuler</a>
</form>

<?php include 'inc/footer.php'; ?>