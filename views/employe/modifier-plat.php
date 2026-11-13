<?php

/** @var array $types */
/** @var string $erreur */
/** @var array $plat */
/** @var string $csrf */
require __DIR__ . '/../morceaux/header.php'; ?>
<h1>Modifier un plat</h1>
<?php if ($erreur): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($erreur) ?></div>
<?php endif ?>
<form method="post" action="/employe/plats/modifier?id=<?= $plat['id_plat'] ?>">
    <input type="hidden" name="csrf" value="<?= $csrf ?>">
    <div class="mb-3">
        <label for="nom" class="form-label">Nom</label>
        <input type="text" id="nom" name="nom" class="form-control" value="<?= htmlspecialchars($plat['nom']) ?>" required>
    </div>
    <div class="mb-3">
        <label for="type" class="form-label">Type</label>
        <select id="type" name="type" class="form-select" required>
            <option value="">Choisir un type</option>
            <?php foreach ($types as $type): ?>
                <option value="<?= htmlspecialchars($type) ?>" <?= $type === $plat['type'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($type) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>


    <button type="submit" class="btn btn-primary">Modifier le plat</button>
    <a href="/employe/plats" class="btn btn-outline-secondary">Annuler</a>
</form>


<?php require __DIR__ . '/../morceaux/footer.php'; ?>