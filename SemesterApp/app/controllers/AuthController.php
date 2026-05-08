<?php

// AuthController zpracovává registraci, přihlášení a odhlášení uživatele.
class AuthController {
    // Zobrazí registrační formulář
    public function register() {
        require_once __DIR__ . '/../views/auth/register.php';
    }

    // Zpracuje registraci nového uživatele a uloží ho do databáze.
    public function storeUser() {
        // Kontrola, zda je požadavek typu POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/index.php?url=auth/register');
            exit;
        }

        // Získání dat z formuláře
        $username = trim($_POST['username'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $firstName = trim($_POST['first_name'] ?? '');
        $lastName = trim($_POST['last_name'] ?? '');
        $nickname = trim($_POST['nickname'] ?? '');
        $password = $_POST['password'] ?? '';
        $passwordConfirm = $_POST['password_confirm'] ?? '';

        // Kontrola povinných polí
        if (empty($username) || empty($email) || empty($password)) {
            $this->addErrorMessage('Vyplňte prosím všechna povinná pole.');
            header('Location: ' . BASE_URL . '/index.php?url=auth/register');
            exit;
        }

        // Kontrola platnosti e-mailu
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->addErrorMessage('Zadejte platný e-mail.');
            header('Location: ' . BASE_URL . '/index.php?url=auth/register');
            exit;
        }

        // Kontrola shody hesel
        if ($password !== $passwordConfirm) {
            $this->addErrorMessage('Hesla se neshodují.');
            header('Location: ' . BASE_URL . '/index.php?url=auth/register');
            exit;
        }

        // Kontrola síly hesla
        if (mb_strlen($password) < 8 || !preg_match('/\d/', $password)) {
            $this->addErrorMessage('Heslo musí mít alespoň 8 znaků a obsahovat číslo.');
            header('Location: ' . BASE_URL . '/index.php?url=auth/register');
            exit;
        }

        // Připojení k databázi a vytvoření instance modelu User
        require_once __DIR__ . '/../models/Database.php';
        require_once __DIR__ . '/../models/User.php';

        $db = (new Database())->getConnection();
        $userModel = new User($db);

        // Pokus o registraci uživatele
        if ($userModel->register($username, $email, $password, $firstName, $lastName, $nickname)) {
            $this->addSuccessMessage('Registrace proběhla úspěšně. Nyní se můžete přihlásit.');
            header('Location: ' . BASE_URL . '/index.php?url=auth/login');
            exit;
        }

        // Chyba při registraci
        $this->addErrorMessage('Uživatel s tímto e-mailem již existuje nebo došlo k chybě.');
        header('Location: ' . BASE_URL . '/index.php?url=auth/register');
        exit;
    }

    
    // Zobrazí přihlašovací formulář
    public function login() {
        require_once __DIR__ . '/../views/auth/login.php';
    }

    // Přihlásí uživatele pomocí e-mailu a hesla. Pokud je vše v pořádku, uloží informace do session.
    public function authenticate() {
        // Kontrola typu požadavku
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/index.php?url=auth/login');
            exit;
        }

        // Získání přihlašovacích údajů
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        // Připojení k databázi a vytvoření instance modelu User
        require_once __DIR__ . '/../models/Database.php';
        require_once __DIR__ . '/../models/User.php';

        $db = (new Database())->getConnection();
        $userModel = new User($db);

        // Hledání uživatele podle e-mailu
        $user = $userModel->findByEmail($email);

       
        // Ověření hesla a přihlášení
        if ($user && password_verify($password, $user['password'])) {
            // Uložení údajů do session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = !empty($user['nickname']) ? $user['nickname'] : $user['username'];
            $_SESSION['user_role'] = $user['role'];

            $this->addSuccessMessage('Vítejte zpět, ' . htmlspecialchars($_SESSION['user_name']) . '!');
            header('Location: ' . BASE_URL . '/index.php');
            exit;
        }

        // Chyba při přihlášení
        $this->addErrorMessage('Nesprávný e-mail nebo heslo.');
        header('Location: ' . BASE_URL . '/index.php?url=auth/login');
        exit;
    }

    // Odhlásí uživatele a vymaže session
    public function logout() {
        session_unset();
        session_destroy();
        session_start();

        $this->addSuccessMessage('Odhlášení proběhlo úspěšně.');
        header('Location: ' . BASE_URL . '/index.php');
        exit;
    }

    // Přidá úspěšnou zprávu do session pro zobrazení
    protected function addSuccessMessage(string $message) {
        $_SESSION['messages']['success'][] = $message;
    }

    // Přidá chybovou zprávu do session pro zobrazení
    protected function addErrorMessage(string $message) {
        $_SESSION['messages']['error'][] = $message;
    }
}
