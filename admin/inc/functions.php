<?php
function get_all($pdo, $table) {
    $stmt = $pdo->query("SELECT * FROM $table ORDER BY id DESC");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function get_one($pdo, $table, $id) {
    $stmt = $pdo->prepare("SELECT * FROM $table WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function delete_item($pdo, $table, $id) {
    $stmt = $pdo->prepare("DELETE FROM $table WHERE id = ?");
    return $stmt->execute([$id]);
}

// Ajoutez ici d'autres fonctions utilitaires pour l'admin
?>