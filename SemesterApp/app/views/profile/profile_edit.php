<?php require_once __DIR__ . '/../layout/header.php'; ?>

<div class="mx-auto max-w-3xl rounded-3xl border border-[#e3d4c2] bg-white p-8 shadow-sm">
    <h2 class="mb-6 text-3xl font-semibold text-slate-900">Upravit profil</h2>

    <form action="<?= BASE_URL ?>/index.php?url=profile/update" method="post" class="space-y-6">
        <div class="grid gap-4 md:grid-cols-2">
            <label class="block">
                <span class="text-slate-700">Uživatelské jméno</span>
                <input type="text" name="username" required value="<?= htmlspecialchars($user['username']) ?>" class="mt-2 w-full rounded-2xl border border-[#d8cab8] bg-[#faf0f0] p-3 outline-none focus:border-amber-400" />
            </label>
            <label class="block">
                <span class="text-slate-700">E-mail</span>
                <input type="email" name="email" required value="<?= htmlspecialchars($user['email']) ?>" class="mt-2 w-full rounded-2xl border border-[#d8cab8] bg-[#faf0f0] p-3 outline-none focus:border-amber-400" />
            </label>
        </div>

        <div class="grid gap-4 md:grid-cols-2">
            <label class="block">
                <span class="text-slate-700">Jméno</span>
                <input type="text" name="first_name" value="<?= htmlspecialchars($user['first_name'] ?? '') ?>" class="mt-2 w-full rounded-2xl border border-[#d8cab8] bg-[#faf0f0] p-3 outline-none focus:border-amber-400" />
            </label>
            <label class="block">
                <span class="text-slate-700">Příjmení</span>
                <input type="text" name="last_name" value="<?= htmlspecialchars($user['last_name'] ?? '') ?>" class="mt-2 w-full rounded-2xl border border-[#d8cab8] bg-[#faf0f0] p-3 outline-none focus:border-amber-400" />
            </label>
        </div>

        <label class="block">
            <span class="text-slate-700">Přezdívka</span>
            <input type="text" name="nickname" value="<?= htmlspecialchars($user['nickname'] ?? '') ?>" class="mt-2 w-full rounded-2xl border border-[#d8cab8] bg-[#faf0f0] p-3 outline-none focus:border-amber-400" />
        </label>

        <button type="submit" class="rounded-full bg-[#f6b26b] px-6 py-3 text-white shadow-sm hover:bg-[#e69f4a]">Uložit změny</button>
    </form>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
