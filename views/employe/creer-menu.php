<?php

/** @var string[] $themes */
/** @var string[] $regimes */
require __DIR__ . '/../morceaux/header.php'; ?>

<form method="post" action="/employe/menus/creer">
    <div class="col-12 col-md-4">
        <label for="theme" class="form-label">Thème</label>
        <select id="theme" name="id_theme" class="form-select">
            <option value="">Tous les thèmes</option>
            <?php foreach ($themes as $theme): ?>
                <option value="<?= htmlspecialchars($theme) ?>"><?= htmlspecialchars($theme) ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="col-12 col-md-4">
        <label for="regime" class="form-label">Régime</label>
        <select id="regime" name="id_regime" class="form-select">
            <option value="">Tous les régimes</option>
            <?php foreach ($regimes as $regime): ?>
                <option value="<?= htmlspecialchars($regime) ?>"><?= htmlspecialchars($regime) ?></option>
            <?php endforeach; ?>
        </select>
    </div>
</form>


<?php require __DIR__ . '/../morceaux/footer.php'; ?>