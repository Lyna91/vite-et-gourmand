<?php

/** @var Menu $menu */
require __DIR__ . '/morceaux/header.php';
?>

<a href="/menus">Retour aux menus</a>

<h1><?= htmlspecialchars($menu->getTitre()) ?></h1>

<p><?= htmlspecialchars($menu->getDescription()) ?></p>
<p><?= number_format($menu->getPrixParPersonne(), 2, ',', ' ') . '€' . '/personne' ?></p>

<?php require __DIR__ . '/morceaux/footer.php'; ?>