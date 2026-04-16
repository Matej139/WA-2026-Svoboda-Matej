<?php require_once '../app/views/layout/header.php'; ?>

<main class="container mx-auto px-6 pb-10 pt-6 flex-grow">
  <div class="max-w-2xl mx-auto">
    <div class="flex justify-between items-end mb-6">
      <h2 class="text-3xl font-light tracking-widest text-slate-400 uppercase">Přidat novou knihu</h2>
    </div>

    <div class="bg-slate-800/50 border border-slate-700 rounded-xl p-8 shadow-2xl backdrop-blur-sm">
      <p class="text-slate-400 mb-6 italic">Vyplňte údaje a uložte knihu do databáze.</p>

      <form action="<?= BASE_URL ?>/index.php?url=book/store" method="POST" enctype="multipart/form-data" class="space-y-6">
        <div>
          <label for="title" class="block text-sm font-medium text-slate-300 mb-2">
            Název knihy <span class="text-red-400">*</span>
          </label>
          <input type="text" id="title" name="title" required
                 class="w-full px-4 py-3 bg-slate-700/50 border border-slate-600 rounded-lg text-slate-200 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
        </div>

        <div>
          <label for="author" class="block text-sm font-medium text-slate-300 mb-2">
            Autor <span class="text-red-400">*</span>
          </label>
          <input type="text" id="author" name="author" required
                 class="w-full px-4 py-3 bg-slate-700/50 border border-slate-600 rounded-lg text-slate-200 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div>
            <label for="category" class="block text-sm font-medium text-slate-300 mb-2">
              Kategorie <span class="text-red-400">*</span>
            </label>
            <input type="text" id="category" name="category" required
                   class="w-full px-4 py-3 bg-slate-700/50 border border-slate-600 rounded-lg text-slate-200 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
          </div>

          <div>
            <label for="subcategory" class="block text-sm font-medium text-slate-300 mb-2">
              Podkategorie <span class="text-red-400">*</span>
            </label>
            <input type="text" id="subcategory" name="subcategory" required
                   class="w-full px-4 py-3 bg-slate-700/50 border border-slate-600 rounded-lg text-slate-200 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
          </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div>
            <label for="isbn" class="block text-sm font-medium text-slate-300 mb-2">
              ISBN
            </label>
            <input type="text" id="isbn" name="isbn"
                   class="w-full px-4 py-3 bg-slate-700/50 border border-slate-600 rounded-lg text-slate-200 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
          </div>

          <div>
            <label for="year" class="block text-sm font-medium text-slate-300 mb-2">
              Rok vydání <span class="text-red-400">*</span>
            </label>
            <input type="number" id="year" name="year" required
                   class="w-full px-4 py-3 bg-slate-700/50 border border-slate-600 rounded-lg text-slate-200 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
          </div>
        </div>

        <div>
          <label for="price" class="block text-sm font-medium text-slate-300 mb-2">
            Cena (Kč)
          </label>
          <input type="number" id="price" name="price" step="0.01"
                 class="w-full px-4 py-3 bg-slate-700/50 border border-slate-600 rounded-lg text-slate-200 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
        </div>

        <div>
          <label for="description" class="block text-sm font-medium text-slate-300 mb-2">
            Popis
          </label>
          <textarea id="description" name="description" rows="4"
                    class="w-full px-4 py-3 bg-slate-700/50 border border-slate-600 rounded-lg text-slate-200 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all resize-vertical"></textarea>
        </div>

        <div class="md:col-span-2">
          <label class="block text-xs font-semibold text-slate-400 mb-2 uppercase tracking-wider">Obrázky knihy</label>
          <div class="w-full">
            <label for="images" class="flex flex-col items-center justify-center w-full h-24 border-2 border-slate-600 border-dashed rounded-lg cursor-pointer bg-slate-800/30 hover:bg-slate-700/50 hover:border-blue-400 transition-colors">
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
            Uložit knihu
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
      fileTitle.className = 'text-sm text-blue-400 font-bold';
      fileInfo.textContent = files[0].name;
    } else {
      // Vybráno více souborů - ukážeme počet
      fileTitle.textContent = 'Soubory připraveny';
      fileTitle.className = 'text-sm text-blue-400 font-bold';
      fileInfo.textContent = 'Vybráno celkem: ' + files.length + ' souborů';
    }
  });
</script>

<?php require_once '../app/views/layout/footer.php'; ?>
