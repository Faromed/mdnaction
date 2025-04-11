<?php
require '../inc/db_config.php';
require 'inc/auth.php';
force_login();
include 'inc/header.php';

$upload_directory = '../img/';
$allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];
$max_file_size = 2 * 1024 * 1024; // 2MB

if (isset($_POST['upload'])) {
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $file_name = $_FILES['image']['name'];
        $file_tmp_name = $_FILES['image']['tmp_name'];
        $file_size = $_FILES['image']['size'];
        $file_extension = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

        if (!in_array($file_extension, $allowed_extensions)) {
            $error_message = 'Seuls les fichiers JPG, JPEG, PNG et GIF sont autorisés.';
        } elseif ($file_size > $max_file_size) {
            $error_message = 'La taille du fichier ne doit pas dépasser 2MB.';
        } else {
            // Générer un nom de fichier unique pour éviter les conflits
            $new_file_name = uniqid() . '.' . $file_extension;
            $destination = $upload_directory . $new_file_name;

            if (move_uploaded_file($file_tmp_name, $destination)) {
                $success_message = 'Image téléversée avec succès sous le nom de : ' . $new_file_name;
            } else {
                $error_message = 'Erreur lors du téléversement du fichier.';
            }
        }
    } else {
        $error_message = 'Veuillez sélectionner un fichier image.';
    }
}

// Lister les images déjà présentes dans le dossier
$images = array_diff(scandir($upload_directory), array('..', '.'));
?>

<h2>Téléverser une Image</h2>

<?php if (isset($success_message)): ?>
    <div class="alert alert-success"><?php echo $success_message; ?></div>
<?php endif; ?>

<?php if (isset($error_message)): ?>
    <div class="alert alert-danger"><?php echo $error_message; ?></div>
<?php endif; ?>

<form method="post" enctype="multipart/form-data">
    <div class="mb-3">
        <label for="image" class="form-label">Sélectionner une image</label>
        <input type="file" class="form-control" id="image" name="image" required>
    </div>
    <button type="submit" class="btn btn-primary" name="upload">Téléverser</button>
</form>

<hr>

<h3>Images Téléversées</h3>

<?php if (!empty($images)): ?>
    <div class="row">
        <?php foreach ($images as $image): ?>
            <div class="col-md-3 mb-3">
                <img src="../img/<?php echo $image; ?>" alt="<?php echo htmlspecialchars($image); ?>" class="img-thumbnail">
                <p class="mt-2">Nom du fichier : <code><?php echo htmlspecialchars($image); ?></code></p>
            </div>
        <?php endforeach; ?>
    </div>
<?php else: ?>
    <p>Aucune image n'a encore été téléversée.</p>
<?php endif; ?>

<?php include 'inc/footer.php'; ?>