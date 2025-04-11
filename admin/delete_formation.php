<?php
require '../inc/db_config.php';
require 'inc/auth.php';
force_login();
include 'inc/functions.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: manage_formations.php');
    exit;
}

$id = $_GET['id'];

if (delete_item($pdo, 'formations', $id)) {
    $_SESSION['success_message'] = 'Formation supprimée avec succès.';
} else {
    $_SESSION['error_message'] = 'Erreur lors de la suppression de la formation.';
}

header('Location: manage_formations.php');
exit;
?>