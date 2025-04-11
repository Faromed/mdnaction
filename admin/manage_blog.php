<?php
require '../inc/db_config.php';
require 'inc/auth.php';
force_login();
include 'inc/header.php';
include 'inc/functions.php';

$blog_posts = get_all($pdo, 'blog');
?>

<h2>Gestion des Articles de Blog</h2>

<p><a href="add_blog.php" class="btn btn-success">Ajouter un article</a></p>

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
            <th>Auteur</th>
            <th>Date de publication</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php if ($blog_posts): ?>
            <?php foreach ($blog_posts as $post): ?>
                <tr>
                    <td><?php echo $post['id']; ?></td>
                    <td><?php echo htmlspecialchars($post['titre']); ?></td>
                    <td><?php echo htmlspecialchars($post['auteur']); ?></td>
                    <td><?php echo date('d/m/Y', strtotime($post['date_publication'])); ?></td>
                    <td>
                        <a href="edit_blog.php?id=<?php echo $post['id']; ?>" class="btn btn-sm btn-primary">Modifier</a>
                        <a href="delete_blog.php?id=<?php echo $post['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet article de blog ?');">Supprimer</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr><td colspan="4">Aucun article de blog enregistré.</td></tr>
        <?php endif; ?>
    </tbody>
</table>

<?php include 'inc/footer.php'; ?>