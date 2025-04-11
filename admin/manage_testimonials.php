<?php
require '../inc/db_config.php';
require 'inc/auth.php';
force_login();
include 'inc/header.php';
include 'inc/functions.php';

$testimonials = get_all($pdo, 'temoignages');
?>

<h2>Gestion des Témoignages</h2>

<p><a href="add_testimonial.php" class="btn btn-success">Ajouter un témoignage</a></p>

<?php if (isset($_SESSION['success_message'])): ?>
    <div class="alert alert-success"><?php echo $_SESSION['success_message']; unset($_SESSION['success_message']); ?></div>
<?php endif; ?>

<?php if (isset($_SESSION['error_message'])): ?>
    <div class="alert alert-danger"><?php echo $_SESSION['error_message']; unset($_SESSION['error_message']); ?></div>
<?php endif; ?>

<table class="table table-striped">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nom</th>
            <th>Profession</th>
            <th>Date de création</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php if ($testimonials): ?>
            <?php foreach ($testimonials as $testimonial): ?>
                <tr>
                    <td><?php echo $testimonial['id']; ?></td>
                    <td><?php echo htmlspecialchars($testimonial['nom']); ?></td>
                    <td><?php echo htmlspecialchars($testimonial['profession']); ?></td>
                    <td><?php echo date('d/m/Y', strtotime($testimonial['date_creation'])); ?></td>
                    <td>
                        <a href="edit_testimonial.php?id=<?php echo $testimonial['id']; ?>" class="btn btn-sm btn-primary">Modifier</a>
                        <a href="delete_testimonial.php?id=<?php echo $testimonial['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce témoignage ?');">Supprimer</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr><td colspan="4">Aucun témoignage enregistré.</td></tr>
        <?php endif; ?>
    </tbody>
</table>

<?php include 'inc/footer.php'; ?>