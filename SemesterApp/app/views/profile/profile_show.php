<?php require_once __DIR__ . '/../layout/header.php'; ?>

<div class="mx-auto max-w-3xl rounded-3xl border border-[#e3d4c2] bg-white p-8 shadow-sm">
    <h2 class="mb-6 text-3xl font-semibold text-slate-900">Můj profil</h2>
    <div class="space-y-4 text-slate-700">
        <div class="grid gap-4 md:grid-cols-2">
            <div>
                <h3 class="text-sm uppercase tracking-wide text-amber-700">Uživatelské jméno</h3>
                <p class="mt-2 text-lg font-semibold"><?= htmlspecialchars($user['username']) ?></p>
            </div>
            <div>
                <h3 class="text-sm uppercase tracking-wide text-amber-700">E-mail</h3>
                <p class="mt-2 text-lg"><?= htmlspecialchars($user['email']) ?></p>
            </div>
        </div>
        <div class="grid gap-4 md:grid-cols-2">
            <div>
                <h3 class="text-sm uppercase tracking-wide text-amber-700">Jméno</h3>
                <p class="mt-2"><?= htmlspecialchars($user['first_name'] ?? '-') ?></p>
            </div>
            <div>
                <h3 class="text-sm uppercase tracking-wide text-amber-700">Příjmení</h3>
                <p class="mt-2"><?= htmlspecialchars($user['last_name'] ?? '-') ?></p>
            </div>
        </div>
        <div class="grid gap-4 md:grid-cols-2">
            <div>
                <h3 class="text-sm uppercase tracking-wide text-amber-700">Přezdívka</h3>
                <p class="mt-2"><?= htmlspecialchars($user['nickname'] ?? '-') ?></p>
            </div>
            <div>
                <h3 class="text-sm uppercase tracking-wide text-amber-700">Role</h3>
                <p class="mt-2 uppercase tracking-wider text-slate-500"><?= htmlspecialchars($user['role']) ?></p>
            </div>
        </div>
        <div class="mt-6 flex flex-wrap gap-4">
            <a href="<?= BASE_URL ?>/index.php?url=profile/edit" class="rounded-full bg-[#f6b26b] px-5 py-3 text-white shadow-sm hover:bg-[#e69f4a]">Upravit profil</a>
            <a href="<?= BASE_URL ?>/index.php?url=profile/favorites" class="rounded-full bg-[#f3e0c7] px-5 py-3 text-slate-900 shadow-sm hover:bg-[#e2c19e]">Moje oblíbené</a>
            <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin'): ?>
                <a href="<?= BASE_URL ?>/index.php?url=profile/users" class="rounded-full border border-[#d8cab8] px-5 py-3 text-slate-800 hover:bg-[#faf0e6]">Spravovat uživatele</a>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
