<?php require_once __DIR__ . '/../layout/header.php'; ?>

<div class="mx-auto max-w-3xl rounded-3xl border border-[#e3d4c2] bg-white p-8 shadow-sm">
    <h2 class="mb-6 text-3xl font-semibold text-slate-900">Upravit komentář</h2>

    <form action="<?= BASE_URL ?>/index.php?url=comment/update/<?= $comment['id'] ?>" method="post" class="space-y-6">
        <label class="block">
            <span class="text-slate-700">Komentář</span>
            <textarea name="content" rows="5" required class="mt-2 w-full rounded-3xl border border-[#d8cab8] bg-[#faf0f0] p-4 outline-none focus:border-amber-400"><?= htmlspecialchars($comment['content']) ?></textarea>
        </label>
        <button type="submit" class="rounded-full bg-[#f6b26b] px-6 py-3 text-white shadow-sm hover:bg-[#e69f4a]">Uložit změny</button>
    </form>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
