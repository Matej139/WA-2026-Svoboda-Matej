<?php require_once '../app/views/layout/header.php'; ?>

<main class="container mx-auto px-6 pb-10 pt-6 flex-grow">
  <div class="max-w-2xl mx-auto">
    <div class="flex justify-between items-end mb-6">
      <h2 class="text-3xl font-light tracking-widest text-slate-400 uppercase">Upravit knihu</h2>
    </div>

    <div class="bg-slate-800/50 border border-slate-700 rounded-xl p-8 shadow-2xl backdrop-blur-sm">
      <p class="text-slate-400 mb-6 italic">Upravte údaje a uložte změny do databáze.</p>

      <form action="<?= BASE_URL ?>/index.php?url=book/update/<?= $book['id'] ?>" method="POST" enctype="multipart/form-data" class="space-y-6">
        <div>
          <label for="title" class="block text-sm font-medium text-slate-300 mb-2">
            Název knihy <span class="text-red-400">*</span>
          </label>
          <input type="text" id="title" name="title" value="<?= htmlspecialchars($book['title']) ?>" required
                 class="w-full px-4 py-3 bg-slate-700/50 border border-slate-600 rounded-lg text-slate-200 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
        </div>

        <div>
          <label for="author" class="block text-sm font-medium text-slate-300 mb-2">
            Autor <span class="text-red-400">*</span>
          </label>
          <input type="text" id="author" name="author" value="<?= htmlspecialchars($book['author']) ?>" required
                 class="w-full px-4 py-3 bg-slate-700/50 border border-slate-600 rounded-lg text-slate-200 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div>
            <label for="category" class="block text-sm font-medium text-slate-300 mb-2">
              Kategorie <span class="text-red-400">*</span>
            </label>
            <input type="text" id="category" name="category" value="<?= htmlspecialchars($book['category']) ?>" required
                   class="w-full px-4 py-3 bg-slate-700/50 border border-slate-600 rounded-lg text-slate-200 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
          </div>

          <div>
            <label for="subcategory" class="block text-sm font-medium text-slate-300 mb-2">
              Podkategorie <span class="text-red-400">*</span>
            </label>
            <input type="text" id="subcategory" name="subcategory" value="<?= htmlspecialchars($book['subcategory']) ?>" required
                   class="w-full px-4 py-3 bg-slate-700/50 border border-slate-600 rounded-lg text-slate-200 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
          </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div>
            <label for="isbn" class="block text-sm font-medium text-slate-300 mb-2">
              ISBN
            </label>
            <input type="text" id="isbn" name="isbn" value="<?= htmlspecialchars($book['isbn']) ?>"
                   class="w-full px-4 py-3 bg-slate-700/50 border border-slate-600 rounded-lg text-slate-200 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
          </div>

          <div>
            <label for="year" class="block text-sm font-medium text-slate-300 mb-2">
              Rok vydání <span class="text-red-400">*</span>
            </label>
            <input type="number" id="year" name="year" value="<?= htmlspecialchars($book['year']) ?>" required
                   class="w-full px-4 py-3 bg-slate-700/50 border border-slate-600 rounded-lg text-slate-200 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
          </div>
        </div>

        <div>
          <label for="price" class="block text-sm font-medium text-slate-300 mb-2">
            Cena (Kč)
          </label>
          <input type="number" id="price" name="price" step="0.01" value="<?= htmlspecialchars($book['price']) ?>"
                 class="w-full px-4 py-3 bg-slate-700/50 border border-slate-600 rounded-lg text-slate-200 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
        </div>

        <div>
          <label for="description" class="block text-sm font-medium text-slate-300 mb-2">
            Popis
          </label>
          <textarea id="description" name="description" rows="4"
                    class="w-full px-4 py-3 bg-slate-700/50 border border-slate-600 rounded-lg text-slate-200 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all resize-vertical"><?= htmlspecialchars($book['description']) ?></textarea>
        </div>

        <div class="md:col-span-2">
          <label class="block text-xs font-semibold text-slate-400 mb-2 uppercase tracking-wider">Obrázky knihy</label>
          
          <?php if (!empty($book['images'])): ?>
            <div class="mb-4 p-3 bg-amber-900/20 border border-amber-700/30 rounded-lg">
              <p class="text-sm text-slate-300 mb-2 font-medium">Stávající obrázky:</p>
              <div class="flex flex-wrap gap-2">
                <?php 
                $existingImages = !empty($book['images']) ? json_decode($book['images'], true) : [];
                foreach ($existingImages as $image): 
                  if (!empty($image)):
                ?>
                  <div class="relative group">
                    <img src="<?= BASE_URL ?>/uploads/<?= htmlspecialchars($image) ?>" 
                         alt="Obrázek knihy" 
                         class="w-20 h-20 object-cover rounded-lg border border-slate-600">
                    <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity rounded-lg flex items-center justify-center">
                      <span class="text-xs text-white">Stávající</span>
                    </div>
                    <span class="text-xs text-slate-400 absolute -bottom-6 left-0 w-full text-center truncate"><?= htmlspecialchars($image) ?></span>
                  </div>
                <?php 
                  endif;
                endforeach; 
                ?>
              </div>
              <p class="text-xs text-amber-300 mt-3 pt-3 border-t border-amber-700/30"><strong>⚠️ Upozornění:</strong> Pokud nyní nahrajete nové soubory, tyto staré obrázky budou smazány a nahrazeny novými.</p>
              </div>
            </div>
          <?php endif; ?>
          
          <div class="w-full">
            <label for="images" class="flex flex-col items-center justify-center w-full h-24 border-2 border-slate-600 border-dashed rounded-lg cursor-pointer bg-slate-800/30 hover:bg-slate-700/50 hover:border-emerald-400 transition-colors">
              <div class="flex flex-col items-center justify-center pt-5 pb-6">
                <span id="file-title" class="text-sm text-slate-400 font-semibold">Klikni pro výběr souborů</span>
                <span id="file-info" class="text-xs text-slate-500 mt-1 text-center px-4">Žádné soubory nebyly vybrány</span>
              </div>
              <input type="file" id="images" name="images[]" multiple accept="image/*" class="hidden">
            </label>
          </div>
        </div>

        <div class="pt-4">
          <button type="submit"
                  class="w-full bg-gradient-to-r from-blue-600 to-blue-500 hover:from-blue-500 hover:to-blue-400 text-white font-semibold py-3 px-6 rounded-lg transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
            Uložit změny
          </button>
        </div>
      </form>
    </div>
  </div>
