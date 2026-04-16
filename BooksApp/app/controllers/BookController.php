<?php

class BookController {
    public function create() {
        $this->ensureAuthenticated();
        require_once __DIR__ . '/../views/books/book_create.php';
    }

    public function store() {
        $this->ensureAuthenticated();

        require_once __DIR__ . '/../models/Book.php';
        require_once __DIR__ . '/../dto/BookDTO.php';
        
        $uploadedImages = $this->processImageUploads();
        
        $data = $_POST;
        $data['images'] = $uploadedImages;
        
        $bookDTO = new BookDTO($data);
        $book = new Book();
        $createdBy = $_SESSION['user_id'] ?? null;
        $isSaved = $book->create($bookDTO, $createdBy);

        if ($isSaved) {
            $_SESSION['messages']['success'][] = 'Kniha byla úspěšně uložena.';
        } else {
            $_SESSION['messages']['error'][] = 'Chyba při ukládání knihy.';
        }

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

    $this->ensureBookOwner($id);

    require_once '../app/models/Book.php';

    $bookModel = new Book();
    $book = $bookModel->getById($id);

    require_once __DIR__ . '/../views/books/book_edit.php';
}

public function update($id = null) {
    $this->ensureBookOwner($id);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        require_once '../app/models/Book.php';
        require_once '../app/dto/BookDTO.php';

        $uploadedImages = $this->processImageUploads();
        
        $bookModel = new Book();
        $existingBook = $bookModel->getById($id);
        $existingImages = $existingBook && !empty($existingBook['images']) ? json_decode($existingBook['images'], true) : [];
        
        if (empty($uploadedImages)) {
            $allImages = $existingImages;
        } else {
            $allImages = $uploadedImages;
        }
        
        $data = $_POST;
        $data['images'] = $allImages;
        
        $bookDTO = new BookDTO($data);
        $isUpdated = $bookModel->update($id, $bookDTO, $_SESSION['user_id'] ?? null);

        if ($isUpdated) {
            $_SESSION['messages']['success'][] = 'Kniha byla úspěšně aktualizována.';
        } else {
            $_SESSION['messages']['error'][] = 'Chyba při aktualizaci knihy.';
        }

        header("Location: /WA-2026-Svoboda-Matej/BooksApp/public/index.php");
        exit;
    }
}

public function delete($id = null) {
    if (!$id) {
        echo "Chybí ID";
        return;
    }

    $this->ensureBookOwner($id);

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

    protected function ensureAuthenticated() {
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['messages']['notice'][] = 'Pro přístup této stránky se musíte nejprve přihlásit.';
            header('Location: ' . BASE_URL . '/index.php?url=auth/login');
            exit;
        }
    }

    protected function ensureBookOwner($id) {
        $this->ensureAuthenticated();

        require_once __DIR__ . '/../models/Book.php';
        $bookModel = new Book();
        $book = $bookModel->getById($id);

        if (!$book) {
            $_SESSION['messages']['error'][] = 'Požadovaná kniha nebyla nalezena.';
            header('Location: ' . BASE_URL . '/index.php');
            exit;
        }

        if (!isset($book['created_by']) || $book['created_by'] !== $_SESSION['user_id']) {
            $_SESSION['messages']['error'][] = 'Nemáte oprávnění upravovat nebo mazat tuto knihu.';
            header('Location: ' . BASE_URL . '/index.php');
            exit;
        }
    }

    // --- Pomocná metoda pro zpracování nahrávání obrázků ---
    protected function processImageUploads() {
        $uploadedFiles = [];
        
        // Cesta ke složce, kam se budou obrázky fyzicky ukládat (relativně od index.php)
        $uploadDir = __DIR__ . '/../../public/uploads/'; 
        
        // Zkontrolujeme, zda vůbec existuje adresář, pokud ne, vytvoříme ho
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        // Zkontrolujeme, zda byl odeslán alespoň jeden soubor
        if (isset($_FILES['images']) && !empty($_FILES['images']['name'][0])) {
            $fileCount = count($_FILES['images']['name']);

            for ($i = 0; $i < $fileCount; $i++) {
                // Pokud při nahrávání tohoto konkrétního souboru nedošlo k chybě
                if ($_FILES['images']['error'][$i] === UPLOAD_ERR_OK) {
                    
                    $tmpName = $_FILES['images']['tmp_name'][$i];
                    $originalName = basename($_FILES['images']['name'][$i]);
                    // Zjištění koncovky (např. jpg, png)
                    $fileExtension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));

                    // Kontrola povolených přípon
                    $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
                    if (!in_array($fileExtension, $allowedExtensions)) {
                        continue; // Přeskočíme nepodporovaný soubor
                    }

                    // Kontrola skutečného MIME typu souboru (zabránění podvrhu - aby soubor .exe nebyl přejmenován na .jpg)
                    $finfo = finfo_open(FILEINFO_MIME_TYPE);
                    $mimeType = finfo_file($finfo, $tmpName);
                    finfo_close($finfo);
                    
                    $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
                    if (!in_array($mimeType, $allowedMimeTypes)) {
                        continue; // Přeskočíme soubor se špatným MIME typem
                    }

                    // 1. Vygenerování unikátního jména pomocí aktuálního času a náhodného řetězce
                    // např: book_64a2b1c_8f2a.jpg
                    $newName = 'book_' . uniqid() . '_' . substr(md5(mt_rand()), 0, 4) . '.' . $fileExtension;
                    $targetFilePath = $uploadDir . $newName;

                    // 2. Fyzický přesun souboru z dočasné paměti do naší složky uploads
                    if (move_uploaded_file($tmpName, $targetFilePath)) {
                        // 3. Uložení POUZE NÁZVU do pole, které pak pošleme databázi
                        $uploadedFiles[] = $newName; 
                    }
                }
            }
        }
        return $uploadedFiles;
    }
}