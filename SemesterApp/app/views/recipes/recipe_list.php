<?php require_once __DIR__ . '/../layout/header.php'; ?>

<section class="mb-8 rounded-3xl border border-[#e3d4c2] bg-white p-8 shadow-sm">
    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <div>
            <h2 class="text-4xl font-semibold text-slate-900">Vítejte v RecipeJoy</h2>
            <p class="mt-2 text-slate-600">Objevujte chutné recepty, přidávejte vlastní nápady a sdílejte komentáře.</p>
        </div>
        <?php if (isset($_SESSION['user_id'])): ?>
            <a href="<?= BASE_URL ?>/index.php?url=recipe/create" class="inline-flex items-center justify-center rounded-full bg-[#f6b26b] px-6 py-3 text-sm font-semibold text-white shadow-sm hover:bg-[#e69f4a]">Přidat nový recept</a>
        <?php endif; ?>
    </div>
</section>

<?php if (empty($recipes)): ?>
    <div class="rounded-3xl border border-[#e3d4c2] bg-[#fff7ed] p-8 text-center text-slate-700 shadow-sm">
        <p class="text-xl">Zatím tady nejsou žádné recepty. Buď první a přidej svůj nápad!</p>
    </div>
<?php else: ?>
    <div class="grid gap-6 lg:grid-cols-2">
        <?php foreach ($recipes as $recipe): ?>
            <?php $isListFavorite = in_array($recipe['id'], $favoriteRecipeIds ?? [], false); ?>
            <!-- Karta receptu může být zvýrazněná, pokud ji má uživatel ve svých oblíbených -->
            <article class="relative rounded-3xl border p-6 shadow-sm transition-shadow <?= $isListFavorite ? 'border-amber-300 bg-[#fff4e1] hover:shadow-md' : 'border-[#e3d4c2] bg-white hover:shadow-md' ?>">
                <?php if ($isListFavorite): ?>
                    <div class="absolute right-4 top-4 rounded-full bg-rose-100 px-3 py-1 text-sm font-semibold text-rose-700 shadow-sm">❤️ Oblíbené</div>
                <?php endif; ?>
                <div class="space-y-3">
                    <?php $images = !empty($recipe['images']) ? json_decode($recipe['images'], true) : []; ?>
                    <?php if (!empty($images)): ?>
                        <img src="<?= BASE_URL ?>/uploads/<?= htmlspecialchars($images[0]) ?>" alt="<?= htmlspecialchars($recipe['title']) ?>" class="h-60 w-full rounded-3xl object-cover" />
                    <?php endif; ?>
                    <h3 class="text-2xl font-semibold text-slate-900"><?= htmlspecialchars($recipe['title']) ?></h3>
                    <p class="text-sm uppercase text-amber-700"><?= htmlspecialchars($recipe['category']) ?> • <?= htmlspecialchars($recipe['difficulty']) ?></p>
                    <p class="text-slate-600 line-clamp-3"><?= htmlspecialchars($recipe['description']) ?></p>
                    <div class="mt-3 flex flex-wrap gap-2 text-sm text-slate-500">
                        <span>Hodnocení: <?= number_format($recipe['average_rating'] ?? 0, 1) ?> / 5</span>
                        <span>|</span>
                        <span>Oblíbené: <?= intval($recipe['favorite_count'] ?? 0) ?></span>
                    </div>
                    <div class="flex flex-wrap items-center justify-between gap-3 text-sm text-slate-500">
                        <span>Autor: <?= htmlspecialchars($recipe['author_name'] ?? 'Host') ?></span>
                        <span>Čas: <?= htmlspecialchars($recipe['cook_time']) ?> min</span>
                    </div>
                    <div class="mt-2 flex flex-wrap items-center gap-3 text-sm text-slate-500">
                        <span>⭐ <?= number_format($recipe['average_rating'] ?? 0, 1) ?> / 5</span>
                        <span>❤️ <?= intval($recipe['favorite_count'] ?? 0) ?></span>
                    </div>
                    <div class="flex flex-wrap gap-3 pt-4">
                        <a href="<?= BASE_URL ?>/index.php?url=recipe/show/<?= $recipe['id'] ?>" class="rounded-full bg-[#f3e0c7] px-4 py-2 text-sm font-semibold text-slate-800 hover:bg-[#e2c19e]">Zobrazit</a>
                        <?php if (isset($_SESSION['user_id']) && ($_SESSION['user_id'] === $recipe['created_by'] || ($_SESSION['user_role'] ?? '') === 'admin')): ?>
                            <a href="<?= BASE_URL ?>/index.php?url=recipe/edit/<?= $recipe['id'] ?>" class="rounded-full border border-[#d8cab8] px-4 py-2 text-sm text-slate-700 hover:bg-[#faf0e6]">Upravit</a>
                            <a href="<?= BASE_URL ?>/index.php?url=recipe/delete/<?= $recipe['id'] ?>" class="rounded-full border border-rose-300 px-4 py-2 text-sm text-rose-700 hover:bg-rose-50" onclick="return confirm('Opravdu chcete recept smazat?');">Smazat</a>
                        <?php endif; ?>
                    </div>
                </div>
            </article>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
