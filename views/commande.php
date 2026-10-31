<?php

/** @var Menu $menu */
/** @var array $utilisateur */
/** @var array $erreurs */
require __DIR__ . '/morceaux/header.php'; ?>

<h1 class="text-center">Commande du menu <?= htmlspecialchars($menu->getTitre()) ?></h1>
<a href="/menus">Retour aux menus</a>
<div>
    <h2>Rappel du menu</h2>
    <p><strong>Prix par personne : </strong><?= number_format($menu->getPrixParPersonne(), 2, ',', ' ') ?> € / personne</p>
    <p><strong>Nombre de personne minimum : </strong><?= $menu->getNbrePersonneMini() ?> personnes</p>
    <p><strong>Conditions : </strong><?= htmlspecialchars($menu->getConditions()) ?></p>
</div>
<div>
    <form id="form-commande" method="post" action="/commande" novalidate id="form-commande" data-prix="<?= $menu->getPrixParPersonne() ?>" data-mini="<?= $menu->getNbrePersonneMini() ?>">
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
            <input type="hidden" id="menu" name="menu" class="form-control" value="<?= htmlspecialchars($menu->getId()) ?>">
        </div>
        <div class="mb-3">
            <label for="nom" class="form-label">Nom</label>
            <input type="text" id="nom" name="nom" class="form-control" value="<?= htmlspecialchars($utilisateur['nom']) ?>" readonly>
        </div>
        <div class="mb-3">
            <label for="prenom" class="form-label">Prénom</label>
            <input type="text" id="prenom" name="prenom" class="form-control" value="<?= htmlspecialchars($utilisateur['prenom']) ?>" readonly>
        </div>
        <div class="mb-3">
            <label for="mail" class="form-label">Email</label>
            <input type="email" id="mail" name="mail" class="form-control" value="<?= htmlspecialchars($utilisateur['email']) ?>" readonly>
        </div>
        <div class="mb-3">
            <label for="telephone" class="form-label">Telephone</label>
            <input type="tel" id="telephone" name="telephone" class="form-control" value="<?= htmlspecialchars($utilisateur['telephone']) ?>" readonly>
        </div>
        <div class="mb-3">
            <h3 class="text-center">Informations adresse et date de livraison</h3>
        </div>
        <div class="mb-3">
            <label for="adresse" class="form-label">Adresse</label>
            <input type="text" id="adresse" name="adresse" class="form-control" value="<?= htmlspecialchars($_POST['adresse'] ?? $utilisateur['adresse']) ?>" required>
        </div>
        <div class="mb-3">
            <label for="cp" class="form-label">Code Postal</label>
            <input type="text" id="cp" name="cp" class="form-control" value="<?= htmlspecialchars($_POST['cp'] ?? $utilisateur['cp']) ?>" required>
        </div>
        <div class="mb-3">
            <label for="ville" class="form-label">Ville</label>
            <input type="text" id="ville" name="ville" class="form-control" value="<?= htmlspecialchars($_POST['ville'] ?? $utilisateur['ville']) ?>" required>
        </div>
        <div class="mb-3">
            <label for="distance" class="form-label">Distance depuis Bordeaux (km), à laisser vide si vous êtes à Bordeaux</label>
            <input type="number" step="0.1" min="0" id="distance" name="distance" class="form-control" value="<?= htmlspecialchars($_POST['distance'] ?? '') ?>">
        </div>
        <div class="mb-3">
            <label for="date_livraison" class="form-label">Date de livraison</label>
            <input type="date" id="date_livraison" name="date_livraison" class="form-control" value="<?= htmlspecialchars($_POST['date_livraison'] ?? '') ?>" required>
        </div>
        <div class="mb-3">
            <label for="heure_livraison" class="form-label">Heure de livraison</label>
            <input type="time" id="heure_livraison" name="heure_livraison" class="form-control" value="<?= htmlspecialchars($_POST['heure_livraison'] ?? '') ?>" required>
        </div>
        <div class="mb-3">
            <label for="nbre_personne" class="form-label">Nombre de personnes</label>
            <input type="number" min="<?= $menu->getNbrePersonneMini() ?>" id="nbre_personne" name="nbre_personne" class="form-control" value="<?= htmlspecialchars($_POST['nbre_personne'] ?? $menu->getNbrePersonneMini()) ?>"" required>
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
        <button type="submit" class="btn btn-primary">Confirmer la commande</button>
    </form>
</div>

<script src="/js/commande.js"></script>
<?php require __DIR__ . '/morceaux/footer.php'; ?>