<?php require_once __DIR__ . '/../layout/header.php'; ?>

<div class="mx-auto max-w-xl rounded-3xl border border-[#e3d4c2] bg-white p-8 shadow-lg">
    <h2 class="mb-4 text-3xl font-semibold text-slate-800">Registrace</h2>
    <form action="<?= BASE_URL ?>/index.php?url=auth/storeUser" method="post" class="space-y-5">
        <div class="grid gap-4 md:grid-cols-2">
            <label class="block">
                <span class="text-slate-700">Uživatelské jméno</span>
                <input type="text" name="username" required class="mt-2 w-full rounded-xl border border-[#d8cab8] bg-[#f7efe0] p-3 outline-none focus:border-amber-400" />
            </label>
            <label class="block">
                <span class="text-slate-700">E-mail</span>
                <input type="email" name="email" required class="mt-2 w-full rounded-xl border border-[#d8cab8] bg-[#f7efe0] p-3 outline-none focus:border-amber-400" />
            </label>
        </div>
        <div class="grid gap-4 md:grid-cols-2">
            <label class="block">
                <span class="text-slate-700">Jméno</span>
                <input type="text" name="first_name" class="mt-2 w-full rounded-xl border border-[#d8cab8] bg-[#f7efe0] p-3 outline-none focus:border-amber-400" />
            </label>
            <label class="block">
                <span class="text-slate-700">Příjmení</span>
                <input type="text" name="last_name" class="mt-2 w-full rounded-xl border border-[#d8cab8] bg-[#f7efe0] p-3 outline-none focus:border-amber-400" />
            </label>
        </div>
        <label class="block">
            <span class="text-slate-700">Přezdívka</span>
            <input type="text" name="nickname" class="mt-2 w-full rounded-xl border border-[#d8cab8] bg-[#f7efe0] p-3 outline-none focus:border-amber-400" />
        </label>
        <div class="grid gap-4 md:grid-cols-2">
            <label class="block">
                <span class="text-slate-700">Heslo</span>
                <input type="password" name="password" required class="mt-2 w-full rounded-xl border border-[#d8cab8] bg-[#f7efe0] p-3 outline-none focus:border-amber-400" />
            </label>
            <label class="block">
                <span class="text-slate-700">Heslo znovu</span>
                <input type="password" name="password_confirm" required class="mt-2 w-full rounded-xl border border-[#d8cab8] bg-[#f7efe0] p-3 outline-none focus:border-amber-400" />
            </label>
        </div>
        <button type="submit" class="w-full rounded-full bg-[#f6b26b] px-5 py-3 text-white shadow-sm hover:bg-[#e69f4a]">Registrovat</button>
    </form>
    <p class="mt-6 text-sm text-slate-600">Už máte účet? <a href="<?= BASE_URL ?>/index.php?url=auth/login" class="font-semibold text-amber-700 hover:text-amber-900">Přihlaste se</a>.</p>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
