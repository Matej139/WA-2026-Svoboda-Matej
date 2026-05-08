<?php require_once __DIR__ . '/../layout/header.php'; ?>

<div class="mx-auto max-w-lg rounded-3xl border border-[#e3d4c2] bg-white p-8 shadow-lg">
    <h2 class="mb-4 text-3xl font-semibold text-slate-800">Přihlášení</h2>
    <form action="<?= BASE_URL ?>/index.php?url=auth/authenticate" method="post" class="space-y-5">
        <label class="block">
            <span class="text-slate-700">E-mail</span>
            <input type="email" name="email" required class="mt-2 w-full rounded-xl border border-[#d8cab8] bg-[#f7efe0] p-3 outline-none focus:border-amber-400" />
        </label>
        <label class="block">
            <span class="text-slate-700">Heslo</span>
            <input type="password" name="password" required class="mt-2 w-full rounded-xl border border-[#d8cab8] bg-[#f7efe0] p-3 outline-none focus:border-amber-400" />
        </label>
        <button type="submit" class="w-full rounded-full bg-[#f6b26b] px-5 py-3 text-white shadow-sm hover:bg-[#e69f4a]">Přihlásit se</button>
    </form>
    <p class="mt-6 text-sm text-slate-600">Ještě nemáte účet? <a href="<?= BASE_URL ?>/index.php?url=auth/register" class="font-semibold text-amber-700 hover:text-amber-900">Zaregistrujte se</a>.</p>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
