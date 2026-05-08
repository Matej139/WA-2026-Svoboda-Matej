<?php require_once __DIR__ . '/../layout/header.php'; ?>

<div class="mx-auto max-w-4xl rounded-3xl border border-[#e3d4c2] bg-white p-8 shadow-sm">
    <h2 class="mb-6 text-3xl font-semibold text-slate-900">Správa uživatelů</h2>
    <div class="overflow-hidden rounded-3xl border border-[#e3d4c2]">
        <table class="min-w-full divide-y divide-[#e3d4c2] text-left text-sm text-slate-700">
            <thead class="bg-[#faf0e3]">
                <tr>
                    <th class="px-6 py-4 font-semibold uppercase">Jméno</th>
                    <th class="px-6 py-4 font-semibold uppercase">E-mail</th>
                    <th class="px-6 py-4 font-semibold uppercase">Role</th>
                    <th class="px-6 py-4 font-semibold uppercase">Registrován</th>
                    <th class="px-6 py-4 font-semibold uppercase">Akce</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-[#e3d4c2] bg-white">
                <?php foreach ($users as $userItem): ?>
                    <tr>
                        <td class="px-6 py-4"><?= htmlspecialchars($userItem['username']) ?></td>
                        <td class="px-6 py-4"><?= htmlspecialchars($userItem['email']) ?></td>
                        <td class="px-6 py-4 uppercase text-slate-500"><?= htmlspecialchars($userItem['role']) ?></td>
                        <td class="px-6 py-4"><?= htmlspecialchars($userItem['created_at']) ?></td>
                        <td class="px-6 py-4">
                            <?php if ((int)$userItem['id'] !== $_SESSION['user_id']): ?>
                                <a href="<?= BASE_URL ?>/index.php?url=profile/delete/<?= $userItem['id'] ?>" class="rounded-full border border-rose-300 px-4 py-2 text-sm text-rose-700 hover:bg-rose-50" onclick="return confirm('Opravdu chcete tohoto uživatele smazat?');">Smazat</a>
                            <?php else: ?>
                                <span class="text-slate-400">Váš účet</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
