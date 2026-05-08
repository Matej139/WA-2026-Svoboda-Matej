<?php

// Database poskytuje spojení k MySQL přes PDO.
// Jedna třída obsahuje informace o připojení a metodu pro vytvoření objektu PDO.
class Database {
    private $host = 'localhost';
    private $db_name = 'semester_app';
    private $username = 'root';
    private $password = '';
    public $conn;

    // Vrátí objekt PDO, který používáme pro všechny dotazy do databáze.
    public function getConnection() {
        $this->conn = null;

        try {
            $this->conn = new PDO('mysql:host=' . $this->host . ';dbname=' . $this->db_name . ';charset=utf8mb4', $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $exception) {
            echo 'Chyba připojení: ' . $exception->getMessage();
            exit;
        }

        return $this->conn;
    }
}
