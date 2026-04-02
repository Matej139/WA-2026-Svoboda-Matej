<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upravit knihu - Knihovna</title>
  <style>
@keyframes rocketFly {
    0% { transform: translateX(-100px) rotate(45deg); opacity: 0; }
    10% { opacity: 1; }
    90% { opacity: 1; }
    100% { transform: translateX(calc(100vw + 100px)) rotate(45deg); opacity: 0; }
}

@keyframes starTwinkle {
    0%, 100% { opacity: 0.2; }
    50% { opacity: 0.8; }
}

@keyframes bookExplode {
    0% { 
        opacity: 1; 
        transform: translate(0, 0) scale(1) rotate(0deg);
    }
    100% { 
        opacity: 0; 
        transform: translate(var(--tx), var(--ty)) scale(0.3) rotate(360deg);
    }
}

* {
    position: relative;
}

body {
    background: linear-gradient(135deg, #0a1428 0%, #1a2541 50%, #0f172a 100%);
    color: #e5e5e5;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    margin: 0;
    padding: 30px;
    min-height: 100vh;
    overflow-x: hidden;
}

body::before {
    content: '';
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-image: 
        radial-gradient(1px 1px at 20px 30px, #fff, rgba(0,0,0,0)),
        radial-gradient(1px 1px at 60px 70px, #fff, rgba(0,0,0,0)),
        radial-gradient(1px 1px at 50px 50px, #fff, rgba(0,0,0,0)),
        radial-gradient(1px 1px at 130px 80px, #fff, rgba(0,0,0,0)),
        radial-gradient(1px 1px at 90px 10px, #fff, rgba(0,0,0,0));
    background-repeat: repeat;
    background-size: 200px 200px;
    pointer-events: none;
    z-index: 0;
    animation: starTwinkle 4s ease-in-out infinite;
}

.rocket-container {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    pointer-events: none;
    z-index: 1;
    overflow: hidden;
}

.rocket {
    position: absolute;
    font-size: 28px;
    animation: rocketFly linear infinite;
    filter: drop-shadow(0 0 8px rgba(255, 209, 102, 0.6));
}

h1 {
    color: #FFD166;
    margin-bottom: 10px;
    font-size: 2.2em;
    font-weight: 700;
    z-index: 10;
}

h2 {
    color: #fff;
    margin-top: 0;
    font-size: 1.6em;
    z-index: 10;
}

header, nav, main, form, button {
    z-index: 10;
}

header {
    margin-bottom: 30px;
    border-bottom: 2px solid #FFD166;
    padding-bottom: 20px;
}

nav {
    display: flex;
    gap: 10px;
}

nav ul {
    list-style: none;
    padding: 0;
    margin: 0;
    display: flex;
    gap: 10px;
}

nav a {
    text-decoration: none;
    padding: 10px 16px;
    border-radius: 6px;
    background: rgba(239, 71, 111, 0.85);
    color: white;
    font-weight: 600;
    transition: all 0.3s ease;
}

nav a:hover {
    background: #FFD166;
    color: #0a1428;
    transform: translateY(-2px);
}

main {
    background: rgba(10, 20, 40, 0.8);
    padding: 30px;
    border-radius: 8px;
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 209, 102, 0.15);
    max-width: 600px;
}

p {
    color: #aaa;
    margin-top: 0;
}

form div {
    margin-bottom: 20px;
}

label {
    display: block;
    font-weight: 600;
    color: #fff;
    margin-bottom: 8px;
}

label span {
    color: #FFD166;
    font-weight: bold;
}

input[type="text"],
input[type="number"],
input[type="email"],
input[type="file"],
textarea {
    width: 100%;
    padding: 10px 12px;
    border: 1px solid rgba(255, 209, 102, 0.3);
    border-radius: 6px;
    font-size: 14px;
    font-family: inherit;
    background: rgba(255, 255, 255, 0.05);
    color: #fff;
    transition: all 0.3s ease;
    box-sizing: border-box;
}

input[type="text"]:focus,
input[type="number"]:focus,
input[type="email"]:focus,
input[type="file"]:focus,
textarea:focus {
    outline: none;
    border-color: #FFD166;
    background: rgba(255, 255, 255, 0.08);
    box-shadow: 0 0 12px rgba(255, 209, 102, 0.2);
}

textarea {
    resize: vertical;
    font-family: inherit;
}

button {
    background: linear-gradient(135deg, #EF476F, #FF6B8F);
    color: white;
    padding: 12px 30px;
    border: none;
    border-radius: 6px;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

button:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(239, 71, 111, 0.3);
}

footer {
    text-align: center;
    color: #FFD166;
    margin-top: 40px;
    padding-top: 20px;
    border-top: 1px solid rgba(255, 209, 102, 0.2);
    font-size: 0.9em;
}
  </style>
</head>
<body>
    <header>
        <h1>Aplikace Knihovna</h1>
        <nav style="margin-bottom:20px;">
            <ul>
                <li><a href="/WA-2026-Svoboda-Matej/BooksApp/public/index.php">Seznam knih</a></li>
                <li><a href="/WA-2026-Svoboda-Matej/BooksApp/public/index.php?url=book/create">Přidat novou knihu</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <h2>Upravit knihu</h2> 
        <p>Upravte údaje a uložte změny do databáze.</p>

        <form action="/WA-2026-Svoboda-Matej/BooksApp/public/index.php?url=book/update/<?= $book['id'] ?>" method="POST">
            <div>
                <label for="title">Název knihy: <span>*</span></label>
                <input type="text" id="title" name="title" value="<?= $book['title'] ?>">
            </div>
            <div>
                <label for="author">Autor: <span>*</span></label>
                <input type="text" id="author" name="author" value="<?= $book['author'] ?>" required>
            </div>
            <div>
                <label for="category">Kategorie: </label>
                <input type="text" id="category" name="category" value="<?= $book['category'] ?>" required>
            </div>
            <div>
                <label for="subcategory">Podkategorie: </label>
                <input type="text" id="subcategory" name="subcategory" value="<?= $book['subcategory'] ?>" required>
            </div>
            <div>
                <label for="isbn">ISBN: </label>
                <input type="text" id="isbn" name="isbn" value="<?= $book['isbn'] ?>">
            </div>
            <div>
                <label for="year">Rok vydání: <span>*</span></label>
                <input type="number" id="year" name="year" value="<?= $book['year'] ?>" required>
            </div>
             <div>
                <label for="price">Cena: </label>
                <input type="number" id="price" name="price" step="0.5" value="<?= $book['price'] ?>">
            </div>
            <div>
                <label for="description">Popis: </label>
                <textarea id="description" name="description" rows="4"><?= $book['description'] ?></textarea>
            </div>
            <div>
                <label for="images">Obrázek: </label>
                <input type="file" id="image" name="images[]" accept="image/*" value="<?= $book['images'] ?>" multiple>
            </div>
            <div>
                <button type="submit">Uložit</button>
            </div>
        </form>
    </main>

    <footer>
        <p>&copy; WA 2026 - Výukový projekt</p>
    </footer>

    <div class="rocket-container" id="rocketContainer"></div>

    <script>
        function createRockets() {
            const container = document.getElementById('rocketContainer');
            const items = ['🚀', '🛸'];
            
            setInterval(() => {
                const randomItem = items[Math.floor(Math.random() * items.length)];
                const rocket = document.createElement('div');
                rocket.className = 'rocket';
                rocket.textContent = randomItem;
                
                const randomTop = Math.random() * 80 + 10;
                const randomDuration = Math.random() * 3 + 4;
                const randomDelay = Math.random() * 1;
                
                rocket.style.top = randomTop + '%';
                rocket.style.animation = `rocketFly ${randomDuration}s linear ${randomDelay}s 1 forwards`;
                
                container.appendChild(rocket);
                
                setTimeout(() => rocket.remove(), (randomDuration + randomDelay) * 1000);
            }, 3000);
        }

        function createBookExplosion(x, y) {
            for (let i = 0; i < 6; i++) {
                const book = document.createElement('div');
                book.textContent = '📚';
                book.style.position = 'fixed';
                book.style.left = x + 'px';
                book.style.top = y + 'px';
                book.style.fontSize = '20px';
                book.style.pointerEvents = 'none';
                book.style.zIndex = '9999';
                
                const angle = (i / 6) * Math.PI * 2;
                const distance = 150;
                const tx = Math.cos(angle) * distance;
                const ty = Math.sin(angle) * distance;
                
                book.style.setProperty('--tx', tx + 'px');
                book.style.setProperty('--ty', ty + 'px');
                book.style.animation = `bookExplode 0.8s ease-out forwards`;
                
                document.body.appendChild(book);
                setTimeout(() => book.remove(), 800);
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            createRockets();
            
            document.addEventListener('click', function(e) {
                if (!e.target.closest('.btn') && !e.target.closest('input') && !e.target.closest('textarea') && !e.target.closest('button')) {
                    createBookExplosion(e.clientX, e.clientY);
                }
            });
        });
    </script>
</body>
</html>
