<?php

require_once __DIR__ . '/Database.php';

// Comment model pracuje s komentáři k receptům.
// Umožňuje přidávat, číst, upravovat a mazat komentáře.
class Comment {
    private PDO $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    // Vloží nový komentář k receptu do tabulky comments.
    public function create(int $recipeId, int $userId, string $content): bool {
        $sql = 'INSERT INTO comments (recipe_id, user_id, content) VALUES (:recipe_id, :user_id, :content)';
        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':recipe_id' => $recipeId,
            ':user_id' => $userId,
            ':content' => $content
        ]);
    }

    // Vrátí všechny komentáře k danému receptu včetně jmen autorů
    public function getByRecipe(int $recipeId): array {
        $sql = 'SELECT comments.*, COALESCE(users.nickname, users.username) AS author_name, users.role AS author_role
                FROM comments
                LEFT JOIN users ON comments.user_id = users.id
                WHERE comments.recipe_id = :recipe_id
                ORDER BY comments.created_at DESC';
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':recipe_id' => $recipeId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Najde komentář podle ID včetně informací o autorovi
    public function getById(int $id) {
        $sql = 'SELECT comments.*, COALESCE(users.nickname, users.username) AS author_name, users.role AS author_role
                FROM comments
                LEFT JOIN users ON comments.user_id = users.id
                WHERE comments.id = :id';
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Aktualizuje obsah komentáře
    public function update(int $id, string $content): bool {
        $sql = 'UPDATE comments SET content = :content, updated_at = CURRENT_TIMESTAMP() WHERE id = :id';
        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':content' => $content,
            ':id' => $id
        ]);
    }

    // Smaže komentář z databáze
    public function delete(int $id): bool {
        $sql = 'DELETE FROM comments WHERE id = :id';
        $stmt = $this->db->prepare($sql);

        return $stmt->execute([':id' => $id]);
    }
}
