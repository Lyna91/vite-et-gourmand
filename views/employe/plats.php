<?php

/** @var array $plats */
/** @var bool $cree */
/** @var bool $modifie */
require __DIR__ . '/../morceaux/header.php'; ?>
<h1>Gérer les plats</h1>
<a href="/employe/plats/creer" class="btn btn-primary mb-3">Créer un plat</a>
<?php if ($cree): ?>
    <div class="alert alert-success">
        <p>Plat créé ✓</p>
    </div>
<?php endif ?>
<?php if ($modifie): ?>
    <div class="alert alert-success">
        <p>Plat modifié ✓</p>
    </div>
<?php endif ?>
<?php if (empty($plats)): ?>
    <p>Aucun plat</p>
<?php else: ?>
    <table class="table">
        <thead>
            <tr>
                <th scope="col">Nom</th>
                <th scope="col">Type</th>
                <th scope="col">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($plats as $plat): ?>
                <tr>
                    <td><?= htmlspecialchars($plat['nom']) ?></td>
                    <td><?= htmlspecialchars($plat['type']) ?></td>
                    <td><a href="/employe/plats/modifier?id=<?= $plat['id_plat'] ?>" class="btn btn-sm btn-outline-primary">Modifier</a></td>
                </tr>
            <?php endforeach ?>
        </tbody>
    </table>
<?php endif ?>
<?php require __DIR__ . '/../morceaux/footer.php'; ?>