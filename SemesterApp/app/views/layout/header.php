<!DOCTYPE html>
<html lang="cs" class="h-full bg-[#f5efe5]">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <title>RecipeJoy • Domácí recepty</title>
    <style>
        .fade-in { animation: fadeIn 0.4s ease-out; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(-10px); } to { opacity: 1; transform: translateY(0); } }
    </style>
</head>
<body class="min-h-screen font-sans text-slate-800 bg-[#faf0e6]">
    <header class="bg-[#fff7ed] border-b border-[#e3d4c2] shadow-sm">
        <div class="container mx-auto px-6 py-5 flex flex-col md:flex-row items-center justify-between gap-4">
            <a href="<?= BASE_URL ?>/index.php" class="flex items-center gap-4">
                <img src="<?= BASE_URL ?>/images/logo.svg" alt="RecipeJoy logo" class="h-14 w-14" />
                <div>
                    <h1 class="text-2xl font-bold tracking-tight">RecipeJoy</h1>
                    <p class="text-sm text-slate-600">Super recepty a sdílené nápady</p>
                </div>
            </a>
            <!-- Navigační menu se mění podle toho, zda je uživatel přihlášený nebo ne -->
            <nav>
                <ul class="flex flex-wrap items-center gap-3 text-sm font-medium text-slate-700">
                    <li><a href="<?= BASE_URL ?>/index.php" class="hover:text-amber-700">Domů</a></li>
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <li><a href="<?= BASE_URL ?>/index.php?url=recipe/create" class="rounded-full bg-[#fbc68b] px-4 py-2 text-slate-900 hover:bg-[#f5a663]">Nový recept</a></li>
                        <li><a href="<?= BASE_URL ?>/index.php?url=profile/favorites" class="hover:text-amber-700">Moje oblíbené</a></li>
                        <li><a href="<?= BASE_URL ?>/index.php?url=profile/show" class="hover:text-amber-700">Profil</a></li>
                        <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin'): ?>
                            <li><a href="<?= BASE_URL ?>/index.php?url=profile/users" class="hover:text-amber-700">Správa uživatelů</a></li>
                        <?php endif; ?>
                        <li class="text-slate-500">Ahoj, <span class="font-semibold"><?= htmlspecialchars($_SESSION['user_name']) ?></span></li>
                        <li><a href="<?= BASE_URL ?>/index.php?url=auth/logout" class="text-rose-600 hover:text-rose-800">Odhlásit</a></li>
                    <?php else: ?>
                        <li><a href="<?= BASE_URL ?>/index.php?url=auth/login" class="hover:text-amber-700">Přihlásit</a></li>
                        <li><a href="<?= BASE_URL ?>/index.php?url=auth/register" class="rounded-full bg-[#fbc68b] px-4 py-2 text-slate-900 hover:bg-[#f5a663]">Registrace</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </header>

    <main class="container mx-auto px-6 py-8">
        <?php if (!empty($_SESSION['messages'])): ?>
            <div class="space-y-3 mb-6">
                <?php foreach ($_SESSION['messages'] as $type => $messages): ?>
                    <?php
                        $styles = [
                            'success' => 'bg-emerald-100 border-emerald-300 text-emerald-900',
                            'error' => 'bg-rose-100 border-rose-300 text-rose-900',
                            'notice' => 'bg-amber-100 border-amber-300 text-amber-900',
                        ];
                        $style = isset($styles[$type]) ? $styles[$type] : 'bg-slate-100 border-slate-300 text-slate-900';
                    ?>
                    <?php foreach ($messages as $message): ?>
                        <div class="<?= $style ?> border-l-4 p-4 rounded-lg shadow-sm fade-in">
                            <?= htmlspecialchars($message) ?>
                        </div>
                    <?php endforeach; ?>
                <?php endforeach; ?>
            </div>
            <?php unset($_SESSION['messages']); ?>
        <?php endif; ?>
