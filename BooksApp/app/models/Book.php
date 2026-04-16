<?php

require_once 'Database.php';

class Book {
    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function create(BookDTO $bookDTO, ?int $createdBy = null) {
        $sql = "INSERT INTO books (title, author, category, subcategory, isbn, year, price, description, images, created_by)
                VALUES (:title, :author, :category, :subcategory, :isbn, :year, :price, :description, :images, :created_by)";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            ':title' => $bookDTO->title,
            ':author' => $bookDTO->author,
            ':category' => $bookDTO->category,
            ':subcategory' => $bookDTO->subcategory,
            ':isbn' => $bookDTO->isbn,
            ':year' => $bookDTO->year,
            ':price' => $bookDTO->price,
            ':description' => $bookDTO->description,
            ':images' => !empty($bookDTO->images) ? json_encode($bookDTO->images) : null,
            ':created_by' => $createdBy
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

    public function delete($id) {
        $book = $this->getById($id);

        if ($book && !empty($book['images'])) {
            $images = json_decode($book['images'], true);
            if (is_array($images)) {
                foreach ($images as $image) {
                    $imagePath = __DIR__ . '/../../public/uploads/' . $image;
                    if (file_exists($imagePath)) {
                        @unlink($imagePath);
                    }
                }
            }
        }

        $sql = "DELETE FROM books WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    public function update($id, BookDTO $bookDTO, ?int $updatedBy = null) {
        $sql = "UPDATE books SET
        title = :title,
        author = :author,
        category = :category,
        subcategory = :subcategory,
        year = :year,
        price = :price,
        isbn = :isbn,
        description = :description,
        images = :images,
        updated_by = :updated_by
        WHERE id = :id";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            ':id' => $id,
            ':title' => $bookDTO->title,
            ':author' => $bookDTO->author,
            ':category' => $bookDTO->category,
            ':subcategory' => $bookDTO->subcategory,
            ':year' => $bookDTO->year,
            ':price' => $bookDTO->price,
            ':isbn' => $bookDTO->isbn,
            ':description' => $bookDTO->description,
            ':images' => !empty($bookDTO->images) ? json_encode($bookDTO->images) : null,
            ':updated_by' => $updatedBy
        ]);
    }
}