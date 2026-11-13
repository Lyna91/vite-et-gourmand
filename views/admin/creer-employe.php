<?php

/** @var array $employes */
require __DIR__ . '/../morceaux/header.php'; ?>
<h1>Créer un compte employé</h1>
<?php if (!empty($erreurs)): ?>
    <div class="alert alert-danger">
        <?php foreach ($erreurs as $erreur): ?>
            <p class="mb-0"><?= htmlspecialchars($erreur) ?></p>
        <?php endforeach ?>
    </div>
<?php endif ?>
<form method="post" action="/admin/employes/creer">
    <div class="mb-3">
        <label for="prenom" class="form-label">Prénom</label>
        <input type="text" id="prenom" name="prenom" class="form-control" required>
    </div>
    <div class="mb-3">
        <label for="nom" class="form-label">Nom</label>
        <input type="text" id="nom" name="nom" class="form-control" required>
    </div>
    <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" id="email" name="email" class="form-control" required>
    </div>
    <div class="mb-3">
        <label for="motDePasse" class="form-label">Mot de passe</label>
        <input type="password" id="motDePasse" name="motDePasse" class="form-control" minlength="10" required>
        <div class="form-text">10 caractères minimum, avec une majuscule, une minuscule, un chiffre et un caractère spécial.</div>
    </div>
    </div>

    <div class="mb-3">
        <label for="confirmation" class="form-label">Confirmez votre mot de passe</label>
        <input type="password" id="confirmation" name="confirmation" class="form-control" required>

        <button type="submit" class="btn btn-primary">Créer mon compte</button>
        <a href="/admin/employes" class="btn btn-outline-secondary">Annuler</a>
</form>
<?php require __DIR__ . '/../morceaux/footer.php'; ?>