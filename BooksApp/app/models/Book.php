<?php

require_once 'Database.php';

class Book {
    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function create($data) {
        $sql = "INSERT INTO books (title, author, category, subcategory, isbn, year, price, description)
                VALUES (:title, :author, :category, :subcategory, :isbn, :year, :price, :description)";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            ':title' => $data['title'],
            ':author' => $data['author'],
            ':category' => $data['category'],
            ':subcategory' => $data['subcategory'],
            ':isbn' => $data['isbn'],
            ':year' => $data['year'],
            ':price' => $data['price'],
            ':description' => $data['description']
        ]);
    }
}