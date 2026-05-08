<?php require_once __DIR__ . '/../layout/header.php'; ?>

<div class="mx-auto max-w-4xl rounded-3xl border border-[#e3d4c2] bg-white p-8 shadow-sm">
    <h2 class="mb-6 text-3xl font-semibold text-slate-900">Upravit recept</h2>

    <form action="<?= BASE_URL ?>/index.php?url=recipe/update/<?= $recipe['id'] ?>" method="post" enctype="multipart/form-data" class="space-y-6">
        <div class="grid gap-4 lg:grid-cols-2">
            <label class="block">
                <span class="text-slate-700">Název receptu</span>
                <input type="text" name="title" required value="<?= htmlspecialchars($recipe['title']) ?>" class="mt-2 w-full rounded-2xl border border-[#d8cab8] bg-[#faf0f0] p-3 outline-none focus:border-amber-400" />
            </label>
            <label class="block">
                <span class="text-slate-700">Kategorie</span>
                <select name="category" required class="mt-2 w-full rounded-2xl border border-[#d8cab8] bg-[#faf0f0] p-3 outline-none focus:border-amber-400">
                    <?php foreach ($categories as $category): ?>
                        <option value="<?= htmlspecialchars($category) ?>" <?= $recipe['category'] === $category ? 'selected' : '' ?>><?= htmlspecialchars($category) ?></option>
                    <?php endforeach; ?>
                </select>
            </label>
        </div>

        <div class="grid gap-4 lg:grid-cols-3">
            <label class="block">
                <span class="text-slate-700">Obtížnost</span>
                <select name="difficulty" required class="mt-2 w-full rounded-2xl border border-[#d8cab8] bg-[#faf0f0] p-3 outline-none focus:border-amber-400">
                    <?php foreach ($difficulties as $difficulty): ?>
                        <option value="<?= htmlspecialchars($difficulty) ?>" <?= $recipe['difficulty'] === $difficulty ? 'selected' : '' ?>><?= htmlspecialchars($difficulty) ?></option>
                    <?php endforeach; ?>
                </select>
            </label>
            <label class="block">
                <span class="text-slate-700">Doba přípravy (min)</span>
                <input type="number" name="cook_time" min="1" value="<?= htmlspecialchars($recipe['cook_time']) ?>" class="mt-2 w-full rounded-2xl border border-[#d8cab8] bg-[#faf0f0] p-3 outline-none focus:border-amber-400" />
            </label>
            <label class="block">
                <span class="text-slate-700">Cena (CZK)</span>
                <input type="number" step="0.01" name="cost" min="0" value="<?= htmlspecialchars($recipe['cost']) ?>" class="mt-2 w-full rounded-2xl border border-[#d8cab8] bg-[#faf0f0] p-3 outline-none focus:border-amber-400" />
            </label>
        </div>

        <label class="block">
            <span class="text-slate-700">Ingredience</span>
            <textarea name="ingredients" rows="4" required class="mt-2 w-full rounded-2xl border border-[#d8cab8] bg-[#faf0f0] p-3 outline-none focus:border-amber-400"><?= htmlspecialchars($recipe['ingredients']) ?></textarea>
        </label>

        <label class="block">
            <span class="text-slate-700">Postup / popis</span>
            <textarea name="description" rows="6" required class="mt-2 w-full rounded-2xl border border-[#d8cab8] bg-[#faf0f0] p-3 outline-none focus:border-amber-400"><?= htmlspecialchars($recipe['description']) ?></textarea>
        </label>

        <div class="space-y-3">
            <p class="text-slate-700">Aktuální obrázky</p>
            <?php $existingImages = !empty($recipe['images']) ? json_decode($recipe['images'], true) : []; ?>
            <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-3">
                <?php foreach ($existingImages as $image): ?>
                    <img src="<?= BASE_URL ?>/uploads/<?= htmlspecialchars($image) ?>" alt="Obrázek receptu" class="h-48 w-full rounded-3xl object-cover" />
                <?php endforeach; ?>
            </div>
        </div>

        <label class="block">
            <span class="text-slate-700">Přidat nové obrázky</span>
            <input type="file" name="images[]" multiple accept="image/*" class="mt-2 w-full rounded-2xl border border-[#d8cab8] bg-[#faf0f0] p-3 text-sm outline-none focus:border-amber-400" />
            <p class="mt-2 text-sm text-slate-500">Nepovinné: nahráním nahradíte stávající obrázky.</p>
        </label>

        <button type="submit" class="rounded-full bg-[#f6b26b] px-6 py-3 text-white shadow-sm hover:bg-[#e69f4a]">Uložit změny</button>
    </form>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
