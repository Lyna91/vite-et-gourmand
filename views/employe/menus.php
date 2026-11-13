<?php

/** @var bool $cree */
/** @var bool $modifie */
/** @var bool $supprime */
/** @var bool $dejaCommande */
/** @var bool $erreur */
/** @var Menu[] $menus */
/** @var string $csrf */
require __DIR__ . '/../morceaux/header.php'; ?>
<h1>Gérer les menus</h1>
<a href="/employe" class="btn btn-outline-secondary mb-3">← Retour à l'espace employé</a>
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
<?php if ($supprime): ?>
    <div class="alert alert-success">
        <p>Menu supprimé ✓</p>
    </div>
<?php endif ?>
<?php if ($dejaCommande): ?>
    <div class="alert alert-danger">
        <p>Menu déjà commandé, il ne peut pas être supprimé afin de conserver l'historique des commandes.</p>
    </div>
<?php endif ?>
<?php if ($erreur): ?>
    <div class="alert alert-danger">
        <p>Une erreur est survenue.</p>
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
                        <form method="post" action="/employe/menus/supprimer" class="d-inline" onsubmit="return confirm('Supprimer ce menu ?');">
                            <input type="hidden" name="csrf" value="<?= $csrf ?>">
                            <input type="hidden" name="id_menu" value="<?= $menu->getId() ?>">
                            <button type="submit" class="btn btn-sm btn-outline-danger">Supprimer</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach ?>
        </tbody>
    </table>
<?php endif ?>
<?php require __DIR__ . '/../morceaux/footer.php'; ?>