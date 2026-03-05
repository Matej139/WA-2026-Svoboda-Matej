<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script> -->
    <title>Document</title>
</head>
<body>
    <div>
        <h2>Přidat novou knihu</h2> 
        <p>Vyplňte údaje a uložte knihu do databáze.</p>
    </div>

    <div>
        <form action="">
            <div>
                <label for="title">Název knihy: <span>*</span></label>
                <input type="text" id="title" name="title" required>
            </div>
            <div>
                <label for="author">Autor: <span>*</span></label>
                <input type="text" id="author" name="author" required>
            </div>
            <div>
                <label for="category">Kategorie: </label>
                <input type="text" id="category" name="category" required>
            </div>
            <div>
                <label for="subcategory">Podkategorie: </label>
                <input type="text" id="subcategory" name="subcategory" required>
            </div>
            <div>
                <label for="year">Rok vydání: <span>*</span></label>
                <input type="number" id="year" name="year" required>
            </div>
            <div>
                <button type="submit">Uložit</button>
            </div>

        </form>
    </div>
    
</body>
</html>