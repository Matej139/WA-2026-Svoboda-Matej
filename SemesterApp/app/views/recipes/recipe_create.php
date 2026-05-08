<?php require_once __DIR__ . '/../layout/header.php'; ?>

<div class="mx-auto max-w-4xl rounded-3xl border border-[#e3d4c2] bg-white p-8 shadow-sm">
    <h2 class="mb-6 text-3xl font-semibold text-slate-900">Přidat nový recept</h2>

    <form action="<?= BASE_URL ?>/index.php?url=recipe/store" method="post" enctype="multipart/form-data" class="space-y-6">
        <div class="grid gap-4 lg:grid-cols-2">
            <label class="block">
                <span class="text-slate-700">Název receptu</span>
                <input type="text" name="title" required class="mt-2 w-full rounded-2xl border border-[#d8cab8] bg-[#faf0f0] p-3 outline-none focus:border-amber-400" />
            </label>
            <label class="block">
                <span class="text-slate-700">Kategorie</span>
                <select name="category" required class="mt-2 w-full rounded-2xl border border-[#d8cab8] bg-[#faf0f0] p-3 outline-none focus:border-amber-400">
                    <?php foreach ($categories as $category): ?>
                        <option value="<?= htmlspecialchars($category) ?>"><?= htmlspecialchars($category) ?></option>
                    <?php endforeach; ?>
                </select>
            </label>
        </div>

        <div class="grid gap-4 lg:grid-cols-3">
            <label class="block">
                <span class="text-slate-700">Obtížnost</span>
                <select name="difficulty" required class="mt-2 w-full rounded-2xl border border-[#d8cab8] bg-[#faf0f0] p-3 outline-none focus:border-amber-400">
                    <?php foreach ($difficulties as $difficulty): ?>
                        <option value="<?= htmlspecialchars($difficulty) ?>"><?= htmlspecialchars($difficulty) ?></option>
                    <?php endforeach; ?>
                </select>
            </label>
            <label class="block">
                <span class="text-slate-700">Doba přípravy (min)</span>
                <input type="number" name="cook_time" min="1" class="mt-2 w-full rounded-2xl border border-[#d8cab8] bg-[#faf0f0] p-3 outline-none focus:border-amber-400" />
            </label>
            <label class="block">
                <span class="text-slate-700">Cena (CZK)</span>
                <input type="number" step="0.01" name="cost" min="0" class="mt-2 w-full rounded-2xl border border-[#d8cab8] bg-[#faf0f0] p-3 outline-none focus:border-amber-400" />
            </label>
        </div>

        <label class="block">
            <span class="text-slate-700">Ingredience</span>
            <textarea name="ingredients" rows="4" required class="mt-2 w-full rounded-2xl border border-[#d8cab8] bg-[#faf0f0] p-3 outline-none focus:border-amber-400"></textarea>
        </label>

        <label class="block">
            <span class="text-slate-700">Postup / popis</span>
            <textarea name="description" rows="6" required class="mt-2 w-full rounded-2xl border border-[#d8cab8] bg-[#faf0f0] p-3 outline-none focus:border-amber-400"></textarea>
        </label>

        <label class="block">
            <span class="text-slate-700">Obrázky receptu</span>
            <input type="file" name="images[]" multiple accept="image/*" class="mt-2 w-full rounded-2xl border border-[#d8cab8] bg-[#faf0f0] p-3 text-sm outline-none focus:border-amber-400" />
            <p class="mt-2 text-sm text-slate-500">Podporováno: jpg, png, webp.</p>
        </label>

        <button type="submit" class="rounded-full bg-[#f6b26b] px-6 py-3 text-white shadow-sm hover:bg-[#e69f4a]">Uložit recept</button>
    </form>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
