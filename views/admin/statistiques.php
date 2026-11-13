<?php

/** @var ?string $synchronise */     // un texte, ou null si pas de synchronisation
/** @var string $erreur */           // un texte (vide s'il n'y a pas d'erreur)
/** @var ?int $idMenu */             // un nombre, ou null si pas de filtre
/** @var ?string $dateDebut */
/** @var ?string $dateFin */
/** @var Menu[] $menus */            // une liste d'objets Menu
/** @var array $chiffresAffaires */
/** @var array $commandesParMenu */
require __DIR__ . '/../morceaux/header.php'; ?>
<h1>Les statistiques</h1>
<a href="/employe" class="btn btn-outline-secondary mb-3">← Retour à l'espace employé</a>
<?php if ($synchronise !== null): ?>
    <div class="alert alert-success">
        <p class="mb-0"><?= (int) $synchronise ?> commande(s) synchronisée(s) ✓</p>
    </div>
<?php endif ?>
<div>
    <form method="post" action="/admin/statistiques/synchroniser" class="mb-4">
        <button type="submit" class="btn btn-outline-primary">Mettre à jour les statistiques</button>
    </form>
</div>
<?php if ($erreur): ?>
    <div class="alert alert-danger">
        <p class="mb-0"><?= htmlspecialchars($erreur) ?></p>
    </div>
<?php endif ?>
<h2 class="h4 mt-4">Nombre de commandes par menu</h2>
<p class="text-muted small">Toutes les commandes non annulées, tous menus confondus.</p>
<div>
    <?php if (empty($commandesParMenu)): ?>
        <p>Aucune donnée. Cliquez sur « Mettre à jour les statistiques ».</p>
    <?php else: ?>
        <canvas id="graphique-commandes"
            data-menus="<?= htmlspecialchars(json_encode(array_column($commandesParMenu, 'menu'))) ?>"
            data-nombres="<?= htmlspecialchars(json_encode(array_column($commandesParMenu, 'nombre'))) ?>">
        </canvas>
    <?php endif ?>
</div>
<h2 class="h4 mt-5">Chiffre d’affaires par menu</h2>
<div>
    <form method="get" action="/admin/statistiques" class="row g-2 align-items-end mb-3">
        <div class="col-md-4">
            <label for="menu" class="form-label">Menu</label>
            <select id="menu" name="menu" class="form-select">
                <option value="">Tous les menus</option>
                <?php foreach ($menus as $menu): ?>
                    <option value="<?= $menu->getId() ?>" <?= $menu->getId() === $idMenu ? 'selected' : '' ?>>
                        <?= htmlspecialchars($menu->getTitre()) ?>
                    </option>
                <?php endforeach ?>
            </select>
        </div>
        <div class="col-md-3">
            <label for="debut" class="form-label">Du</label>
            <input type="date" id="debut" name="debut" class="form-control" value="<?= htmlspecialchars($dateDebut ?? '') ?>">
        </div>
        <div class="col-md-3">
            <label for="fin" class="form-label">Au</label>
            <input type="date" id="fin" name="fin" class="form-control" value="<?= htmlspecialchars($dateFin ?? '') ?>">
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-primary w-100">Filtrer</button>
            <a href="/admin/statistiques" class="btn btn-outline-secondary mb-3">Réinitialiser</a>
        </div>
    </form>
</div>

<?php if (empty($chiffresAffaires)): ?>
    <p>Aucune commande pour ces critères</p>
<?php else: ?>
    <table class="table">
        <thead>
            <tr>
                <th scope="col">Menu</th>
                <th scope="col">Commandes</th>
                <th scope="col">Chiffre d'affaires</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($chiffresAffaires as $ligne): ?>
                <tr>
                    <td><?= htmlspecialchars($ligne['menu']) ?></td>
                    <td><?= $ligne['nombre'] ?></td>
                    <td><?= number_format($ligne['chiffre_affaires'], 2, ',', ' ') ?> €</td>
                </tr>
            <?php endforeach ?>
        </tbody>
    </table>
<?php endif ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script src="/js/admin-statistiques.js"></script>
<?php require __DIR__ . '/../morceaux/footer.php'; ?>