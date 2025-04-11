<?php
require '../inc/db_config.php';
require 'inc/auth.php';
force_login();
include 'inc/header.php';
include 'inc/functions.php';

$formations = get_all($pdo, 'formations');
?>

<h2>Gestion des Formations</h2>

<p><a href="add_formation.php" class="btn btn-success">Ajouter une formation</a></p>

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
            <th>Titre</th>
            <th>Prix</th>
            <th>Date de création</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php if ($formations): ?>
            <?php foreach ($formations as $formation): ?>
                <tr>
                    <td><?php echo $formation['id']; ?></td>
                    <td><?php echo htmlspecialchars($formation['titre']); ?></td>
                    <td><?php echo htmlspecialchars($formation['prix']); ?></td>
                    <td><?php echo date('d/m/Y', strtotime($formation['date_creation'])); ?></td>
                    <td>
                        <a href="edit_formation.php?id=<?php echo $formation['id']; ?>" class="btn btn-sm btn-primary">Modifier</a>
                        <a href="delete_formation.php?id=<?php echo $formation['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette formation ?');">Supprimer</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr><td colspan="5">Aucune formation enregistrée.</td></tr>
        <?php endif; ?>
    </tbody>
</table>

<?php include 'inc/footer.php'; ?>