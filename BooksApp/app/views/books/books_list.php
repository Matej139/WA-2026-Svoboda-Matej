<?php require_once '../app/views/layout/header.php'; ?>

<main class="container mx-auto px-6 pb-10 pt-6 flex-grow">
  <div class="flex justify-between items-end mb-6">
      <h2 class="text-3xl font-light tracking-widest text-slate-400 uppercase">Dostupné knihy</h2>
  </div>

  <div class="bg-slate-800/50 border border-slate-700 rounded-xl overflow-hidden shadow-2xl backdrop-blur-sm">
      <?php if (empty($books)): ?>
          <div class="p-10 text-center text-slate-500 italic">
              V databázi se zatím nenachází žádné knihy.
          </div>
      <?php else: ?>
          <div class="overflow-x-auto">
              <table class="w-full text-left border-collapse">
                <thead class="bg-slate-700/50">
                  <tr>
                    <th class="px-6 py-4 text-slate-300 font-semibold uppercase text-sm tracking-wider">ID</th>
                    <th class="px-6 py-4 text-slate-300 font-semibold uppercase text-sm tracking-wider">Název</th>
                    <th class="px-6 py-4 text-slate-300 font-semibold uppercase text-sm tracking-wider">Autor</th>
                    <th class="px-6 py-4 text-slate-300 font-semibold uppercase text-sm tracking-wider">Kategorie</th>
                    <th class="px-6 py-4 text-slate-300 font-semibold uppercase text-sm tracking-wider">Podkategorie</th>
                    <th class="px-6 py-4 text-slate-300 font-semibold uppercase text-sm tracking-wider">Rok</th>
                    <th class="px-6 py-4 text-slate-300 font-semibold uppercase text-sm tracking-wider">Cena</th>
                    <th class="px-6 py-4 text-slate-300 font-semibold uppercase text-sm tracking-wider">ISBN</th>
                    <th class="px-6 py-4 text-slate-300 font-semibold uppercase text-sm tracking-wider">Popis</th>
                    <th class="px-6 py-4 text-slate-300 font-semibold uppercase text-sm tracking-wider">Obrázky</th>
                    <th class="px-6 py-4 text-slate-300 font-semibold uppercase text-sm tracking-wider">Akce</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($books as $book): ?>
                    <tr class="border-b border-slate-700/50 hover:bg-slate-700/30 transition-colors">
                      <td class="px-6 py-4 text-slate-400"><?= $book['id'] ?></td>
                      <td class="px-6 py-4 text-slate-200 font-medium"><?= $book['title'] ?></td>
                      <td class="px-6 py-4 text-slate-300"><?= $book['author'] ?></td>
                      <td class="px-6 py-4 text-emerald-400 font-medium"><?= htmlspecialchars($book['category_name'] ?? 'Nezařazeno') ?></td>
                      <td class="px-6 py-4 text-slate-300"><?= htmlspecialchars($book['subcategory_name'] ?? 'Nezařazeno') ?></td>
                      <td class="px-6 py-4 text-slate-300"><?= htmlspecialchars($book['year']) ?></td>
                      <td class="px-6 py-4 text-slate-300"><?= $book['price'] ?> Kč</td>
                      <td class="px-6 py-4 text-slate-300"><?= $book['isbn'] ?></td>
                      <td class="px-6 py-4 text-slate-300 max-w-xs truncate"><?= $book['description'] ?></td>
                      <td class="px-6 py-4 text-slate-300">
                        <?php $bookImages = !empty($book['images']) ? json_decode($book['images'], true) : []; ?>
                        <?php if (!empty($bookImages)): ?>
                          <div class="flex flex-wrap gap-1">
                            <?php foreach (array_slice($bookImages, 0, 4) as $image): ?>
                              <?php if (!empty($image)): ?>
                                <img src="<?= BASE_URL ?>/uploads/<?= htmlspecialchars($image) ?>" alt="Obrázek" class="h-12 w-12 object-cover rounded border border-slate-700" />
                              <?php endif; ?>
                            <?php endforeach; ?>
                            <?php if (count($bookImages) > 4): ?>
                              <span class="text-xs text-slate-400 self-center">+<?= count($bookImages) - 4 ?> další</span>
                            <?php endif; ?>
                          </div>
                        <?php else: ?>
                          <span class="text-xs text-slate-500 italic">Žádné obrázky</span>
                        <?php endif; ?>
                      </td>
                      <td class="px-6 py-4">
                        <div class="flex flex-wrap gap-2">
                          <a href="<?= BASE_URL ?>/index.php?url=book/show/<?= $book['id'] ?>" class="bg-blue-600 hover:bg-blue-500 text-white px-3 py-1 rounded text-sm transition-colors">Detail</a>
                          <?php 
                            $isAdmin = isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1;
                            $bookOwnerId = isset($book['created_by']) ? (int)$book['created_by'] : null;
                            $currentUserId = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : null;
                            $isAdminOtherBook = $isAdmin && $currentUserId !== $bookOwnerId;
                            $editClass = $isAdminOtherBook ? 'bg-sky-600 hover:bg-sky-500' : 'bg-amber-600 hover:bg-amber-500';
                            $deleteClass = $isAdminOtherBook ? 'bg-rose-500 hover:bg-rose-400' : 'bg-red-600 hover:bg-red-500';
                          ?>
                          <?php if ($currentUserId !== null && ($currentUserId === $bookOwnerId || $isAdmin)): ?>
                              <a href="<?= BASE_URL ?>/index.php?url=book/edit/<?= $book['id'] ?>" class="<?= $editClass ?> text-white px-3 py-1 rounded text-sm transition-colors">
                                  Upravit<?= $isAdminOtherBook ? ' (admin)' : '' ?>
                              </a>
                              <a href="<?= BASE_URL ?>/index.php?url=book/delete/<?= $book['id'] ?>" class="<?= $deleteClass ?> text-white px-3 py-1 rounded text-sm transition-colors" onclick="return confirm('Opravdu chcete tuto knihu smazat?')">
                                  Smazat<?= $isAdminOtherBook ? ' (admin)' : '' ?>
                              </a>
                          <?php endif; ?>
                        </div>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
          </div>
      <?php endif; ?>
  </div>
</main>

<?php require_once '../app/views/layout/footer.php'; ?>