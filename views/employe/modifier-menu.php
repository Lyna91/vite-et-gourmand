<?php

/** @var array $themes */
/** @var array $regimes */
/** @var string $erreur */
/** @var Menu $menu */
require __DIR__ . '/../morceaux/header.php'; ?>
<h1>Modifier un menu</h1>
<?php if ($erreur): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($erreur) ?></div>
<?php endif ?>
<form method="post" action="/employe/menus/modifier?id=<?= $menu->getId() ?>">
    <div class="mb-3">
        <label for="titre" class="form-label">Titre</label>
        <input type="text" id="titre" name="titre" class="form-control" value="<?= htmlspecialchars($menu->getTitre()) ?>" required>
    </div>
    <div class="mb-3">
        <label for="description" class="form-label">Description</label>
        <textarea id="description" name="description" class="form-control" rows="3" required><?= htmlspecialchars($menu->getDescription()) ?></textarea>
    </div>
    <div class="mb-3">
        <label for="conditions" class="form-label">Conditions</label>
        <textarea id="conditions" name="conditions" class="form-control" rows="3" required><?= htmlspecialchars($menu->getConditions()) ?></textarea>
    </div>
    <div class="mb-3">
        <label for="stock" class="form-label">Stock</label>
        <input type="number" id="stock" name="stock" class="form-control" min="1" value="<?= $menu->getStock() ?>" required>
    </div>
    <div class=" mb-3">
        <label for="prix_par_personne" class="form-label">Prix par personne</label>
        <input type="number" id="prix_par_personne" name="prix_par_personne" class="form-control" step="0.01" min="0" value="<?= $menu->getPrixParPersonne() ?>" required>
    </div>
    <div class=" mb-3">
        <label for="nbre_personne_mini" class="form-label">Nombre de personnes minimum</label>
        <input type="number" id="nbre_personne_mini" name="nbre_personne_mini" class="form-control" min="1" value="<?= $menu->getNbrePersonneMini() ?>" required>
    </div>
    <div class=" mb-3">
        <label for="theme" class="form-label">Thème</label>
        <select id="theme" name="id_theme" class="form-select" required>
            <option value="">Choisir un thème</option>
            <?php foreach ($themes as $theme): ?>
                <option value="<?= $theme['id_theme'] ?>" <?= $theme['libelle'] === $menu->getTheme() ? 'selected' : '' ?>>
                    <?= htmlspecialchars($theme['libelle']) ?>
                </option> <?php endforeach; ?>
        </select>
    </div>
    <div class="mb-3">
        <label for="regime" class="form-label">Régime</label>
        <select id="regime" name="id_regime" class="form-select" required>
            <option value="">Choisir un régime</option>
            <?php foreach ($regimes as $regime): ?>
                <option value="<?= $regime['id_regime'] ?>" <?= $regime['libelle'] === $menu->getRegime() ? 'selected' : '' ?>>
                    <?= htmlspecialchars($regime['libelle']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    <button type="submit" class="btn btn-primary">Modifier le menu</button>
    <a href="/employe/menus" class="btn btn-outline-secondary">Annuler</a>
</form>


<?php require __DIR__ . '/../morceaux/footer.php'; ?>