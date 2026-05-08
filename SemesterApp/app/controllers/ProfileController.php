<?php

// ProfileController spravuje profil uživatele, úpravu údajů a osobní oblíbené recepty.
// Zde také funguje administrátorské rozhraní pro správu uživatelů.
class ProfileController {
    // Zobrazí profil právě přihlášeného uživatele.
    public function show() {
        $this->ensureAuthenticated();

        require_once __DIR__ . '/../models/Database.php';
        require_once __DIR__ . '/../models/User.php';

        $db = (new Database())->getConnection();
        $user = (new User($db))->findById($_SESSION['user_id']);

        require_once __DIR__ . '/../views/profile/profile_show.php';
    }

    // Zobrazí stránku s recepty, které si uživatel označil jako oblíbené.
    public function favorites() {
        $this->ensureAuthenticated();

        require_once __DIR__ . '/../models/Recipe.php';

        $recipeModel = new Recipe();
        $favoriteRecipes = $recipeModel->getFavoritesByUser($_SESSION['user_id']);

        require_once __DIR__ . '/../views/profile/profile_favorites.php';
    }

    // Zobrazí formulář pro úpravu profilu uživatele
    public function edit() {
        $this->ensureAuthenticated();

        require_once __DIR__ . '/../models/Database.php';
        require_once __DIR__ . '/../models/User.php';

        $db = (new Database())->getConnection();
        $user = (new User($db))->findById($_SESSION['user_id']);

        require_once __DIR__ . '/../views/profile/profile_edit.php';
    }

    // Aktualizuje údaje uživatele v databázi
    public function update() {
        // Kontrola typu požadavku
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/index.php?url=profile/show');
            exit;
        }

        $this->ensureAuthenticated();

        require_once __DIR__ . '/../models/Database.php';
        require_once __DIR__ . '/../models/User.php';

        $db = (new Database())->getConnection();
        $userModel = new User($db);

        // Získání dat z formuláře
        $username = trim($_POST['username'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $firstName = trim($_POST['first_name'] ?? '');
        $lastName = trim($_POST['last_name'] ?? '');
        $nickname = trim($_POST['nickname'] ?? '');

        // Kontrola povinných polí
        if (empty($username) || empty($email)) {
            $_SESSION['messages']['error'][] = 'Uživatelské jméno a e-mail jsou povinné.';
            header('Location: ' . BASE_URL . '/index.php?url=profile/edit');
            exit;
        }

        // Kontrola platnosti e-mailu
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['messages']['error'][] = 'Zadejte platný e-mail.';
            header('Location: ' . BASE_URL . '/index.php?url=profile/edit');
            exit;
        }

        // Aktualizace profilu
        $updated = $userModel->updateProfile($_SESSION['user_id'], $username, $email, $firstName, $lastName, $nickname);

        if ($updated) {
            // Aktualizace session údajů
            $_SESSION['user_name'] = !empty($nickname) ? $nickname : $username;
            $_SESSION['messages']['success'][] = 'Profil byl aktualizován.';
        } else {
            $_SESSION['messages']['error'][] = 'Nepodařilo se uložit změny.';
        }

        header('Location: ' . BASE_URL . '/index.php?url=profile/show');
        exit;
    }

    // Zobrazí seznam všech uživatelů (pouze pro adminy)
    // Zobrazí seznam všech uživatelů (pouze pro adminy)
    public function index() {
        $this->ensureAuthenticated();
        $this->ensureAdmin();

        require_once __DIR__ . '/../models/Database.php';
        require_once __DIR__ . '/../models/User.php';

        $db = (new Database())->getConnection();
        $users = (new User($db))->findAll();

        require_once __DIR__ . '/../views/profile/user_list.php';
    }

    // Smaže uživatele (pouze pro adminy)
    public function delete($id = null) {
        $this->ensureAuthenticated();
        $this->ensureAdmin();

        if (!$id) {
            header('Location: ' . BASE_URL . '/index.php?url=profile/users');
            exit;
        }

        require_once __DIR__ . '/../models/Database.php';
        require_once __DIR__ . '/../models/User.php';

        $db = (new Database())->getConnection();
        $userModel = new User($db);

        if ($userModel->deleteUser((int)$id)) {
            $_SESSION['messages']['success'][] = 'Uživatel byl smazán.';
        } else {
            $_SESSION['messages']['error'][] = 'Nepodařilo se smazat uživatele.';
        }

        header('Location: ' . BASE_URL . '/index.php?url=profile/users');
        exit;
    }

    // Zkontroluje, zda je uživatel přihlášený
    // Zkontroluje, zda je uživatel přihlášený
    protected function ensureAuthenticated() {
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['messages']['notice'][] = 'Přihlaste se pro zobrazení profilu.';
            header('Location: ' . BASE_URL . '/index.php?url=auth/login');
            exit;
        }
    }

    // Zkontroluje, zda má uživatel roli admin
    protected function ensureAdmin() {
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            $_SESSION['messages']['error'][] = 'Přístup odepřen. Tato akce je určena pouze administrátorovi.';
            header('Location: ' . BASE_URL . '/index.php');
            exit;
        }
    }
}
