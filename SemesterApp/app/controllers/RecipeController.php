<?php

// RecipeController obsluhuje zobrazování receptů a všechny akce s recepty.
// Zajišťuje CRUD operace, hodnocení, oblíbené i omezení přístupu.
class RecipeController {
    // Zobrazí hlavní stránku se seznamem všech receptů.
    public function index() {
        require_once __DIR__ . '/../models/Recipe.php';

        $recipeModel = new Recipe();
        $recipes = $recipeModel->getAll();
        $favoriteRecipeIds = [];

        if (isset($_SESSION['user_id'])) {
            $favoriteRecipeIds = $recipeModel->getFavoriteRecipeIds($_SESSION['user_id']);
        }

        require_once __DIR__ . '/../views/recipes/recipe_list.php';
    }

    // Zobrazí formulář pro vytvoření nového receptu
    public function create() {
        $this->ensureAuthenticated();

        $categories = $this->categories();
        $difficulties = $this->difficulties();

        require_once __DIR__ . '/../views/recipes/recipe_create.php';
    }

    // Uloží nový recept do databáze
    public function store() {
        $this->ensureAuthenticated();

        require_once __DIR__ . '/../models/Recipe.php';

        // Zpracování nahraných obrázků
        $uploadedImages = $this->processImageUploads();
        // Vyčištění dat z formuláře
        $data = $this->sanitizeRecipeData($_POST);
        $data['images'] = $uploadedImages;

        $recipeModel = new Recipe();
        $isSaved = $recipeModel->create($data, $_SESSION['user_id']);

        if ($isSaved) {
            $_SESSION['messages']['success'][] = 'Recept byl úspěšně přidán.';
        } else {
            $_SESSION['messages']['error'][] = 'Chyba při ukládání receptu.';
        }

        header('Location: ' . BASE_URL . '/index.php');
        exit;
    }

    // Zobrazí detail konkrétního receptu včetně komentářů, vašeho hodnocení a stavu oblíbeného.
    public function show($id = null) {
        if (!$id) {
            echo 'Chybí ID receptu.';
            return;
        }

        require_once __DIR__ . '/../models/Recipe.php';
        require_once __DIR__ . '/../models/Comment.php';

        $recipeModel = new Recipe();
        $recipe = $recipeModel->getById((int)$id);

        if (!$recipe) {
            $_SESSION['messages']['error'][] = 'Recept nebyl nalezen.';
            header('Location: ' . BASE_URL . '/index.php');
            exit;
        }

        $commentModel = new Comment();
        $comments = $commentModel->getByRecipe((int)$id);

        // Kontrola práv pro úpravu receptu (vlastník nebo admin)
        $canEditRecipe = isset($_SESSION['user_id']) && (($_SESSION['user_id'] === $recipe['created_by']) || ($_SESSION['user_role'] === 'admin'));
        // Kontrola, zda je recept v oblíbených
        $isFavorite = isset($_SESSION['user_id']) ? $recipeModel->isFavorite((int)$id, $_SESSION['user_id']) : false;
        // Získání uživatelova hodnocení
        $userRating = isset($_SESSION['user_id']) ? $recipeModel->getUserRating((int)$id, $_SESSION['user_id']) : null;
        // Kontrola, zda může uživatel hodnotit (nesmí hodnotit vlastní recept)
        $canRate = isset($_SESSION['user_id']) && $_SESSION['user_id'] !== $recipe['created_by'];

        require_once __DIR__ . '/../views/recipes/recipe_show.php';
    }

    // Přepne recept mezi oblíbené pro přihlášeného uživatele.
    public function favorite($id = null) {
        $this->ensureAuthenticated();

        if (!$id) {
            header('Location: ' . BASE_URL . '/index.php');
            exit;
        }

        require_once __DIR__ . '/../models/Recipe.php';

        $recipeModel = new Recipe();
        $recipeModel->toggleFavorite((int)$id, $_SESSION['user_id']);

        $_SESSION['messages']['success'][] = 'Nastavení oblíbeného receptu bylo aktualizováno.';
        header('Location: ' . BASE_URL . '/index.php?url=recipe/show/' . $id);
        exit;
    }

