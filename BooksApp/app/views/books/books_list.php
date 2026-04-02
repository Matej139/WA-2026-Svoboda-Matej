<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
<!-- <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script> -->
    <title>Knihovna - seznam knih</title>
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

/* STARFIELD BACKGROUND */
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

/* ROCKET CONTAINER */
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

header, nav, main, table, .btn {
    z-index: 10;
}

/* NAV */
nav {
    margin-bottom: 20px;
}

nav ul {
    list-style: none;
    padding: 0;
    margin: 0;
    display: flex;
    gap: 10px;
}

nav a {
    color: white;
    margin-right: 0;
    text-decoration: none;
    padding: 10px 16px;
    border-radius: 6px;
    background: rgba(239, 71, 111, 0.85);
    transition: all 0.3s ease;
    font-weight: 600;
}

nav a:hover {
    background: #FFD166;
    color: #0a1428;
    transform: translateY(-2px);
}

/* TABLE CONTAINER */
table {
    width: 100%;
    border-collapse: collapse;
    background: rgba(26, 37, 65, 0.8);
    border-radius: 8px;
    overflow: hidden;
    backdrop-filter: blur(10px);
}

/* HEADER */
th {
    background: linear-gradient(90deg, #EF476F, #FF6B8F);
    color: white;
    text-transform: uppercase;
    font-size: 12px;
    letter-spacing: 1px;
    padding: 14px 12px;
    font-weight: 600;
}

/* CELLS */
td {
    padding: 12px;
    border-bottom: 1px solid rgba(255, 209, 102, 0.1);
    color: #ccc;
}

/* HOVER */
tr:hover {
    background: rgba(255, 209, 102, 0.08);
    transition: background 0.2s ease;
}

/* TITLE highlight */
td:nth-child(2) {
    color: #FFD166;
    font-weight: 600;
}

/* BUTTONS */
.btn {
    padding: 6px 12px;
    border-radius: 5px;
    font-size: 12px;
    text-decoration: none;
    color: white;
    display: inline-block;
    font-weight: 600;
    transition: all 0.3s ease;
    border: none;
    cursor: pointer;
}

.detail {
    background: rgba(255, 209, 102, 0.85);
    color: #0a1428;
}

.detail:hover {
    background: #FFD166;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(255, 209, 102, 0.3);
}

.edit {
    background: rgba(239, 71, 111, 0.85);
}

.edit:hover {
    background: #EF476F;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(239, 71, 111, 0.3);
}

.delete {
    background: rgba(220, 38, 38, 0.85);
}

.delete:hover {
    background: #dc2626;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(220, 38, 38, 0.3);
}

main {
    background: rgba(10, 20, 40, 0.8);
    padding: 25px;
    border-radius: 8px;
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 209, 102, 0.15);
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
        <h2>Dostupné knihy</h2>

        <?php if (!empty($books)): ?>
    <table border="1" cellpadding="10">
        <tr>
    <th>ID</th>
    <th>Název</th>
    <th>Autor</th>
    <th>Kategorie</th>
    <th>Podkategorie</th>
    <th>Rok</th>
    <th>Cena</th>
    <th>ISBN</th>
    <th>Popis</th>
    <th>Obrázky</th>
    <th>Akce</th>
</tr>

        <?php foreach ($books as $book): ?>
            <tr>
    <td><?= $book['id'] ?></td>
    <td><?= $book['title'] ?></td>
    <td><?= $book['author'] ?></td>
    <td><?= $book['category'] ?></td>
    <td><?= $book['subcategory'] ?></td>
    <td><?= $book['year'] ?></td>
    <td><?= $book['price'] ?> Kč</td>
    <td><?= $book['isbn'] ?></td>
    <td><?= $book['description'] ?></td>
    <td><?= $book['images'] ?></td>

    <td>
        <a class="btn detail" href="/WA-2026-Svoboda-Matej/BooksApp/public/index.php?url=book/show/<?= $book['id'] ?>">Detail</a> |
        <a class="btn edit" href="/WA-2026-Svoboda-Matej/BooksApp/public/index.php?url=book/edit/<?= $book['id'] ?>">Upravit</a> | 
        <a class="btn delete" href="/WA-2026-Svoboda-Matej/BooksApp/public/index.php?url=book/delete/<?= $book['id'] ?>" 
           onclick="return confirm('Opravdu chcete tuto knihu smazat?')">
           Smazat
        </a>
    </td>
</tr>
        <?php endforeach; ?>
    </table>
<?php else: ?>
    <p>Žádné knihy v databázi.</p>
<?php endif; ?>
    </main>
    
    <footer>
        <p>&copy; WA 2026 - Výukový projekt</p>
    </footer>

    <!-- ROCKET CONTAINER -->
    <div class="rocket-container" id="rocketContainer"></div>

    <script>
        // Letící rakety v pozadí
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

        // Efekt rozletání knih při kliknutí
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
                
                // Random směry
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
            
            // Klik na stránku = rozletí se knížky
            document.addEventListener('click', function(e) {
                // Není-li kliknutí na tlačítko, vytvoř efekt
                if (!e.target.closest('.btn') && !e.target.closest('input') && !e.target.closest('textarea')) {
                    createBookExplosion(e.clientX, e.clientY);
                }
            });
        });
    </script>
</body>
</html>