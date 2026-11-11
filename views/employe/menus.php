<?php

/** @var bool $cree */
/** @var bool $modifie */
/** @var Menu[] $menus */
require __DIR__ . '/../morceaux/header.php'; ?>
<h1>Gérer les menus</h1>
<?php if ($cree): ?>
    <div class="alert alert-success">
        <p>Menu créé ✓</p>
    </div>
<?php endif ?>
<?php if ($modifie): ?>
    <div class="alert alert-success">
        <p>Menu modifié ✓</p>
    </div>
<?php endif ?>
<a href="/employe/menus/creer" class="btn btn-primary mb-3">Créer un menu</a><?php if (empty($menus)): ?>
    <p>Aucun menu</p>
<?php else: ?>
    <table class="table">
        <thead>
            <tr>
                <th scope="col">Titre</th>
                <th scope="col">Prix par personne</th>
                <th scope="col">Nombre personne minimum</th>
                <th scope="col">Stock</th>
                <th scope="col">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($menus as $menu): ?>
                <tr>
                    <td><?= htmlspecialchars($menu->getTitre()) ?></td>
                    <td><?= number_format($menu->getPrixParPersonne(), 2, ',', ' ') ?></td>
                    <td><?= $menu->getNbrePersonneMini() ?> </td>
                    <td><?= $menu->getStock() ?></td>
                    <td>
                        <a href="/employe/menus/modifier?id=<?= $menu->getId() ?>" class="btn btn-sm btn-outline-primary">Modifier</a>
                    </td>
                </tr>
            <?php endforeach ?>
        </tbody>
    </table>
<?php endif ?>
<?php require __DIR__ . '/../morceaux/footer.php'; ?>