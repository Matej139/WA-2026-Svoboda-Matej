<?php require_once __DIR__ . '/../layout/header.php'; ?>

<div class="mx-auto max-w-5xl space-y-8">
    <section class="rounded-3xl border border-[#e3d4c2] bg-white p-8 shadow-sm">
        <div class="grid gap-8 lg:grid-cols-[2fr_1fr]">
            <div class="space-y-4">
                <h2 class="text-4xl font-semibold text-slate-900"><?= htmlspecialchars($recipe['title']) ?></h2>
                <div class="flex flex-wrap gap-3 text-sm text-slate-600">
                    <span class="rounded-full bg-[#fdf1df] px-3 py-1">Kategorie: <?= htmlspecialchars($recipe['category']) ?></span>
                    <span class="rounded-full bg-[#fdf1df] px-3 py-1">Obtížnost: <?= htmlspecialchars($recipe['difficulty']) ?></span>
                    <span class="rounded-full bg-[#fdf1df] px-3 py-1">Čas: <?= htmlspecialchars($recipe['cook_time']) ?> min</span>
                    <span class="rounded-full bg-[#fdf1df] px-3 py-1">Cena: <?= htmlspecialchars($recipe['cost']) ?> Kč</span>
                </div>
                <div class="mt-4 flex flex-wrap items-center gap-3 text-sm text-slate-500">
                    <p>Přidal: <?= htmlspecialchars($recipe['author_name'] ?? 'Host') ?></p>
                    <span class="rounded-full bg-[#fdf1df] px-3 py-1">Hodnocení: <?= number_format($recipe['average_rating'] ?? 0, 1) ?> / 5 (<?= intval($recipe['rating_count'] ?? 0) ?>)</span>
                    <span class="rounded-full bg-[#fdf1df] px-3 py-1">Oblíbené: <?= intval($recipe['favorite_count'] ?? 0) ?></span>
                </div>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <div class="mt-4 flex flex-wrap items-center gap-3">
                        <!-- Tlačítko pro přidání nebo odebrání receptu z oblíbených -->
                        <form action="<?= BASE_URL ?>/index.php?url=recipe/favorite/<?= $recipe['id'] ?>" method="post">
                            <button type="submit" class="inline-flex items-center gap-2 rounded-full <?= $isFavorite ? 'bg-rose-100 text-rose-700 border border-rose-200' : 'bg-[#f6b26b] text-slate-900' ?> px-4 py-2 text-sm font-semibold hover:opacity-90">
                                <?= $isFavorite ? '❤️ Odebrat z oblíbených' : '🤍 Přidat do oblíbených' ?>
                            </button>
                        </form>
                        <?php if ($canRate): ?>
                            <form id="rating-form" action="<?= BASE_URL ?>/index.php?url=recipe/rate/<?= $recipe['id'] ?>" method="post" class="flex flex-wrap items-center gap-2 rounded-3xl border border-[#e3d4c2] bg-[#faf0e3] p-3">
                                <span class="text-sm font-semibold text-slate-700">Moje hodnocení:</span>
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <label class="rating-star cursor-pointer text-xl <?= ($userRating !== null && $i <= $userRating) ? 'text-amber-500' : 'text-slate-300' ?>" data-value="<?= $i ?>">
                                        <input type="radio" name="rating" value="<?= $i ?>" class="hidden" <?= ($userRating === $i) ? 'checked' : '' ?> />
                                        ★
                                    </label>
                                <?php endfor; ?>
                                <button type="submit" class="rounded-full bg-[#f6b26b] px-4 py-2 text-sm font-semibold text-slate-900 hover:bg-[#e69f4a]">Uložit</button>
                            </form>
                        <?php else: ?>
                            <p class="rounded-3xl border border-[#e3d4c2] bg-[#faf0e3] p-4 text-slate-700">Autorem receptu nelze hodnotit vlastní recept.</p>
                        <?php endif; ?>
                    </div>
                <?php else: ?>
                    <p class="mt-4 rounded-3xl border border-[#e3d4c2] bg-[#faf0e3] p-4 text-slate-700">Přihlaste se, abyste mohli recept označit jako oblíbený nebo jej ohodnotit.</p>
                <?php endif; ?>
                <div class="space-y-4">
                    <div>
                        <h3 class="mb-2 text-xl font-semibold text-slate-800">Ingredience</h3>
                        <p class="whitespace-pre-line text-slate-700"><?= htmlspecialchars($recipe['ingredients']) ?></p>
                    </div>
                    <div>
                        <h3 class="mb-2 text-xl font-semibold text-slate-800">Postup</h3>
                        <p class="whitespace-pre-line text-slate-700"><?= htmlspecialchars($recipe['description']) ?></p>
                    </div>
                </div>
            </div>
            <div class="space-y-6">
                <?php $images = !empty($recipe['images']) ? json_decode($recipe['images'], true) : []; ?>
                <?php if (!empty($images)): ?>
                    <div class="grid gap-4">
                        <?php foreach ($images as $image): ?>
                            <img src="<?= BASE_URL ?>/uploads/<?= htmlspecialchars($image) ?>" alt="Obrázek receptu" class="h-64 w-full rounded-3xl object-cover" />
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
                <?php if ($canEditRecipe): ?>
                    <div class="rounded-3xl border border-[#e3d4c2] bg-[#fff8ec] p-5">
                        <h3 class="text-lg font-semibold text-slate-800">Správa receptu</h3>
                        <div class="mt-4 flex flex-col gap-3">
                            <a href="<?= BASE_URL ?>/index.php?url=recipe/edit/<?= $recipe['id'] ?>" class="rounded-full bg-[#f6b26b] px-4 py-3 text-center text-sm font-semibold text-slate-900 hover:bg-[#e69f4a]">Upravit</a>
                            <a href="<?= BASE_URL ?>/index.php?url=recipe/delete/<?= $recipe['id'] ?>" class="rounded-full border border-rose-300 px-4 py-3 text-center text-sm font-semibold text-rose-700 hover:bg-rose-50" onclick="return confirm('Opravdu chcete tento recept smazat?');">Smazat</a>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <section class="rounded-3xl border border-[#e3d4c2] bg-white p-8 shadow-sm">
        <div class="flex items-center justify-between gap-4">
            <h3 class="text-2xl font-semibold text-slate-900">Komentáře</h3>
            <span class="text-sm text-slate-500"><?= count($comments) ?> komentářů</span>
        </div>

        <?php if (isset($_SESSION['user_id'])): ?>
            <form action="<?= BASE_URL ?>/index.php?url=comment/store/<?= $recipe['id'] ?>" method="post" class="space-y-4 rounded-3xl border border-[#e3d4c2] bg-[#faf0e3] p-5">
                <textarea name="content" rows="4" placeholder="Napište komentář..." class="w-full rounded-3xl border border-[#d8cab8] bg-white p-4 outline-none"></textarea>
                <button type="submit" class="rounded-full bg-[#f6b26b] px-6 py-3 text-sm font-semibold text-slate-900 hover:bg-[#e69f4a]">Odeslat komentář</button>
            </form>
        <?php else: ?>
            <p class="rounded-3xl border border-[#e3d4c2] bg-[#faf0e3] p-5 text-slate-700">Přihlaste se a přidejte svůj komentář k tomuto receptu.</p>
        <?php endif; ?>

        <div class="space-y-4 mt-6">
            <?php if (empty($comments)): ?>
                <div class="rounded-3xl border border-[#e3d4c2] bg-[#fff7ed] p-6 text-slate-700">Zatím žádné komentáře. Buďte první!</div>
            <?php endif; ?>

            <?php foreach ($comments as $comment): ?>
                <div class="rounded-3xl border border-[#e3d4c2] bg-[#fffdf7] p-5 shadow-sm">
                    <div class="flex flex-wrap items-center justify-between gap-3 text-sm text-slate-500">
                        <span><strong><?= htmlspecialchars($comment['author_name']) ?></strong></span>
                        <span><?= htmlspecialchars($comment['created_at']) ?></span>
                    </div>
                    <p class="mt-4 text-slate-700 whitespace-pre-line"><?= htmlspecialchars($comment['content']) ?></p>
                    <?php if (isset($_SESSION['user_id']) && ($_SESSION['user_id'] === $comment['user_id'] || ($_SESSION['user_role'] ?? '') === 'admin')): ?>
                        <div class="mt-4 flex flex-wrap gap-3">
                            <a href="<?= BASE_URL ?>/index.php?url=comment/edit/<?= $comment['id'] ?>" class="rounded-full border border-[#d8cab8] px-4 py-2 text-sm text-slate-700 hover:bg-[#faf0e6]">Upravit</a>
                            <a href="<?= BASE_URL ?>/index.php?url=comment/delete/<?= $comment['id'] ?>" class="rounded-full border border-rose-300 px-4 py-2 text-sm text-rose-700 hover:bg-rose-50" onclick="return confirm('Opravdu chcete smazat tento komentář?');">Smazat</a>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </section>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const stars = document.querySelectorAll('.rating-star');

        if (stars.length === 0) {
            return;
        }

        stars.forEach(function(star) {
            star.addEventListener('click', function() {
                const clickedValue = parseInt(star.getAttribute('data-value'), 10);

                stars.forEach(function(otherStar) {
                    const otherValue = parseInt(otherStar.getAttribute('data-value'), 10);
                    if (otherValue <= clickedValue) {
                        otherStar.classList.remove('text-slate-300');
                        otherStar.classList.add('text-amber-500');
                    } else {
                        otherStar.classList.remove('text-amber-500');
                        otherStar.classList.add('text-slate-300');
                    }
                });
            });
        });
    });
</script>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
