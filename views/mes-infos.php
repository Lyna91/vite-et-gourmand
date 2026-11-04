<?php

/** @var array $utilisateur */
/** @var bool $succes */
require __DIR__ . '/morceaux/header.php'; ?>
<h1>Mes informations</h1>
<?php if (!empty($erreurs)): ?>
    <div class="alert alert-danger">
        <ul class="mb-0">
            <?php foreach ($erreurs as $erreur): ?>
                <li><?= htmlspecialchars($erreur) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>
<?php if ($succes): ?>
    <div class="alert alert-success">
        <p class="mb-0">Vos information on été mises à jour.</p>
    </div>
<?php endif; ?>
<form method="post" action="/mes-infos">
    <div class="mb-3">
        <label for="nom" class="form-label">Votre nom</label>
        <input type="text" id="nom" name="nom" class="form-control" value="<?= htmlspecialchars($_POST['nom'] ?? $utilisateur['nom']) ?>" required>
    </div>
    <div class="mb-3">
        <label for="prenom" class="form-label">Votre prénom</label>
        <input type="text" id="prenom" name="prenom" class="form-control" value="<?= htmlspecialchars($_POST['prenom'] ?? $utilisateur['prenom']) ?>" required>
    </div>
    <div class="mb-3">
        <label for="mail" class="form-label">Email</label>
        <input type="mail" id="mail" name="mail" class="form-control" value="<?= htmlspecialchars($_POST['mail'] ?? $utilisateur['email']) ?>" required>
    </div>
    <div class="mb-3">
        <label for="telephone" class="form-label">Numéro de téléphone</label>
        <input type="tel" id="telephone" name="telephone" class="form-control" value="<?= htmlspecialchars($_POST['telephone'] ?? $utilisateur['telephone']) ?>" required>
    </div>
    <div class="mb-3">
        <label for="adresse" class="form-label">Adresse</label>
        <input type="text" id="adresse" name="adresse" class="form-control" value="<?= htmlspecialchars($_POST['adresse'] ?? $utilisateur['adresse']) ?>" required>
    </div>
    <div class="mb-3">
        <label for="cp" class="form-label">Code postal</label>
        <input type="text" id="cp" name="cp" class="form-control" value="<?= htmlspecialchars($_POST['cp'] ?? $utilisateur['cp']) ?>" required>
    </div>
    <div class="mb-3">
        <label for="ville" class="form-label">Ville</label>
        <input type="text" id="ville" name="ville" class="form-control" value="<?= htmlspecialchars($_POST['ville'] ?? $utilisateur['ville']) ?>" required>
    </div>
    <button type="submit" class="btn btn-primary">Enregistrer</button>
</form>

<?php require __DIR__ . '/morceaux/footer.php'; ?>