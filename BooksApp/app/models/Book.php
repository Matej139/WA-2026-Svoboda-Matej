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

    public function getAll() {
    $sql = "SELECT * FROM books";
    $stmt = $this->conn->prepare($sql);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

public function getById($id) {
    $sql = "SELECT * FROM books WHERE id = :id";
    $stmt = $this->conn->prepare($sql);
    $stmt->execute([':id' => $id]);

    return $stmt->fetch(PDO::FETCH_ASSOC);
}

public function update($id, $data) {
    $sql = "UPDATE books SET
        title = :title,
        author = :author,
        category = :category,
        subcategory = :subcategory,
        year = :year,
        price = :price,
        isbn = :isbn,
        description = :description
        WHERE id = :id";

    $stmt = $this->conn->prepare($sql);

    return $stmt->execute([
        ':id' => $id,
        ':title' => $data['title'],
        ':author' => $data['author'],
        ':category' => $data['category'],
        ':subcategory' => $data['subcategory'],
        ':year' => $data['year'],
        ':price' => $data['price'],
        ':isbn' => $data['isbn'],
        ':description' => $data['description']
    ]);
}

public function delete($id) {
    $sql = "DELETE FROM books WHERE id = :id";
    $stmt = $this->conn->prepare($sql);

    return $stmt->execute([':id' => $id]);
}
}