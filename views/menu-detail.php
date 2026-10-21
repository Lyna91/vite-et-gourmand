<?php

/** @var Menu $menu */
/** @var Plat[] $plats */
/** @var Image[] $images */
require __DIR__ . '/morceaux/header.php';
?>

<a href="/menus">Retour aux menus</a>
<h1><?= htmlspecialchars($menu->getTitre()) ?></h1>

<p><?= htmlspecialchars($menu->getDescription()) ?></p>
<?php if (!empty($images)): ?>
    <div id="galerie-menu" class="carousel slide mb-4" data-bs-ride="carousel">
        <div class="carousel-inner rounded">
            <?php foreach ($images as $index => $image): ?>
                <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
                    <img src="/<?= htmlspecialchars($image->getChemin()) ?>"
                         alt="<?= htmlspecialchars($image->getTexte()) ?>"
                         class="d-block w-100"
                         loading="lazy">
                </div>
            <?php endforeach; ?>
        </div>

        <button class="carousel-control-prev" type="button" data-bs-target="#galerie-menu" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Image précédente</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#galerie-menu" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Image suivante</span>
        </button>
    </div>
<?php endif; ?>
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
                <li>
                    <?= htmlspecialchars($plat->getNom()) ?>
                    <?php if (!empty($plat->getAllergenes())): ?>
                        <br>
                        <small class="text-muted">
                            Allergènes : <?= htmlspecialchars(implode(', ', $plat->getAllergenes())) ?>
                        </small>
                    <?php endif; ?>
                </li>
            <?php endif; ?>
        <?php endforeach; ?>
    </ul>
<?php endforeach; ?>

<?php require __DIR__ . '/morceaux/footer.php'; ?>