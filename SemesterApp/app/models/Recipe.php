<?php

require_once __DIR__ . '/Database.php';

// Recipe model pracuje s tabulkou recipes a souvisejícími tabulkami favorites/ratings.
// Obsahuje metody pro CRUD receptů, hodnocení a oblíbené recepty.
class Recipe {
    private PDO $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    // Vytvoří nový recept ve sloupci recipes. Obrázky se ukládají jako JSON pole.
    public function create(array $data, ?int $createdBy = null): bool {
        $sql = 'INSERT INTO recipes (title, ingredients, category, difficulty, cook_time, cost, description, images, created_by)
                VALUES (:title, :ingredients, :category, :difficulty, :cook_time, :cost, :description, :images, :created_by)';
        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':title' => $data['title'],
            ':ingredients' => $data['ingredients'],
            ':category' => $data['category'],
            ':difficulty' => $data['difficulty'],
            ':cook_time' => $data['cook_time'],
            ':cost' => $data['cost'],
            ':description' => $data['description'],
            ':images' => !empty($data['images']) ? json_encode($data['images']) : null,
            ':created_by' => $createdBy
        ]);
    }

    // Vrátí všechny recepty spolu s autorem, průměrným hodnocením a počtem oblíbených.
    public function getAll(): array {
        $sql = 'SELECT
                    recipes.*,
                    COALESCE(users.nickname, users.username) AS author_name,
                    COALESCE(rating_summary.avg_rating, 0) AS average_rating,
                    COALESCE(rating_summary.rating_count, 0) AS rating_count,
                    COALESCE(favorite_summary.favorite_count, 0) AS favorite_count
                FROM recipes
                LEFT JOIN users ON recipes.created_by = users.id
                LEFT JOIN (
                    SELECT recipe_id, ROUND(AVG(rating), 1) AS avg_rating, COUNT(*) AS rating_count
                    FROM ratings
                    GROUP BY recipe_id
                ) AS rating_summary ON recipes.id = rating_summary.recipe_id
                LEFT JOIN (
                    SELECT recipe_id, COUNT(*) AS favorite_count
                    FROM favorites
                    GROUP BY recipe_id
                ) AS favorite_summary ON recipes.id = favorite_summary.recipe_id
                ORDER BY recipes.created_at DESC';
        $stmt = $this->db->query($sql);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Získá detail jednoho receptu podle ID včetně autora a statistik hodnocení/oblíbených.
    public function getById(int $id) {
        $sql = 'SELECT
                    recipes.*,
                    COALESCE(users.nickname, users.username) AS author_name,
                    users.id AS author_id,
                    COALESCE(rating_summary.avg_rating, 0) AS average_rating,
                    COALESCE(rating_summary.rating_count, 0) AS rating_count,
                    COALESCE(favorite_summary.favorite_count, 0) AS favorite_count
                FROM recipes
                LEFT JOIN users ON recipes.created_by = users.id
                LEFT JOIN (
                    SELECT recipe_id, ROUND(AVG(rating), 1) AS avg_rating, COUNT(*) AS rating_count
                    FROM ratings
                    WHERE recipe_id = :id
                    GROUP BY recipe_id
                ) AS rating_summary ON recipes.id = rating_summary.recipe_id
                LEFT JOIN (
                    SELECT recipe_id, COUNT(*) AS favorite_count
                    FROM favorites
                    WHERE recipe_id = :id
                    GROUP BY recipe_id
                ) AS favorite_summary ON recipes.id = favorite_summary.recipe_id
                WHERE recipes.id = :id';
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Vrátí hodnocení receptu od konkrétního uživatele, pokud už hodnotil.
    public function getUserRating(int $recipeId, int $userId): ?int {
        $sql = 'SELECT rating FROM ratings WHERE recipe_id = :recipe_id AND user_id = :user_id';
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':recipe_id' => $recipeId,
            ':user_id' => $userId
        ]);

        $rating = $stmt->fetch(PDO::FETCH_ASSOC);
        return $rating ? (int)$rating['rating'] : null;
    }

    // Vrátí seznam ID receptů, které má uživatel označené jako oblíbené.
    public function getFavoriteRecipeIds(int $userId): array {
        $sql = 'SELECT recipe_id FROM favorites WHERE user_id = :user_id';
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':user_id' => $userId]);

        return array_column($stmt->fetchAll(PDO::FETCH_ASSOC), 'recipe_id');
    }

    // Vrátí seznam oblíbených receptů pro přihlášeného uživatele.
    public function getFavoritesByUser(int $userId): array {
        $sql = 'SELECT
                    recipes.*,
                    COALESCE(users.nickname, users.username) AS author_name,
                    COALESCE(rating_summary.avg_rating, 0) AS average_rating,
                    COALESCE(rating_summary.rating_count, 0) AS rating_count,
                    COALESCE(favorite_summary.favorite_count, 0) AS favorite_count
                FROM recipes
                JOIN favorites ON recipes.id = favorites.recipe_id
                LEFT JOIN users ON recipes.created_by = users.id
                LEFT JOIN (
                    SELECT recipe_id, ROUND(AVG(rating), 1) AS avg_rating, COUNT(*) AS rating_count
                    FROM ratings
                    GROUP BY recipe_id
                ) AS rating_summary ON recipes.id = rating_summary.recipe_id
                LEFT JOIN (
                    SELECT recipe_id, COUNT(*) AS favorite_count
                    FROM favorites
                    GROUP BY recipe_id
                ) AS favorite_summary ON recipes.id = favorite_summary.recipe_id
                WHERE favorites.user_id = :user_id
                ORDER BY favorites.created_at DESC';
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':user_id' => $userId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Zjistí, zda si uživatel již označil recept jako oblíbený.
    public function isFavorite(int $recipeId, int $userId): bool {
        $sql = 'SELECT 1 FROM favorites WHERE recipe_id = :recipe_id AND user_id = :user_id LIMIT 1';
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':recipe_id' => $recipeId,
            ':user_id' => $userId
        ]);

        return (bool)$stmt->fetchColumn();
    }

    // Přepíná stav oblíbeného receptu pro uživatele.
    // Pokud už je v oblíbených, smaže záznam, jinak ho vloží.
    public function toggleFavorite(int $recipeId, int $userId): bool {
        // Kontrola, zda už je v oblíbených
        if ($this->isFavorite($recipeId, $userId)) {
            // Odebrání z oblíbených
            $sql = 'DELETE FROM favorites WHERE recipe_id = :recipe_id AND user_id = :user_id';
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                ':recipe_id' => $recipeId,
                ':user_id' => $userId
            ]);
        }

        // Přidání do oblíbených
        $sql = 'INSERT INTO favorites (recipe_id, user_id) VALUES (:recipe_id, :user_id)';
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':recipe_id' => $recipeId,
            ':user_id' => $userId
        ]);
    }

    // Uloží hodnocení pro recept od konkrétního uživatele.
    // Pokud již existuje, aktualizuje ho, jinak vytvoří nový záznam.
    public function saveRating(int $recipeId, int $userId, int $rating): bool {
        // Kontrola, zda uživatel již hodnotil
        $currentRating = $this->getUserRating($recipeId, $userId);

        if ($currentRating !== null) {
            // Aktualizace existujícího hodnocení
            $sql = 'UPDATE ratings SET rating = :rating, updated_at = CURRENT_TIMESTAMP() WHERE recipe_id = :recipe_id AND user_id = :user_id';
        } else {
            // Vytvoření nového hodnocení
            $sql = 'INSERT INTO ratings (recipe_id, user_id, rating) VALUES (:recipe_id, :user_id, :rating)';
        }

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':recipe_id' => $recipeId,
            ':user_id' => $userId,
            ':rating' => $rating
        ]);
    }

    // Aktualizuje recept a uloží informaci, kdo ho naposledy upravil.
    public function update(int $id, array $data, ?int $updatedBy = null): bool {
        $sql = 'UPDATE recipes SET
                    title = :title,
                    ingredients = :ingredients,
                    category = :category,
                    difficulty = :difficulty,
                    cook_time = :cook_time,
                    cost = :cost,
                    description = :description,
                    images = :images,
                    updated_by = :updated_by,
                    updated_at = CURRENT_TIMESTAMP()
                WHERE id = :id';
        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':id' => $id,
            ':title' => $data['title'],
            ':ingredients' => $data['ingredients'],
            ':category' => $data['category'],
            ':difficulty' => $data['difficulty'],
            ':cook_time' => $data['cook_time'],
            ':cost' => $data['cost'],
            ':description' => $data['description'],
            ':images' => !empty($data['images']) ? json_encode($data['images']) : null,
            ':updated_by' => $updatedBy
        ]);
    }

    // Smaže recept a zároveň odstraní všechny jeho nahrané obrázky z disku.
    public function delete(int $id): bool {
        // Získání informací o receptu pro smazání obrázků
        $recipe = $this->getById($id);
        if ($recipe && !empty($recipe['images'])) {
            $images = json_decode($recipe['images'], true);
            if (is_array($images)) {
                foreach ($images as $image) {
                    $imagePath = __DIR__ . '/../../public/uploads/' . $image;
                    if (file_exists($imagePath)) {
                        @unlink($imagePath);
                    }
                }
            }
        }

        // Smazání receptu z databáze
        $sql = 'DELETE FROM recipes WHERE id = :id';
        $stmt = $this->db->prepare($sql);

        return $stmt->execute([':id' => $id]);
    }
}
