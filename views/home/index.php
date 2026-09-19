<?php
function e(mixed $value): string { return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8'); }
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e(APP_NAME) ?> | Gestion de stock</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>tailwind.config = { theme: { extend: { colors: { cream: '#f4f1ea', paper: '#fffdf8', navy: '#102942', 'navy-soft': '#29445f', danger: '#c53636' } } } };</script>
    <link rel="stylesheet" href="<?= BASE_URL ?>/public/assets/style.css">
</head>
<body class="bg-cream text-navy">
    <header class="border-b border-[#d9d6cf] bg-paper">
        <div class="mx-auto flex max-w-7xl items-center justify-between px-6 py-5 lg:px-10">
            <a href="<?= BASE_URL ?>/index.php?page=home" class="flex items-center gap-3 text-sm font-bold tracking-[.16em]"><span class="grid h-10 w-10 place-items-center bg-navy text-lg text-paper">F</span><span>FURU <small class="text-[10px] opacity-60">/ HAOJUE</small></span></a>
            <nav class="hidden items-center gap-8 font-sans text-xs font-bold text-navy-soft md:flex"><a href="#vision" class="transition hover:text-navy">Notre vision</a><a href="#modules" class="transition hover:text-navy">Fonctionnalités</a><a href="<?= BASE_URL ?>/index.php?page=login" class="border border-navy px-5 py-3 text-navy transition hover:bg-navy hover:text-paper">Accéder à l’espace</a></nav>
            <a href="<?= BASE_URL ?>/index.php?page=login" class="border border-navy px-4 py-2 font-sans text-xs font-bold md:hidden">Connexion</a>
        </div>
    </header>
    <main>
        <section class="mx-auto grid max-w-7xl gap-14 px-6 pb-20 pt-16 lg:grid-cols-[1.05fr_.95fr] lg:items-center lg:px-10 lg:pb-28 lg:pt-24">
            <div class="animate-[fadeIn_.7s_ease-out_both]">
                <p class="mb-6 font-sans text-[10px] font-bold tracking-[.25em] text-navy-soft">GESTION DE STOCK · GARAGE FURU</p>
                <h1 class="max-w-3xl font-display text-5xl leading-[1.02] sm:text-6xl lg:text-7xl">Votre atelier avance avec une vision claire.</h1>
                <p class="mt-7 max-w-xl font-sans text-base leading-7 text-navy/70">Centralisez vos pièces HAOJUE, vos achats, vos ventes et votre caisse dans un espace simple, rapide et fiable.</p>
                <div class="mt-9 flex flex-wrap items-center gap-4"><a href="<?= BASE_URL ?>/index.php?page=login" class="bg-navy px-6 py-4 font-sans text-xs font-bold text-paper transition hover:bg-navy-soft">Ouvrir l’espace gestionnaire <span class="ml-3">→</span></a><a href="#modules" class="px-3 py-4 font-sans text-xs font-bold text-navy-soft underline underline-offset-4">Découvrir les outils</a></div>
                <div class="mt-14 flex gap-10 border-t border-[#d9d6cf] pt-6 font-sans"><div><strong class="block font-display text-2xl">01</strong><span class="text-[11px] text-navy/60">stock maîtrisé</span></div><div><strong class="block font-display text-2xl">02</strong><span class="text-[11px] text-navy/60">flux séparés</span></div><div><strong class="block font-display text-2xl">03</strong><span class="text-[11px] text-navy/60">décisions rapides</span></div></div>
            </div>
            <div class="relative min-h-[430px] overflow-hidden bg-navy p-8 text-paper shadow-2xl sm:p-12">
                <div class="absolute right-0 top-0 h-40 w-40 border-b border-l border-paper/20"></div><div class="absolute bottom-0 left-0 h-28 w-28 border-r border-t border-paper/20"></div>
                <div class="relative flex h-full flex-col justify-between"><div class="flex items-start justify-between"><span class="font-sans text-[10px] font-bold tracking-[.2em] text-paper/60">TABLEAU DE BORD</span><span class="h-2 w-2 bg-paper"></span></div><div><p class="font-sans text-xs text-paper/60">Valeur actuelle du stock</p><p class="mt-2 font-display text-5xl">— — —</p><div class="mt-10 grid grid-cols-2 gap-3 font-sans"><div class="border border-paper/20 p-4"><span class="block text-[10px] text-paper/60">RÉFÉRENCES</span><strong class="mt-3 block font-display text-2xl">000</strong></div><div class="border border-danger p-4"><span class="block text-[10px] text-paper/60">RUPTURES</span><strong class="mt-3 block font-display text-2xl text-danger">00</strong></div></div></div><p class="font-sans text-xs text-paper/60">FURU / HAOJUE <span class="float-right">VERSION 1.0</span></p></div>
            </div>
        </section>
        <section id="vision" class="border-y border-[#d9d6cf] bg-paper"><div class="mx-auto grid max-w-7xl gap-8 px-6 py-16 lg:grid-cols-[.8fr_1.2fr] lg:px-10"><p class="font-sans text-[10px] font-bold tracking-[.2em] text-navy-soft">UNE BASE SOLIDE</p><h2 class="max-w-3xl font-display text-3xl leading-tight sm:text-4xl">Chaque mouvement devient une information utile, pas une ligne perdue dans un cahier.</h2></div></section>
        <section id="modules" class="mx-auto max-w-7xl px-6 py-16 lg:px-10 lg:py-24"><div class="mb-10 flex items-end justify-between gap-6"><div><p class="mb-3 font-sans text-[10px] font-bold tracking-[.2em] text-navy-soft">LES MODULES</p><h2 class="font-display text-4xl">Tout ce qui compte au même endroit.</h2></div><span class="hidden font-sans text-xs text-navy/60 md:block">Pensé pour le bureau et le téléphone</span></div><div class="grid border-l border-t border-[#d9d6cf] sm:grid-cols-2 lg:grid-cols-4"><article class="border-b border-r border-[#d9d6cf] bg-paper p-7"><span class="font-sans text-xs font-bold">01</span><h3 class="mt-16 font-display text-2xl">Inventaire</h3><p class="mt-3 font-sans text-sm leading-6 text-navy/60">Retrouvez chaque référence, sa quantité, son prix et son emplacement en quelques secondes.</p></article><article class="border-b border-r border-[#d9d6cf] bg-paper p-7"><span class="font-sans text-xs font-bold">02</span><h3 class="mt-16 font-display text-2xl">Mouvements</h3><p class="mt-3 font-sans text-sm leading-6 text-navy/60">Enregistrez achats et ventes avec un stock toujours recalculé.</p></article><article class="border-b border-r border-[#d9d6cf] bg-paper p-7"><span class="font-sans text-xs font-bold">03</span><h3 class="mt-16 font-display text-2xl">Caisse</h3><p class="mt-3 font-sans text-sm leading-6 text-navy/60">Gardez les entrées et les dépenses distinctes pour des totaux justes.</p></article><article class="border-b border-r border-[#d9d6cf] bg-paper p-7"><span class="font-sans text-xs font-bold text-danger">04</span><h3 class="mt-16 font-display text-2xl">Alertes</h3><p class="mt-3 font-sans text-sm leading-6 text-navy/60">Les ruptures et les stocks faibles ressortent immédiatement.</p></article></div></section>
    </main>
    <footer class="border-t border-[#d9d6cf] bg-navy px-6 py-8 text-paper lg:px-10"><div class="mx-auto flex max-w-7xl flex-col gap-4 font-sans text-xs text-paper/60 sm:flex-row sm:items-center sm:justify-between"><span>GARAGE FURU / HAOJUE</span><span>Gestion interne · Version 1.0</span></div></footer>
</body>
</html>
