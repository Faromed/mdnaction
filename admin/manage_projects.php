<?php
require '../inc/db_config.php';
require 'inc/auth.php';
force_login();
include 'inc/header.php';
include 'inc/functions.php';

$projets = get_all($pdo, 'projets');
?>

<h2>Gestion des Projets</h2>

<p><a href="add_project.php" class="btn btn-success">Ajouter un projet</a></p>

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
            <th>Date de création</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php if ($projets): ?>
            <?php foreach ($projets as $projet): ?>
                <tr>
                    <td><?php echo $projet['id']; ?></td>
                    <td><?php echo htmlspecialchars($projet['titre']); ?></td>
                    <td><?php echo date('d/m/Y', strtotime($projet['date_creation'])); ?></td>
                    <td>
                        <a href="edit_project.php?id=<?php echo $projet['id']; ?>" class="btn btn-sm btn-primary">Modifier</a>
                        <a href="delete_project.php?id=<?php echo $projet['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce projet ?');">Supprimer</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr><td colspan="3">Aucun projet enregistré.</td></tr>
        <?php endif; ?>
    </tbody>
</table>

<?php include 'inc/footer.php'; ?>