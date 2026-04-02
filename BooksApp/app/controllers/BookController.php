<?php

class BookController {
    public function create() {
    require_once __DIR__ . '/../views/books/book_create.php';
}

public function store() {
    require_once __DIR__ . '/../models/Book.php';
    $book = new Book();

    $book->create($_POST);

    echo "Uloženo!";
    header("Location: /WA-2026-Svoboda-Matej/BooksApp/public/index.php");
exit;
}

//0. Výchozí metoda pro zobrazení úvodní stránky
public function index() {
    require_once '../app/models/Book.php';

    $book = new Book();
    $books = $book->getAll();

    require_once __DIR__ . '/../views/books/books_list.php';
}

public function edit($id = null) {
    if (!$id) {
        echo "Chybí ID";
        return;
    }

    require_once '../app/models/Book.php';

    $bookModel = new Book();
    $book = $bookModel->getById($id);

    require_once __DIR__ . '/../views/books/book_edit.php';
}

public function update($id = null) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        require_once '../app/models/Book.php';

        $bookModel = new Book();
        $bookModel->update($id, $_POST);

        header("Location: /WA-2026-Svoboda-Matej/BooksApp/public/index.php");
        exit;
    }
}

public function delete($id = null) {
    if (!$id) {
        echo "Chybí ID";
        return;
    }

    require_once '../app/models/Book.php';

    $book = new Book();
    $book->delete($id);

    header("Location: /WA-2026-Svoboda-Matej/BooksApp/public/index.php");
    exit;
}

public function show($id = null) {
    if (!$id) {
        echo "Chybí ID";
        return;
    }

    require_once '../app/models/Book.php';

    $bookModel = new Book();
    $book = $bookModel->getById($id);

    require_once __DIR__ . '/../views/books/book_show.php';
}

}