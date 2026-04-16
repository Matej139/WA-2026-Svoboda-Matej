<?php

class AuthController {
    public function register() {
        require_once __DIR__ . '/../views/auth/register.php';
    }

    public function storeUser() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = htmlspecialchars($_POST['username'] ?? '');
            $email = htmlspecialchars($_POST['email'] ?? '');
            $firstName = htmlspecialchars($_POST['first_name'] ?? '');
            $lastName = htmlspecialchars($_POST['last_name'] ?? '');
            $nickname = htmlspecialchars($_POST['nickname'] ?? '');
            $password = $_POST['password'] ?? '';
            $passwordConfirm = $_POST['password_confirm'] ?? '';

            if (empty($username) || empty($email) || empty($password)) {
                $this->addErrorMessage('Vyplňte prosím všechna povinná pole.');
                header('Location: ' . BASE_URL . '/index.php?url=auth/register');
                exit;
            }

            if ($password !== $passwordConfirm) {
                $this->addErrorMessage('Zadaná hesla se neshodují.');
                header('Location: ' . BASE_URL . '/index.php?url=auth/register');
                exit;
            }

            if (mb_strlen($password) < 8 || !preg_match('/\d/', $password)) {
                $this->addErrorMessage('Heslo musí mít alespoň 8 znaků a obsahovat alespoň jedno číslo.');
                header('Location: ' . BASE_URL . '/index.php?url=auth/register');
                exit;
            }

            require_once __DIR__ . '/../models/Database.php';
            require_once __DIR__ . '/../models/User.php';

            $db = (new Database())->getConnection();
            $userModel = new User($db);

            if ($userModel->register($username, $email, $password, $firstName, $lastName, $nickname)) {
                $this->addSuccessMessage('Registrace byla úspěšná. Nyní se můžete přihlásit.');
                header('Location: ' . BASE_URL . '/index.php?url=auth/login');
                exit;
            }

            $this->addErrorMessage('Uživatel s tímto e-mailem již existuje nebo došlo k chybě.');
            header('Location: ' . BASE_URL . '/index.php?url=auth/register');
            exit;
        }
    }

    public function login() {
        require_once __DIR__ . '/../views/auth/login.php';
    }

    public function authenticate() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = htmlspecialchars($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';

            require_once __DIR__ . '/../models/Database.php';
            require_once __DIR__ . '/../models/User.php';

            $db = (new Database())->getConnection();
            $userModel = new User($db);

            $user = $userModel->findByEmail($email);

            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = !empty($user['nickname']) ? $user['nickname'] : $user['username'];

                $this->addSuccessMessage('Vítejte zpět, ' . $_SESSION['user_name'] . '!');
                header('Location: ' . BASE_URL . '/index.php');
                exit;
            }

            $this->addErrorMessage('Nesprávný e-mail nebo heslo.');
            header('Location: ' . BASE_URL . '/index.php?url=auth/login');
            exit;
        }
    }

    public function logout() {
        unset($_SESSION['user_id']);
        unset($_SESSION['user_name']);

        $this->addSuccessMessage('Byli jste úspěšně odhlášeni.');
        header('Location: ' . BASE_URL . '/index.php');
        exit;
    }

    protected function addSuccessMessage($message) {
        $_SESSION['messages']['success'][] = $message;
    }

    protected function addNoticeMessage($message) {
        $_SESSION['messages']['notice'][] = $message;
    }

    protected function addErrorMessage($message) {
        $_SESSION['messages']['error'][] = $message;
    }
}