    // Uloží uživatelovo hodnocení receptu.
    public function rate($id = null) {
        $this->ensureAuthenticated();

        if (!$id || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/index.php');
            exit;
        }

        // Kontrola platnosti hodnocení (1-5 hvězd)
        $rating = isset($_POST['rating']) ? (int)$_POST['rating'] : 0;
        if ($rating < 1 || $rating > 5) {
            $_SESSION['messages']['error'][] = 'Hodnocení musí být mezi 1 a 5 hvězdami.';
            header('Location: ' . BASE_URL . '/index.php?url=recipe/show/' . $id);
            exit;
        }

        require_once __DIR__ . '/../models/Recipe.php';

        $recipeModel = new Recipe();
        if ($recipeModel->saveRating((int)$id, $_SESSION['user_id'], $rating)) {
            $_SESSION['messages']['success'][] = 'Hodnocení bylo uloženo.';
        } else {
            $_SESSION['messages']['error'][] = 'Chyba při ukládání hodnocení.';
        }

        header('Location: ' . BASE_URL . '/index.php?url=recipe/show/' . $id);
        exit;
    }

    // Zobrazí formulář pro úpravu receptu
    // Zobrazí formulář pro úpravu receptu
    public function edit($id = null) {
        if (!$id) {
            echo 'Chybí ID receptu.';
            return;
        }

        require_once __DIR__ . '/../models/Recipe.php';

        $this->ensureRecipeOwnerOrAdmin((int)$id);

        $recipeModel = new Recipe();
        $recipe = $recipeModel->getById((int)$id);
        $categories = $this->categories();
        $difficulties = $this->difficulties();

        require_once __DIR__ . '/../views/recipes/recipe_edit.php';
    }

    // Aktualizuje existující recept
    public function update($id = null) {
        if (!$id || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/index.php');
            exit;
        }

        require_once __DIR__ . '/../models/Recipe.php';

        $this->ensureRecipeOwnerOrAdmin((int)$id);

        $recipeModel = new Recipe();
        $existingRecipe = $recipeModel->getById((int)$id);
        $existingImages = !empty($existingRecipe['images']) ? json_decode($existingRecipe['images'], true) : [];
        $uploadedImages = $this->processImageUploads();

        $data = $this->sanitizeRecipeData($_POST);
        // Pokud nejsou nahrané nové obrázky, použijí se stávající
        $data['images'] = empty($uploadedImages) ? $existingImages : $uploadedImages;

        $isUpdated = $recipeModel->update((int)$id, $data, $_SESSION['user_id']);

        if ($isUpdated) {
            $_SESSION['messages']['success'][] = 'Recept byl upraven.';
        } else {
            $_SESSION['messages']['error'][] = 'Chyba při úpravě receptu.';
        }

        header('Location: ' . BASE_URL . '/index.php?url=recipe/show/' . $id);
        exit;
    }

    // Smaže recept
    // Smaže recept
    public function delete($id = null) {
        if (!$id) {
            header('Location: ' . BASE_URL . '/index.php');
            exit;
        }

        require_once __DIR__ . '/../models/Recipe.php';

        $this->ensureRecipeOwnerOrAdmin((int)$id);

        $recipeModel = new Recipe();
        $success = $recipeModel->delete((int)$id);

        if ($success) {
            $_SESSION['messages']['success'][] = 'Recept byl smazán.';
        } else {
            $_SESSION['messages']['error'][] = 'Chyba při mazání receptu.';
        }

        header('Location: ' . BASE_URL . '/index.php');
        exit;
    }

