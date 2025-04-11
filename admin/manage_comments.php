<?php
require '../inc/db_config.php';
require 'inc/auth.php';
force_login();
include 'inc/header.php';
include 'inc/functions.php';

$comments = $pdo->query("SELECT c.*, b.titre AS article_titre FROM commentaires c JOIN blog b ON c.article_id = b.id ORDER BY c.date_creation DESC")->fetchAll(PDO::FETCH_ASSOC);
?>

<h2>Gestion des Commentaires</h2>

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
            <th>Article</th>
            <th>Contenu</th>
            <th>Date de création</th>
            <th>Statut</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php if ($comments): ?>
            <?php foreach ($comments as $comment): ?>
                <tr>
                    <td><?php echo $comment['id']; ?></td>
                    <td><?php echo htmlspecialchars($comment['nom']); ?></td>
                    <td><a href="../blog_post.php?id=<?php echo $comment['article_id']; ?>" target="_blank"><?php echo htmlspecialchars($comment['article_titre']); ?></a></td>
                    <td><?php echo substr(htmlspecialchars($comment['contenu']), 0, 50); ?>...</td>
                    <td><?php echo date('d/m/Y à H:i', strtotime($comment['date_creation'])); ?></td>
                    <td><?php echo $comment['est_approuve'] ? '<span class="badge bg-success">Approuvé</span>' : '<span class="badge bg-warning text-dark">En attente</span>'; ?></td>
                    <td>
                        <a href="edit_comment.php?id=<?php echo $comment['id']; ?>" class="btn btn-sm btn-primary">Modifier</a>
                        <a href="delete_comment.php?id=<?php echo $comment['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce commentaire ?');">Supprimer</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr><td colspan="7">Aucun commentaire.</td></tr>
        <?php endif; ?>
    </tbody>
</table>

<?php include 'inc/footer.php'; ?>