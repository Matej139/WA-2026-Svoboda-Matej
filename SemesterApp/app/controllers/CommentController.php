<?php

// CommentController zpracovává přidávání, editaci a mazání komentářů.
// Udržuje pravidla přístupu, aby mohl upravit nebo smazat komentář jen autor nebo admin.
class CommentController {
    // Přidá nový komentář k receptu
    public function store($recipeId = null) {
        // Kontrola typu požadavku a existence ID receptu
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !$recipeId) {
            header('Location: ' . BASE_URL . '/index.php');
            exit;
        }

        $this->ensureAuthenticated();

        $content = trim($_POST['content'] ?? '');

        // Kontrola, zda komentář není prázdný
        if (empty($content)) {
            $_SESSION['messages']['error'][] = 'Komentář nemůže být prázdný.';
            header('Location: ' . BASE_URL . '/index.php?url=recipe/show/' . $recipeId);
            exit;
        }

        require_once __DIR__ . '/../models/Comment.php';
        $commentModel = new Comment();
        $commentModel->create((int)$recipeId, $_SESSION['user_id'], htmlspecialchars($content));

        $_SESSION['messages']['success'][] = 'Komentář byl přidán.';
        header('Location: ' . BASE_URL . '/index.php?url=recipe/show/' . $recipeId);
        exit;
    }


    // Zobrazí formulář pro úpravu komentáře
    public function edit($id = null) {
        if (!$id) {
            header('Location: ' . BASE_URL . '/index.php');
            exit;
        }

        $this->ensureAuthenticated();

        require_once __DIR__ . '/../models/Comment.php';
        $commentModel = new Comment();
        $comment = $commentModel->getById((int)$id);

        if (!$comment) {
            $_SESSION['messages']['error'][] = 'Komentář nebyl nalezen.';
            header('Location: ' . BASE_URL . '/index.php');
            exit;
        }

        // Kontrola práv pro úpravu komentáře
        if (!$this->canEditComment($comment)) {
            $_SESSION['messages']['error'][] = 'Nemáte oprávnění upravovat tento komentář.';
            header('Location: ' . BASE_URL . '/index.php?url=recipe/show/' . $comment['recipe_id']);
            exit;
        }

        require_once __DIR__ . '/../views/comments/comment_edit.php';
    }

    // Aktualizuje obsah komentáře
    public function update($id = null) {
        // Kontrola typu požadavku a existence ID komentáře
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !$id) {
            header('Location: ' . BASE_URL . '/index.php');
            exit;
        }

        $this->ensureAuthenticated();

        require_once __DIR__ . '/../models/Comment.php';
        $commentModel = new Comment();
        $comment = $commentModel->getById((int)$id);

        if (!$comment || !$this->canEditComment($comment)) {
            $_SESSION['messages']['error'][] = 'Nemáte oprávnění upravovat tento komentář.';
            header('Location: ' . BASE_URL . '/index.php');
            exit;
        }

        $content = trim($_POST['content'] ?? '');
        if (empty($content)) {
            $_SESSION['messages']['error'][] = 'Komentář nemůže být prázdný.';
            header('Location: ' . BASE_URL . '/index.php?url=comment/edit/' . $id);
            exit;
        }

        $commentModel->update((int)$id, htmlspecialchars($content));
        $_SESSION['messages']['success'][] = 'Komentář byl upraven.';
        header('Location: ' . BASE_URL . '/index.php?url=recipe/show/' . $comment['recipe_id']);
        exit;
    }

    // Smaže komentář
    // Smaže komentář
    public function delete($id = null) {
        if (!$id) {
            header('Location: ' . BASE_URL . '/index.php');
            exit;
        }

        $this->ensureAuthenticated();

        require_once __DIR__ . '/../models/Comment.php';
        $commentModel = new Comment();
        $comment = $commentModel->getById((int)$id);

        if (!$comment || !$this->canDeleteComment($comment)) {
            $_SESSION['messages']['error'][] = 'Nemáte oprávnění mazat tento komentář.';
            header('Location: ' . BASE_URL . '/index.php');
            exit;
        }

        $commentModel->delete((int)$id);
        $_SESSION['messages']['success'][] = 'Komentář byl smazán.';
        header('Location: ' . BASE_URL . '/index.php?url=recipe/show/' . $comment['recipe_id']);
        exit;
    }

    // Zkontroluje, zda je uživatel přihlášený
    protected function ensureAuthenticated() {
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['messages']['notice'][] = 'Přihlaste se pro přidávání komentářů.';
            header('Location: ' . BASE_URL . '/index.php?url=auth/login');
            exit;
        }
    }

    // Zkontroluje, zda může uživatel upravit komentář (autor nebo admin)
    protected function canEditComment(array $comment): bool {
        return isset($_SESSION['user_id']) && ($_SESSION['user_id'] === $comment['user_id'] || $_SESSION['user_role'] === 'admin');
    }

    // Zkontroluje, zda může uživatel smazat komentář (autor nebo admin)
    protected function canDeleteComment(array $comment): bool {
        return isset($_SESSION['user_id']) && ($_SESSION['user_id'] === $comment['user_id'] || $_SESSION['user_role'] === 'admin');
    }
}
