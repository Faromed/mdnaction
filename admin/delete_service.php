<?php
require '../inc/db_config.php';
require 'inc/auth.php';
force_login();
include 'inc/functions.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: manage_services.php');
    exit;
}

$id = $_GET['id'];

if (delete_item($pdo, 'services', $id)) {
    $_SESSION['success_message'] = 'Service supprimé avec succès.';
} else {
    $_SESSION['error_message'] = 'Erreur lors de la suppression du service.';
}

header('Location: manage_services.php');
exit;
?>