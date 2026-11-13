<?php

/** @var array $types */
/** @var string $erreur */
/** @var string $csrf */
require __DIR__ . '/../morceaux/header.php'; ?>
<h1>Créer un plat</h1>
<?php if ($erreur): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($erreur) ?></div>
<?php endif ?>
<form method="post" action="/employe/plats/creer">
    <input type="hidden" name="csrf" value="<?= $csrf ?>">
    <div class="mb-3">
        <label for="nom" class="form-label">Nom</label>
        <input type="text" id="nom" name="nom" class="form-control" required>
    </div>
    <div class="mb-3">
        <label for="type" class="form-label">Type</label>
        <select id="type" name="type" class="form-select" required>
            <option value="">Choisir un type</option>
            <?php foreach ($types as $type): ?>
                <option value="<?= htmlspecialchars($type) ?>"><?= htmlspecialchars($type) ?></option>
            <?php endforeach; ?>
        </select>
    </div>


    <button type="submit" class="btn btn-primary">Créer le plat</button>
    <a href="/employe/plats" class="btn btn-outline-secondary">Annuler</a>
</form>


<?php require __DIR__ . '/../morceaux/footer.php'; ?>