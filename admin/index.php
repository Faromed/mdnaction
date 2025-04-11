<?php
require '../inc/db_config.php';
require 'inc/auth.php';
if (is_logged_in()) {
    header('Location: dashboard.php');
    exit;
}
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    if (login($pdo, $username, $password)) {
        header('Location: dashboard.php');
        exit;
    } else {
        $error = 'Nom d\'utilisateur ou mot de passe incorrect.';
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Connexion | MDNAction</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" integrity="sha512-9usAa10IRO0HhonpyAIVpjrylPvoDwiPUiKdWk5t3PyolY1cOd4DSE0Ga+ri4AuTroPR5aQvXU9xC6qOPnzFeg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body class="bg-light">
    <div class="container-fluid">
        <div class="row vh-100 align-items-center justify-content-center" style="background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);">
            <div class="col-md-5 col-lg-4">
                <div class="card shadow-lg border-0 rounded-lg">
                    <div class="card-header bg-white text-center p-4 border-bottom-0">
                        <h1 class="display-6 fw-bold text-success mb-0">
                            <i class="fas fa-gauge-high me-2"></i>MDNAction
                        </h1>
                        <p class="text-muted mt-2">Panneau d'administration</p>
                    </div>
                    
                    <div class="card-body px-4 py-4">
                        <?php if ($error): ?>
                            <div class="alert alert-danger d-flex align-items-center" role="alert">
                                <i class="fas fa-exclamation-circle me-2"></i>
                                <div><?php echo $error; ?></div>
                            </div>
                        <?php endif; ?>
                        
                        <form method="post" id="loginForm">
                            <div class="mb-4">
                                <label for="username" class="form-label fw-medium">
                                    <i class="fas fa-user text-success me-2"></i>Nom d'utilisateur
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light">
                                        <i class="fas fa-user-shield text-secondary"></i>
                                    </span>
                                    <input type="text" class="form-control form-control-lg bg-light" 
                                           id="username" name="username" placeholder="Entrez votre identifiant" required>
                                </div>
                            </div>
                            <div class="mb-4">
                                <label for="password" class="form-label fw-medium">
                                    <i class="fas fa-lock text-success me-2"></i>Mot de passe
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light">
                                        <i class="fas fa-key text-secondary"></i>
                                    </span>
                                    <input type="password" class="form-control form-control-lg bg-light" 
                                           id="password" name="password" placeholder="Entrez votre mot de passe" required>
                                    <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                        <i class="fas fa-eye" id="toggleIcon"></i>
                                    </button>
                                </div>
                            </div>
                            
                            <div class="form-check mb-4">
                                <input class="form-check-input" type="checkbox" id="rememberMe">
                                <label class="form-check-label text-muted" for="rememberMe">
                                    Se souvenir de moi
                                </label>
                            </div>
                            
                            <button type="submit" class="btn btn-success btn-lg w-100 mb-3">
                                <i class="fas fa-sign-in-alt me-2"></i>Se connecter
                            </button>
                        </form>
                    </div>
                    
                    <div class="card-footer bg-white text-center p-3 border-top-0">
                        <div class="small text-muted">
                            &copy; <?php echo date('Y'); ?> MDNAction - Tous droits réservés
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Effet de flottement sur la carte de connexion
            const loginCard = document.querySelector('.card');
            loginCard.addEventListener('mousemove', function(e) {
                const rect = loginCard.getBoundingClientRect();
                const x = e.clientX - rect.left;
                const y = e.clientY - rect.top;
                
                const centerX = rect.width / 2;
                const centerY = rect.height / 2;
                
                const moveX = (x - centerX) / 20;
                const moveY = (y - centerY) / 20;
                
                loginCard.style.transform = `perspective(1000px) rotateY(${moveX}deg) rotateX(${-moveY}deg)`;
            });
            
            loginCard.addEventListener('mouseleave', function() {
                loginCard.style.transform = 'perspective(1000px) rotateY(0deg) rotateX(0deg)';
                loginCard.style.transition = 'transform 0.5s ease';
            });
            
            // Fonctionnalité pour afficher/masquer le mot de passe
            const togglePassword = document.getElementById('togglePassword');
            const password = document.getElementById('password');
            const toggleIcon = document.getElementById('toggleIcon');
            
            togglePassword.addEventListener('click', function() {
                const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
                password.setAttribute('type', type);
                
                // Changer l'icône
                if (type === 'password') {
                    toggleIcon.classList.remove('fa-eye-slash');
                    toggleIcon.classList.add('fa-eye');
                } else {
                    toggleIcon.classList.remove('fa-eye');
                    toggleIcon.classList.add('fa-eye-slash');
                }
            });
            
            // Animation des labels de formulaire
            const inputs = document.querySelectorAll('.form-control');
            inputs.forEach(input => {
                input.addEventListener('focus', function() {
                    this.parentElement.parentElement.querySelector('.form-label').classList.add('text-success');
                });
                
                input.addEventListener('blur', function() {
                    this.parentElement.parentElement.querySelector('.form-label').classList.remove('text-success');
                });
            });
        });
    </script>
</body>
</html>