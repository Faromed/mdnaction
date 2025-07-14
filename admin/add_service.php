<?php
require '../inc/db_config.php';
require 'inc/auth.php';
force_login();
include 'inc/header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titre = $_POST['titre'];
    $description = $_POST['description'];
    $prix = $_POST['prix'];
    $image_ou_icone = '';

    if (isset($_FILES['image_ou_icone']) && $_FILES['image_ou_icone']['error'] == 0) {
        $target_dir = "../img/";
        $target_file = $target_dir . basename($_FILES["image_ou_icone"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

        // Allow certain file formats
        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
        && $imageFileType != "gif" ) {
            $_SESSION['error_message'] = "Désolé, seuls les fichiers JPG, JPEG, PNG et GIF sont autorisés.";
            header('Location: add_service.php');
            exit;
        }

        if (move_uploaded_file($_FILES["image_ou_icone"]["tmp_name"], $target_file)) {
            $image_ou_icone = basename($_FILES["image_ou_icone"]["name"]);
        } else {
            $_SESSION['error_message'] = "Désolé, une erreur s'est produite lors du téléchargement de votre fichier.";
            header('Location: add_service.php');
            exit;
        }
    }

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

<form method="post" enctype="multipart/form-data">
    <div class="mb-3">
        <label for="titre" class="form-label">Titre</label>
        <input type="text" class="form-control" id="titre" name="titre" required>
    </div>
    <div class="mb-3">
        <label for="description" class="form-label">Description</label>
        <textarea class="form-control" id="description" name="description" rows="5"></textarea>
    </div>
    <div class="mb-3">
        <label for="image_ou_icone" class="form-label">Image</label>
        <input type="file" class="form-control" id="image_ou_icone" name="image_ou_icone">
    </div>
    <div class="mb-3">
        <label for="prix" class="form-label">Prix (Optionnel)</label>
        <input type="text" class="form-control" id="prix" name="prix">
    </div>
    <button type="submit" class="btn btn-primary">Ajouter</button>
    <a href="manage_services.php" class="btn btn-secondary">Annuler</a>
</form>

<?php include 'inc/footer.php'; ?>