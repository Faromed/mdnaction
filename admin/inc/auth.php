<?php
session_start();

function is_logged_in() {
    return isset($_SESSION['admin_id']);
}

function force_login() {
    if (!is_logged_in()) {
        header('Location: index.php');
        exit;
    }
}

function login($pdo, $username, $password) {
    $stmt = $pdo->prepare("SELECT id, password FROM admins WHERE username = ?");
    $stmt->execute([$username]);
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($admin && password_verify($password, $admin['password'])) {
        $_SESSION['admin_id'] = $admin['id'];
        return true;
    } else {
        return false;
    }
}

function logout() {
    session_destroy();
}
?>