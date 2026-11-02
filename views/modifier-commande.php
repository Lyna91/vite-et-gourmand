<?php

/** @var Menu $menu */
/** @var array $erreurs */
/** @var array $commande */
/** @var string|float $distance */
require __DIR__ . '/morceaux/header.php'; ?>

<h1 class="text-center">Modifier la commande <?= htmlspecialchars($commande['numero_commande']) ?></h1>
<a href="/mes-commandes">Retour aux commandes</a>
<div>
    <h2>Rappel du menu</h2>
    <p><strong>Prix par personne : </strong><?= number_format($menu->getPrixParPersonne(), 2, ',', ' ') ?> € / personne</p>
    <p><strong>Nombre de personne minimum : </strong><?= $menu->getNbrePersonneMini() ?> personnes</p>
    <p><strong>Conditions : </strong><?= htmlspecialchars($menu->getConditions()) ?></p>
</div>
<div>
    <form id="form-commande" method="post" action="/commande/modifier" data-prix="<?= $menu->getPrixParPersonne() ?>" data-mini="<?= $menu->getNbrePersonneMini() ?>">
        <?php if (!empty($erreurs)): ?>
            <div class="alert alert-danger">
                <ul class="mb-0">
                    <?php foreach ($erreurs as $erreur): ?>
                        <li><?= htmlspecialchars($erreur) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
        <h3 class="text-center">Remplissez vos informations</h3>
        <div class="mb-3">
            <input type="hidden" id="id_commande" name="id_commande" class="form-control" value="<?= htmlspecialchars($commande['id_commande']) ?>">
        </div>
        <div class="mb-3">
            <h3 class="text-center">Informations adresse et date de livraison</h3>
        </div>
        <div class="mb-3">
            <label for="adresse" class="form-label">Adresse</label>
            <input type="text" id="adresse" name="adresse" class="form-control" value="<?= htmlspecialchars($_POST['adresse'] ?? $commande['adresse_livraison']) ?>" required>
        </div>
        <div class="mb-3">
            <label for="cp" class="form-label">Code Postal</label>
            <input type="text" id="cp" name="cp" class="form-control" value="<?= htmlspecialchars($_POST['cp'] ?? $commande['cp_livraison']) ?>" required>
        </div>
        <div class="mb-3">
            <label for="ville" class="form-label">Ville</label>
            <input type="text" id="ville" name="ville" class="form-control" value="<?= htmlspecialchars($_POST['ville'] ?? $commande['ville_livraison']) ?>" required>
        </div>
        <div class="mb-3">
            <label for="distance" class="form-label">Distance depuis Bordeaux (km), à laisser vide si vous êtes à Bordeaux</label>
            <input type="number" step="0.1" min="0" id="distance" name="distance" class="form-control" value="<?= htmlspecialchars($_POST['distance'] ?? $distance) ?>">
        </div>
        <div class="mb-3">
            <label for="date_livraison" class="form-label">Date de livraison</label>
            <input type="date" id="date_livraison" name="date_livraison" class="form-control" value="<?= htmlspecialchars($_POST['date_livraison'] ?? $commande['date_livraison']) ?>" required>
        </div>
        <div class="mb-3">
            <label for="heure_livraison" class="form-label">Heure de livraison</label>
            <input type="time" id="heure_livraison" name="heure_livraison" class="form-control" value="<?= htmlspecialchars($_POST['heure_livraison'] ?? $commande['heure_livraison']) ?>" required>
        </div>
        <div class="mb-3">
            <label for="nbre_personne" class="form-label">Nombre de personnes</label>
            <input type="number" min="<?= $menu->getNbrePersonneMini() ?>" id="nbre_personne" name="nbre_personne" class="form-control" value="<?= htmlspecialchars($_POST['nbre_personne'] ?? $commande['nbre_personne']) ?>" required>
        </div>
        <div class=" mb-3">
            <h3 class="text-center">Détails du prix</h3>
        </div>
        <div class="mb-3">
            <p>Prix des menus : <span id="prix-menus"></span> €</p>
            <p>Remise (10%) : <span id="prix-remise"></span> €</p>
            <p>Livraison : <span id="prix-livraison"></span> €</p>
            <p><strong>Total : </strong><span id="prix-total"></span> €</p>
        </div>
        <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
    </form>
</div>

<script src="/js/commande.js"></script>
<?php require __DIR__ . '/morceaux/footer.php'; ?>