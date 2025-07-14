<?php

/**
 * Récupère tous les enregistrements d'une table.
 * @param PDO $pdo L'objet PDO pour la connexion à la base de données.
 * @param string $table Le nom de la table.
 * @return array Les enregistrements.
 */
function get_all(PDO $pdo, string $table): array {
    // Attention: cette fonction peut retourner un grand nombre de résultats
    // pour les tables volumineuses. Préférer get_all_paginated pour les listes.
    $stmt = $pdo->query("SELECT * FROM `$table` ORDER BY id DESC");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Récupère un enregistrement spécifique par son ID.
 * @param PDO $pdo L'objet PDO pour la connexion à la base de données.
 * @param string $table Le nom de la table.
 * @param int $id L'ID de l'enregistrement.
 * @return array|false L'enregistrement ou false si non trouvé.
 */
function get_one(PDO $pdo, string $table, int $id): array {
    $stmt = $pdo->prepare("SELECT * FROM `$table` WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

/**
 * Supprime un enregistrement par son ID.
 * @param PDO $pdo L'objet PDO pour la connexion à la base de données.
 * @param string $table Le nom de la table.
 * @param int $id L'ID de l'enregistrement.
 * @return bool True en cas de succès, false en cas d'échec.
 */
function delete_item(PDO $pdo, string $table, int $id): bool {
    try {
        $stmt = $pdo->prepare("DELETE FROM `$table` WHERE id = ?");
        return $stmt->execute([$id]);
    } catch (PDOException $e) {
        // Log l'erreur si nécessaire
        error_log("Erreur de suppression dans la table $table (ID: $id): " . $e->getMessage());
        return false;
    }
}

/**
 * Récupère les enregistrements d'une table avec pagination.
 * @param PDO $pdo L'objet PDO pour la connexion à la base de données.
 * @param string $table Le nom de la table.
 * @param int $items_per_page Le nombre d'éléments par page.
 * @param int $current_page La page actuelle demandée.
 * @param string $order_by La colonne par laquelle trier (par défaut 'id').
 * @param string $order_direction La direction du tri (par défaut 'DESC').
 * @return array Un tableau contenant les 'items', 'total_items', 'total_pages', 'current_page', 'items_per_page'.
 */
function get_all_paginated(PDO $pdo, string $table, int $items_per_page, int $current_page, string $order_by = 'id', string $order_direction = 'DESC'): array {

    $items_per_page = max(1, $items_per_page); // S'assurer d'avoir au moins 1 élément par page
    $current_page = max(1, $current_page); // S'assurer que la page est au moins 1

    // Récupérer le nombre total d'éléments dans la table
    $total_items_stmt = $pdo->query("SELECT COUNT(*) FROM `$table`");
    $total_items = $total_items_stmt->fetchColumn();

    // Calculer le nombre total de pages
    $total_pages = ($items_per_page > 0) ? ceil($total_items / $items_per_page) : 0;

    // S'assurer que la page actuelle ne dépasse pas le nombre total de pages (sauf si total_pages est 0)
    if ($total_pages > 0) {
         $current_page = min($current_page, $total_pages);
    } else {
        $current_page = 1; // Si aucun élément, la page reste 1
    }


    // Calculer l'offset
    $offset = ($current_page - 1) * $items_per_page;
    $offset = max(0, $offset); // L'offset ne peut pas être négatif


    // Récupérer les éléments pour la page actuelle
    // Utilisation de guillemets autour du nom de la table pour éviter les conflits avec les mots-clés SQL
    $sql = "SELECT * FROM `$table` ORDER BY `$order_by` $order_direction LIMIT :limit OFFSET :offset";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':limit', $items_per_page, PDO::PARAM_INT);
    $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    $items = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return [
        'items' => $items,
        'total_items' => $total_items,
        'total_pages' => $total_pages,
        'current_page' => $current_page,
        'items_per_page' => $items_per_page
    ];
}


// Ajoutez ici d'autres fonctions utilitaires pour l'admin
?>