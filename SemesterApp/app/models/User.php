<?php

require_once __DIR__ . '/Database.php';

// User model pracuje s tabulkou users.
// Obsahuje metody pro registraci, hledání uživatele a správu profilu.
class User {
    private PDO $db;

    public function __construct(PDO $db) {
        $this->db = $db;
    }

    // Vytvoří nového uživatele. Kontroluje duplicitu e-mailu a heslo ukládá jako hash.
    public function register(string $username, string $email, string $password, ?string $firstName, ?string $lastName, ?string $nickname): bool {
        if ($this->findByEmail($email)) {
            return false;
        }

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $sql = 'INSERT INTO users (username, email, password, first_name, last_name, nickname, role)
                VALUES (:username, :email, :password, :first_name, :last_name, :nickname, :role)';
        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':username' => $username,
            ':email' => $email,
            ':password' => $hashedPassword,
            ':first_name' => $firstName,
            ':last_name' => $lastName,
            ':nickname' => $nickname,
            ':role' => 'user'
        ]);
    }

    // Najde uživatele podle e-mailu pro přihlášení a kontrolu duplicity.
    public function findByEmail(string $email) {
        $sql = 'SELECT * FROM users WHERE email = :email';
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':email' => $email]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Najde uživatele podle ID pro zobrazení profilu
    public function findById(int $id) {
        $sql = 'SELECT id, username, email, first_name, last_name, nickname, role, created_at FROM users WHERE id = :id';
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Vrátí seznam všech uživatelů (pro adminy)
    public function findAll() {
        $sql = 'SELECT id, username, email, nickname, role, created_at FROM users ORDER BY created_at DESC';
        $stmt = $this->db->query($sql);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Aktualizuje údaje uživatele v profilu
    public function updateProfile(int $id, string $username, string $email, ?string $firstName, ?string $lastName, ?string $nickname): bool {
        $sql = 'UPDATE users SET username = :username, email = :email, first_name = :first_name, last_name = :last_name, nickname = :nickname WHERE id = :id';
        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':username' => $username,
            ':email' => $email,
            ':first_name' => $firstName,
            ':last_name' => $lastName,
            ':nickname' => $nickname,
            ':id' => $id
        ]);
    }

    // Smaže uživatele z databáze (pouze pro adminy)
    public function deleteUser(int $id): bool {
        $sql = 'DELETE FROM users WHERE id = :id';
        $stmt = $this->db->prepare($sql);

        return $stmt->execute([':id' => $id]);
    }
}
