<?php require_once '../app/views/layout/header.php'; ?>

<main class="container mx-auto px-6 pb-10 pt-6 flex-grow">
  <div class="max-w-4xl mx-auto">
    <div class="flex justify-between items-end mb-6">
      <h2 class="text-3xl font-light tracking-widest text-slate-400 uppercase">Detail knihy</h2>
      <a href="<?= BASE_URL ?>/index.php" class="bg-slate-600 hover:bg-slate-500 text-slate-200 px-4 py-2 rounded-lg transition-colors">
        ← Zpět na seznam
      </a>
    </div>

    <div class="bg-slate-800/50 border border-slate-700 rounded-xl p-8 shadow-2xl backdrop-blur-sm">
      <div class="mb-8">
        <h3 class="text-4xl font-bold text-slate-200 mb-2"><?= htmlspecialchars($book['title']) ?></h3>
        <p class="text-xl text-slate-400 italic">od <?= htmlspecialchars($book['author']) ?></p>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <div class="space-y-4">
          <div class="bg-slate-700/30 border border-slate-600 rounded-lg p-4">
            <div class="text-sm text-slate-400 uppercase tracking-wider mb-1">ID</div>
            <div class="text-slate-200 font-medium">#<?= htmlspecialchars($book['id']) ?></div>
          </div>

          <div class="bg-slate-700/30 border border-slate-600 rounded-lg p-4">
            <div class="text-sm text-slate-400 uppercase tracking-wider mb-1">Kategorie</div>
            <div class="text-slate-200 font-medium"><?= htmlspecialchars($book['category']) ?></div>
          </div>

          <div class="bg-slate-700/30 border border-slate-600 rounded-lg p-4">
            <div class="text-sm text-slate-400 uppercase tracking-wider mb-1">Podkategorie</div>
            <div class="text-slate-200 font-medium"><?= htmlspecialchars($book['subcategory']) ?></div>
          </div>

          <div class="bg-slate-700/30 border border-slate-600 rounded-lg p-4">
            <div class="text-sm text-slate-400 uppercase tracking-wider mb-1">ISBN</div>
            <div class="text-slate-200 font-medium"><?= htmlspecialchars($book['isbn']) ?: 'Neuvedeno' ?></div>
          </div>
        </div>

        <div class="space-y-4">
          <div class="bg-slate-700/30 border border-slate-600 rounded-lg p-4">
            <div class="text-sm text-slate-400 uppercase tracking-wider mb-1">Rok vydání</div>
            <div class="text-slate-200 font-medium"><?= htmlspecialchars($book['year']) ?></div>
          </div>

          <div class="bg-slate-700/30 border border-slate-600 rounded-lg p-4">
            <div class="text-sm text-slate-400 uppercase tracking-wider mb-1">Cena</div>
            <div class="text-slate-200 font-medium"><?= htmlspecialchars($book['price']) ?> Kč</div>
          </div>

          <div class="bg-slate-700/30 border border-slate-600 rounded-lg p-4 md:col-span-2">
            <div class="text-sm text-slate-400 uppercase tracking-wider mb-2">Popis</div>
            <div class="text-slate-200 leading-relaxed">
              <?= nl2br(htmlspecialchars($book['description'])) ?: 'Bez popisu' ?>
            </div>
          </div>
        </div>
      </div>

      <?php if (!empty($book['images'])): ?>
        <div class="mt-8">
          <h3 class="text-xl font-semibold text-slate-200 mb-4">Obrázky knihy</h3>
          <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
            <?php 
            $bookImages = !empty($book['images']) ? json_decode($book['images'], true) : [];
            foreach ($bookImages as $image): 
              if (!empty($image)):
            ?>
              <div class="relative group">
                <img src="<?= BASE_URL ?>/uploads/<?= htmlspecialchars($image) ?>" 
                     alt="Obrázek knihy" 
                     class="w-full h-32 object-cover rounded-lg border border-slate-600 hover:border-slate-500 transition-colors">
              </div>
            <?php 
              endif;
            endforeach; 
            ?>
          </div>
        </div>
      <?php endif; ?>

      <?php if (isset($_SESSION['user_id']) && isset($book['created_by']) && $_SESSION['user_id'] === $book['created_by']): ?>
          <div class="mt-8 flex gap-4">
            <a href="<?= BASE_URL ?>/index.php?url=book/edit/<?= $book['id'] ?>" class="bg-amber-600 hover:bg-amber-500 text-white px-6 py-3 rounded-lg transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
              ✏️ Upravit knihu
            </a>
            <a href="<?= BASE_URL ?>/index.php?url=book/delete/<?= $book['id'] ?>" class="bg-red-600 hover:bg-red-500 text-white px-6 py-3 rounded-lg transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-0.5" onclick="return confirm('Opravdu chcete tuto knihu smazat?')">
              🗑️ Smazat knihu
            </a>
          </div>
      <?php endif; ?>
    </div>
  </div>
</main>

<?php require_once '../app/views/layout/footer.php'; ?>
