<?php
require '../inc/db_config.php';
require 'inc/auth.php';
force_login();
include 'inc/header.php';
include 'inc/functions.php';

$services = get_all($pdo, 'services');
?>

<h2>Gestion des Services</h2>

<p><a href="add_service.php" class="btn btn-success">Ajouter un service</a></p>

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
        <?php if ($services): ?>
            <?php foreach ($services as $service): ?>
                <tr>
                    <td><?php echo $service['id']; ?></td>
                    <td><?php echo htmlspecialchars($service['titre']); ?></td>
                    <td><?php echo htmlspecialchars($service['prix']); ?></td>
                    <td><?php echo date('d/m/Y', strtotime($service['date_creation'])); ?></td>
                    <td>
                        <a href="edit_service.php?id=<?php echo $service['id']; ?>" class="btn btn-sm btn-primary">Modifier</a>
                        <a href="delete_service.php?id=<?php echo $service['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce service ?');">Supprimer</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr><td colspan="5">Aucun service enregistré.</td></tr>
        <?php endif; ?>
    </tbody>
</table>

<?php include 'inc/footer.php'; ?>