</main>

<script>
  // Najdeme naše HTML prvky podle ID
  const fileInput = document.getElementById('images');
  const fileTitle = document.getElementById('file-title');
  const fileInfo = document.getElementById('file-info');

  // Posloucháme událost 'change' (změna hodnoty v inputu)
  fileInput.addEventListener('change', function(event) {
    const files = event.target.files;
    
    if (files.length === 0) {
      // Uživatel výběr zrušil
      fileTitle.textContent = 'Klikněte pro výběr souborů';
      fileTitle.className = 'text-sm text-slate-400 font-semibold';
      fileInfo.textContent = 'Žádné soubory nebyly vybrány';
    } else if (files.length === 1) {
      // Vybrán 1 soubor - ukážeme jeho název
      fileTitle.textContent = 'Soubor připraven';
      fileTitle.className = 'text-sm text-emerald-400 font-bold';
      fileInfo.textContent = files[0].name;
    } else {
      // Vybráno více souborů - ukážeme počet
      fileTitle.textContent = 'Soubory připraveny';
      fileTitle.className = 'text-sm text-emerald-400 font-bold';
      fileInfo.textContent = 'Vybráno celkem: ' + files.length + ' souborů';
    }
  });
</script>

<?php require_once '../app/views/layout/footer.php'; ?>
