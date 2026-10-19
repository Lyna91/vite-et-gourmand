<?php

/** @var Menu $menu */
/** @var Plat[] $plats */
require __DIR__ . '/morceaux/header.php';
?>

<a href="/menus">Retour aux menus</a>

<h1><?= htmlspecialchars($menu->getTitre()) ?></h1>

<p><?= htmlspecialchars($menu->getDescription()) ?></p>
<p><?= number_format($menu->getPrixParPersonne(), 2, ',', ' ') ?> € / personne</p>
<p><strong>Régime : </strong> <?= htmlspecialchars($menu->getRegime()) ?></p>
<p><strong>Thème : </strong> <?= htmlspecialchars($menu->getTheme()) ?></p>
<p><strong>Minimum : </strong><?= $menu->getNbrePersonneMini() ?> personnes</p>
<p><strong>Stock disponible pour ce menu : </strong> <?= $menu->getStock() ?></p>
<div class="alert alert-warning">
    <p class="mb-0"><strong>Les conditions : </strong> <?= htmlspecialchars($menu->getConditions()) ?></p>
</div>

<h2>Composition du menu</h2>

<?php foreach (['Entrée', 'Plat', 'Dessert'] as $type): ?>
    <h3><?= $type ?></h3>
    <ul>
        <?php foreach ($plats as $plat): ?>
            <?php if ($plat->getType() === $type): ?>
                <li><?= htmlspecialchars($plat->getNom()) ?></li>
            <?php endif; ?>
        <?php endforeach; ?>
    </ul>
<?php endforeach; ?>

<?php require __DIR__ . '/morceaux/footer.php'; ?>