    // Vyčistí a zabezpečí data z formuláře receptu
    // Vyčistí a zabezpečí data z formuláře receptu
    protected function sanitizeRecipeData(array $input): array {
        return [
            'title' => htmlspecialchars(trim($input['title'] ?? '')),
            'ingredients' => htmlspecialchars(trim($input['ingredients'] ?? '')),
            'category' => htmlspecialchars(trim($input['category'] ?? 'Nebyl vybrán')),
            'difficulty' => htmlspecialchars(trim($input['difficulty'] ?? 'Easy')),
            'cook_time' => (int)($input['cook_time'] ?? 0),
            'cost' => number_format((float)($input['cost'] ?? 0), 2, '.', ''),
            'description' => htmlspecialchars(trim($input['description'] ?? ''))
        ];
    }

    // Vrátí seznam dostupných kategorií receptů
    protected function categories(): array {
        return ['Sladké', 'Slané', 'Rychlé jídlo', 'Piknik', 'Zdravé', 'Pečení'];
    }

    // Vrátí seznam dostupných obtížností receptů
    protected function difficulties(): array {
        return ['Easy', 'Medium', 'Hard'];
    }

    // Zkontroluje, zda je uživatel přihlášený, jinak přesměruje na přihlášení
    // Zkontroluje, zda je uživatel přihlášený, jinak přesměruje na přihlášení
    protected function ensureAuthenticated() {
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['messages']['notice'][] = 'Musíte být přihlášeni pro úpravu receptů.';
            header('Location: ' . BASE_URL . '/index.php?url=auth/login');
            exit;
        }
    }

    // Zkontroluje, zda je uživatel vlastník receptu nebo admin, jinak přesměruje
    // Zkontroluje, zda je uživatel vlastník receptu nebo admin, jinak přesměruje
    protected function ensureRecipeOwnerOrAdmin(int $recipeId) {
        $this->ensureAuthenticated();

        require_once __DIR__ . '/../models/Recipe.php';

        $recipeModel = new Recipe();
        $recipe = $recipeModel->getById($recipeId);

        if (!$recipe) {
            $_SESSION['messages']['error'][] = 'Recept nebyl nalezen.';
            header('Location: ' . BASE_URL . '/index.php');
            exit;
        }

        if (!isset($_SESSION['user_id']) || ($_SESSION['user_id'] !== $recipe['created_by'] && $_SESSION['user_role'] !== 'admin')) {
            $_SESSION['messages']['error'][] = 'Nemáte oprávnění upravovat nebo mazat tento recept.';
            header('Location: ' . BASE_URL . '/index.php');
            exit;
        }
    }

    // Zpracuje nahrané obrázky, ověří je a uloží do složky uploads
    // Zpracuje nahrané obrázky, ověří je a uloží do složky uploads
    protected function processImageUploads(): array {
        $uploadedFiles = [];
        $uploadDir = __DIR__ . '/../../public/uploads/';

        // Vytvoří složku uploads, pokud neexistuje
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        if (isset($_FILES['images']) && !empty($_FILES['images']['name'][0])) {
            $fileCount = count($_FILES['images']['name']);

            for ($i = 0; $i < $fileCount; $i++) {
                if ($_FILES['images']['error'][$i] === UPLOAD_ERR_OK) {
                    $tmpName = $_FILES['images']['tmp_name'][$i];
                    $originalName = basename($_FILES['images']['name'][$i]);
                    $fileExtension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
                    $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp'];

                    // Kontrola přípony souboru
                    if (!in_array($fileExtension, $allowedExtensions)) {
                        continue;
                    }

                    // Kontrola MIME typu
                    $finfo = finfo_open(FILEINFO_MIME_TYPE);
                    $mimeType = finfo_file($finfo, $tmpName);
                    finfo_close($finfo);
                    $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/webp'];

                    if (!in_array($mimeType, $allowedMimeTypes)) {
                        continue;
                    }

                    // Generování unikátního názvu souboru
                    $newName = 'recipe_' . uniqid() . '.' . $fileExtension;
                    $targetFilePath = $uploadDir . $newName;

                    // Přesun souboru do cílové složky
                    if (move_uploaded_file($tmpName, $targetFilePath)) {
                        $uploadedFiles[] = $newName;
                    }
                }
            }
        }

        return $uploadedFiles;
    }
}
