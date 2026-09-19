<?php
function e(mixed $value): string { return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8'); }
function money(mixed $value): string { return number_format((float) $value, 2, ',', ' ') . ' ' . CURRENCY; }
$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);
$currentPage = $_GET['page'] ?? 'dashboard';
$isAdmin = ($_SESSION['user']['role'] ?? '') === 'Administrateur';
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($pageTitle ?? APP_NAME) ?> | <?= e(APP_NAME) ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>tailwind.config = { theme: { extend: { colors: { cream: '#f4f1ea', paper: '#fffdf8', navy: '#102942', 'navy-soft': '#29445f', danger: '#c53636' }, fontFamily: { display: ['Georgia', 'serif'], sans: ['Arial', 'sans-serif'] } } } };</script>
    <link rel="stylesheet" href="<?= BASE_URL ?>/public/assets/style.css">
</head>
<body>
<div class="app-shell">
    <aside class="sidebar">
        <a class="brand" href="<?= BASE_URL ?>/index.php"><span class="brand-mark">F</span><span>FURU <small>/ HAOJUE</small></span></a>
        <nav aria-label="Navigation principale">
            <?php if ($isAdmin): ?><a class="nav-link <?= $currentPage === 'backup' ? 'is-active' : '' ?>" href="<?= BASE_URL ?>/index.php?page=backup"><span>↓</span> Sauvegarder la base</a><a class="nav-link <?= $currentPage === 'utilisateur-delete-page' ? 'is-active' : '' ?>" href="<?= BASE_URL ?>/index.php?page=utilisateur-delete-page"><span>×</span> Supprimer un compte</a><?php endif; ?>
            <a class="nav-link <?= $currentPage === 'dashboard' ? 'is-active' : '' ?>" href="<?= BASE_URL ?>/index.php?page=dashboard"><span>▦</span> Tableau de bord</a>
            <a class="nav-link <?= in_array($currentPage, ['produits', 'produit-form'], true) ? 'is-active' : '' ?>" href="<?= BASE_URL ?>/index.php?page=produits"><span>▤</span> Inventaire</a>
            <a class="nav-link <?= $currentPage === 'mouvements' ? 'is-active' : '' ?>" href="<?= BASE_URL ?>/index.php?page=mouvements"><span>↕</span> Mouvements</a>
            <?php if ($isAdmin): ?><a class="nav-link <?= $currentPage === 'caisse' ? 'is-active' : '' ?>" href="<?= BASE_URL ?>/index.php?page=caisse"><span>$</span> Caisse</a><a class="nav-link <?= $currentPage === 'rapport' ? 'is-active' : '' ?>" href="<?= BASE_URL ?>/index.php?page=rapport"><span>▧</span> Rapport du jour</a><a class="nav-link <?= $currentPage === 'categories' ? 'is-active' : '' ?>" href="<?= BASE_URL ?>/index.php?page=categories"><span>◇</span> Catégories</a><a class="nav-link <?= $currentPage === 'utilisateurs' ? 'is-active' : '' ?>" href="<?= BASE_URL ?>/index.php?page=utilisateurs"><span>◎</span> Utilisateurs</a><?php endif; ?>
        </nav>
        <div class="sidebar-footer">Gestion interne<br><strong>Version 1.0</strong></div>
    </aside>
    <main class="main-content">
        <div class="account-actions"><a href="<?= BASE_URL ?>/index.php?page=password">Changer mon mot de passe</a></div>
        <header class="topbar"><div><p class="eyebrow">GARAGE FURU / HAOJUE</p><h1><?= e($pageTitle ?? '') ?></h1></div><div class="user-chip"><span class="avatar">G</span><span><?= e($_SESSION['user']['nom'] ?? 'Gestionnaire') ?></span><a class="text-link" href="<?= BASE_URL ?>/index.php?page=logout">Quitter</a></div></header>
        <?php if ($flash): ?><div class="alert alert-<?= e($flash['type']) ?>" role="status"><?= e($flash['message']) ?></div><?php endif; ?>
        <div class="page-content">
