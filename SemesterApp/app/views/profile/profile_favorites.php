<?php require_once __DIR__ . '/../layout/header.php'; ?>

<div class="mx-auto max-w-5xl space-y-8">
    <!-- Výpis oblíbených receptů pro uživatele, kteří je označili jako hvězdičky/srdce -->
    <section class="rounded-3xl border border-[#e3d4c2] bg-white p-8 shadow-sm">
        <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
            <div>
                <h2 class="text-4xl font-semibold text-slate-900">Moje oblíbené recepty</h2>
                <p class="mt-2 text-slate-600">Tady najdete recepty, které jste si označili jako oblíbené.</p>
            </div>
            <a href="<?= BASE_URL ?>/index.php" class="inline-flex items-center justify-center rounded-full bg-[#f6b26b] px-6 py-3 text-sm font-semibold text-white shadow-sm hover:bg-[#e69f4a]">Zpět na domov</a>
        </div>
    </section>

    <?php if (empty($favoriteRecipes)): ?>
        <div class="rounded-3xl border border-[#e3d4c2] bg-[#fff7ed] p-8 text-center text-slate-700 shadow-sm">
            <p class="text-xl">Ještě nemáte žádné oblíbené recepty. Označte nějaký srdíčkem a bude se zde zobrazovat.</p>
        </div>
    <?php else: ?>
        <div class="grid gap-6 lg:grid-cols-2">
            <?php foreach ($favoriteRecipes as $recipe): ?>
                <article class="relative rounded-3xl border border-[#e3d4c2] bg-white p-6 shadow-sm hover:shadow-md transition-shadow">
                    <div class="space-y-3">
                        <?php $images = !empty($recipe['images']) ? json_decode($recipe['images'], true) : []; ?>
                        <?php if (!empty($images)): ?>
                            <img src="<?= BASE_URL ?>/uploads/<?= htmlspecialchars($images[0]) ?>" alt="<?= htmlspecialchars($recipe['title']) ?>" class="h-60 w-full rounded-3xl object-cover" />
                        <?php endif; ?>
                        <div class="flex items-center justify-between gap-3">
                            <h3 class="text-2xl font-semibold text-slate-900"><?= htmlspecialchars($recipe['title']) ?></h3>
                            <span class="rounded-full bg-rose-100 px-3 py-1 text-sm font-semibold text-rose-700">❤️ Oblíbené</span>
                        </div>
                        <p class="text-sm uppercase text-amber-700"><?= htmlspecialchars($recipe['category']) ?> • <?= htmlspecialchars($recipe['difficulty']) ?></p>
                        <p class="text-slate-600 line-clamp-3"><?= htmlspecialchars($recipe['description']) ?></p>
                        <div class="flex flex-wrap items-center justify-between gap-3 text-sm text-slate-500">
                            <span>Autor: <?= htmlspecialchars($recipe['author_name'] ?? 'Host') ?></span>
                            <span>Čas: <?= htmlspecialchars($recipe['cook_time']) ?> min</span>
                        </div>
                        <div class="flex flex-wrap gap-3 pt-4">
                            <a href="<?= BASE_URL ?>/index.php?url=recipe/show/<?= $recipe['id'] ?>" class="rounded-full bg-[#f3e0c7] px-4 py-2 text-sm font-semibold text-slate-800 hover:bg-[#e2c19e]">Zobrazit</a>
                            <a href="<?= BASE_URL ?>/index.php?url=recipe/favorite/<?= $recipe['id'] ?>" class="rounded-full border border-rose-300 px-4 py-2 text-sm text-rose-700 hover:bg-rose-50">Odebrat z oblíbených</a>
                        </div>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>