<?php
require '../inc/db_config.php';
require 'inc/auth.php';
force_login();
include 'inc/header.php';
include 'inc/functions.php';

$competences = get_all($pdo, 'competences');
?>

<h2>Gestion des Compétences</h2>

<p><a href="add_competence.php" class="btn btn-success">Ajouter une compétence</a></p>

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
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php if ($competences): ?>
            <?php foreach ($competences as $competence): ?>
                <tr>
                    <td><?php echo $competence['id']; ?></td>
                    <td><?php echo htmlspecialchars($competence['titre']); ?></td>
                    <td>
                        <a href="edit_competence.php?id=<?php echo $competence['id']; ?>" class="btn btn-sm btn-primary">Modifier</a>
                        <a href="delete_competence.php?id=<?php echo $competence['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette compétence ?');">Supprimer</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr><td colspan="3">Aucune compétence enregistrée.</td></tr>
        <?php endif; ?>
    </tbody>
</table>

<?php include 'inc/footer.php'; ?>