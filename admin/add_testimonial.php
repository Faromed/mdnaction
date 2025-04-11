<?php
require '../inc/db_config.php';
require 'inc/auth.php';
force_login();
include 'inc/header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = $_POST['nom'];
    $temoignage = $_POST['temoignage'];
    $profession = $_POST['profession'];

    try {
        $stmt = $pdo->prepare("INSERT INTO temoignages (nom, temoignage, profession) VALUES (?, ?, ?)");
        $stmt->execute([$nom, $temoignage, $profession]);
        $_SESSION['success_message'] = 'Témoignage ajouté avec succès.';
        header('Location: manage_testimonials.php');
        exit;
    } catch (PDOException $e) {
        $_SESSION['error_message'] = 'Erreur lors de l\'ajout du témoignage : ' . $e->getMessage();
        header('Location: manage_testimonials.php');
        exit;
    }
}
?>

<h2>Ajouter un Témoignage</h2>

<form method="post">
    <div class="mb-3">
        <label for="nom" class="form-label">Nom</label>
        <input type="text" class="form-control" id="nom" name="nom" required>
    </div>
    <div class="mb-3">
        <label for="temoignage" class="form-label">Témoignage</label>
        <textarea class="form-control" id="temoignage" name="temoignage" rows="5" required></textarea>
    </div>
    <div class="mb-3">
        <label for="profession" class="form-label">Profession</label>
        <input type="text" class="form-control" id="profession" name="profession">
    </div>
    <button type="submit" class="btn btn-primary">Ajouter</button>
    <a href="manage_testimonials.php" class="btn btn-secondary">Annuler</a>
</form>

<?php include 'inc/footer.php'; ?>