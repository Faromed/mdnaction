<?php include 'inc/header.php'; ?>
<?php require 'inc/db_config.php'; 

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $pdo->prepare("SELECT * FROM blog WHERE id = ?");
    $stmt->execute([$id]);
    $post = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($post): ?>

<div class="container py-5">
    <div class="row">
        <!-- Contenu principal -->
        <div class="col-lg-8">
            <!-- Article header -->
            <div class="mb-4">
                <h1 class="fw-bold"><?php echo $post['titre']; ?></h1>
                <div class="d-flex align-items-center text-muted mb-4">
                    <i class="far fa-calendar-alt me-2"></i>
                    <span>Publié le <?php echo date('d/m/Y', strtotime($post['date_publication'])); ?></span>
                    <span class="mx-3">|</span>
                    <i class="far fa-comments me-2"></i>
                    <?php
                    $stmt_count = $pdo->prepare("SELECT COUNT(*) FROM commentaires WHERE article_id = ? AND est_approuve = TRUE");
                    $stmt_count->execute([$id]);
                    $comment_count = $stmt_count->fetchColumn();
                    echo "<span>{$comment_count} commentaire" . ($comment_count > 1 ? "s" : "") . "</span>";
                    ?>
                </div>
            </div>

            <?php if ($post['image']): ?>
                <img src="img/<?php echo $post['image']; ?>" class="img-fluid rounded shadow mb-4" alt="<?php echo $post['titre']; ?>">
            <?php endif; ?>

            <!-- Article content -->
            <div class="card border-0 shadow-sm p-4 mb-5">
                <div class="card-body">
                    <div class="article-content">
                        <?php echo $post['contenu']; ?>
                    </div>
                </div>
            </div>

            <!-- Commentaires section -->
            <div class="card border-0 shadow-sm mb-5">
                <div class="card-header bg-white py-3">
                    <h3 class="mb-0 fw-bold">
                        <i class="fas fa-comments text-success me-2"></i>Commentaires
                    </h3>
                </div>
                <div class="card-body p-4">
                    <?php
                    $stmt_comments = $pdo->prepare("SELECT * FROM commentaires WHERE article_id = ? AND est_approuve = TRUE ORDER BY date_creation DESC");
                    $stmt_comments->execute([$id]);
                    $comments = $stmt_comments->fetchAll(PDO::FETCH_ASSOC);
                    
                    if ($comments):
                        foreach ($comments as $comment):
                    ?>
                        <div class="d-flex mb-4">
                            <div class="flex-shrink-0">
                                <div class="bg-light rounded-circle d-flex justify-content-center align-items-center" style="width: 50px; height: 50px;">
                                    <i class="fas fa-user text-success"></i>
                                </div>
                            </div>
                            <div class="ms-3 flex-grow-1">
                                <div class="d-flex align-items-center mb-1">
                                    <h6 class="fw-bold mb-0"><?php echo htmlspecialchars($comment['nom']); ?></h6>
                                    <small class="text-muted ms-2">• <?php echo date('d/m/Y à H:i', strtotime($comment['date_creation'])); ?></small>
                                </div>
                                <p class="mb-0"><?php echo htmlspecialchars($comment['contenu']); ?></p>
                            </div>
                        </div>
                    <?php
                        endforeach;
                    else:
                        echo '<div class="text-center py-4 text-muted"><i class="far fa-comment-dots fa-3x mb-3"></i><p>Pas de commentaires pour cet article.<br>Soyez le premier à commenter !</p></div>';
                    endif;
                    ?>
                </div>
            </div>

            <!-- Formulaire de commentaire -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h4 class="mb-0 fw-bold">
                        <i class="fas fa-pen-alt text-success me-2"></i>Laisser un commentaire
                    </h4>
                </div>
                <div class="card-body p-4">
                    <form method="post" action="blog_post.php?id=<?php echo $id; ?>" class="comment-form">
                        <?php
                        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_comment'])) {
                            $nom = $_POST['nom'];
                            $email = $_POST['email'];
                            $contenu = $_POST['contenu'];
                            
                            if (!empty($nom) && !empty($email) && !empty($contenu)) {
                                $insert_stmt = $pdo->prepare("INSERT INTO commentaires (article_id, nom, email, contenu) VALUES (?, ?, ?, ?)");
                                $insert_stmt->execute([$id, $nom, $email, $contenu]);
                                echo '<div class="alert alert-success">
                                    <i class="fas fa-check-circle me-2"></i>Votre commentaire a été soumis et sera examiné par un modérateur.
                                </div>';
                            } else {
                                echo '<div class="alert alert-danger">
                                    <i class="fas fa-exclamation-circle me-2"></i>Veuillez remplir tous les champs du formulaire.
                                </div>';
                            }
                        }
                        ?>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" id="nom" name="nom" placeholder="Votre nom" required>
                                    <label for="nom"><i class="fas fa-user me-2"></i>Nom</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating mb-3">
                                    <input type="email" class="form-control" id="email" name="email" placeholder="Votre email" required>
                                    <label for="email"><i class="fas fa-envelope me-2"></i>Adresse Email</label>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-floating mb-3">
                                    <textarea class="form-control" id="contenu" name="contenu" placeholder="Votre commentaire" style="height: 150px" required></textarea>
                                    <label for="contenu"><i class="fas fa-comment me-2"></i>Commentaire</label>
                                </div>
                            </div>
                            <div class="col-12">
                                <button type="submit" name="submit_comment" class="btn btn-success">
                                    <i class="fas fa-paper-plane me-2"></i>Soumettre le commentaire
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4 mt-5 mt-lg-0">
            <!-- Articles récents -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h4 class="mb-0 fw-bold"><i class="fas fa-newspaper text-success me-2"></i>Articles récents</h4>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        <?php
                        $recent_posts = $pdo->query("SELECT id, titre, date_publication FROM blog WHERE id != {$id} ORDER BY date_publication DESC LIMIT 5");
                        while ($recent_post = $recent_posts->fetch(PDO::FETCH_ASSOC)):
                        ?>
                        <li class="mb-3 pb-3 border-bottom">
                            <a href="blog_post.php?id=<?php echo $recent_post['id']; ?>" class="text-decoration-none d-flex align-items-center">
                                <i class="fas fa-angle-right text-success me-2"></i>
                                <div>
                                    <h6 class="mb-0 text-body"><?php echo $recent_post['titre']; ?></h6>
                                    <small class="text-muted"><?php echo date('d/m/Y', strtotime($recent_post['date_publication'])); ?></small>
                                </div>
                            </a>
                        </li>
                        <?php endwhile; ?>
                    </ul>
                </div>
                <div class="card-footer bg-white py-3">
                    <a href="blog.php" class="btn btn-outline-success btn-sm w-100">
                        <i class="fas fa-arrow-right me-2"></i>Tous les articles
                    </a>
                </div>
            </div>

            <!-- CTA Card -->
            <div class="card border-0 shadow-sm bg-success text-white">
                <div class="card-body text-center p-4">
                    <i class="fas fa-envelope-open-text fa-3x mb-3"></i>
                    <h4 class="fw-bold mb-3">Besoin d'aide pour votre projet ?</h4>
                    <p>Je suis disponible pour vous accompagner dans vos projets web et logiciels.</p>
                    <a href="contact.php" class="btn btn-light">
                        <i class="fas fa-envelope me-2"></i>Contactez-moi
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
    else:
        echo '<div class="container py-5"><div class="alert alert-warning">
            <i class="fas fa-exclamation-triangle me-2"></i>Article de blog non trouvé.
        </div></div>';
    endif;
} else {
    echo '<div class="container py-5"><div class="alert alert-danger">
        <i class="fas fa-exclamation-circle me-2"></i>ID d\'article de blog invalide.
    </div></div>';
}
?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Style pour le contenu de l'article
    const articleContent = document.querySelector('.article-content');
    if (articleContent) {
        // Améliorer l'apparence des éléments dans le contenu
        const headings = articleContent.querySelectorAll('h1, h2, h3, h4, h5, h6');
        headings.forEach(heading => {
            heading.classList.add('mt-4', 'mb-3', 'fw-bold');
        });
        
        const images = articleContent.querySelectorAll('img');
        images.forEach(img => {
            img.classList.add('img-fluid', 'rounded', 'my-3');
        });
        
        const paragraphs = articleContent.querySelectorAll('p');
        paragraphs.forEach(p => {
            p.classList.add('mb-3', 'text-body');
        });
        
        const lists = articleContent.querySelectorAll('ul, ol');
        lists.forEach(list => {
            list.classList.add('my-3');
        });
    }
});
</script>

<?php include 'inc/footer.php'; ?>