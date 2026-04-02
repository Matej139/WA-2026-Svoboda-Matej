<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail knihy - Knihovna</title>
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

header, nav, main {
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
    max-width: 700px;
}

.book-detail {
    display: grid;
    gap: 16px;
    margin: 30px 0;
}

.detail-row {
    padding: 15px;
    background: rgba(255, 209, 102, 0.05);
    border-left: 3px solid #FFD166;
    border-radius: 6px;
    transition: all 0.3s ease;
}

.detail-row:hover {
    background: rgba(255, 209, 102, 0.1);
    transform: translateX(5px);
}

.detail-row strong {
    color: #FFD166;
    display: inline-block;
    min-width: 120px;
    font-weight: 600;
}

.btn {
    display: inline-block;
    padding: 10px 20px;
    background: linear-gradient(135deg, #EF476F, #FF6B8F);
    color: white;
    text-decoration: none;
    border-radius: 6px;
    font-weight: 600;
    transition: all 0.3s ease;
    margin-top: 20px;
    border: none;
    cursor: pointer;
}

.btn:hover {
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
        <h2><?= $book['title'] ?></h2>

        <div class="book-detail">
            <div class="detail-row"><strong>ID:</strong> <?= $book['id'] ?></div>
            <div class="detail-row"><strong>Autor:</strong> <?= $book['author'] ?></div>
            <div class="detail-row"><strong>Kategorie:</strong> <?= $book['category'] ?></div>
            <div class="detail-row"><strong>Podkategorie:</strong> <?= $book['subcategory'] ?></div>
            <div class="detail-row"><strong>ISBN:</strong> <?= $book['isbn'] ?></div>
            <div class="detail-row"><strong>Rok vydání:</strong> <?= $book['year'] ?></div>
            <div class="detail-row"><strong>Cena:</strong> <?= $book['price'] ?> Kč</div>
            <div class="detail-row"><strong>Popis:</strong><br><?= $book['description'] ?></div>
        </div>

        <a class="btn" href="/WA-2026-Svoboda-Matej/BooksApp/public/index.php">← Zpět na seznam</a>
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
