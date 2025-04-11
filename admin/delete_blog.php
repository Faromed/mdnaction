<?php
require '../inc/db_config.php';
require 'inc/auth.php';
force_login();
include 'inc/functions.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: manage_blog.php');
    exit;
}

$id = $_GET['id'];

if (delete_item($pdo, 'blog', $id)) {
    $_SESSION['success_message'] = 'Article de blog supprimé avec succès.';
} else {
    $_SESSION['error_message'] = 'Erreur lors de la suppression de l\'article de blog.';
}

header('Location: manage_blog.php');
exit;
?>