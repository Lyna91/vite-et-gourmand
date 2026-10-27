<?php

/** @var string[] $erreurs */
/** @var bool $valide */
/** @var string $token */
require __DIR__ . '/morceaux/header.php'; ?>
<h1>Nouveau mot de passe</h1>
<?php if (!empty($erreurs)): ?>
    <div class="alert alert-danger">
        <ul class="mb-0">
            <?php foreach ($erreurs as $erreur): ?>
                <li><?= htmlspecialchars($erreur) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>
<?php if (!$valide): ?>
    <div class="alert alert-warning">
        <p>Ce lien est invalide ou a expiré.</p>
        <p class="mt-3"><a href="/mot-de-passe-oublie">Faire une nouvelle demande.</a></p>
    </div>
<?php else: ?>
    <form method="post" action="/reinitialisation">
        <div>
            <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">
            <div>
                <label for="motDePasse" class="form-label">Mot de passe</label>
                <input type="password" id="motDePasse" name="motDePasse" class="form-control" minlength="10" required>
                <div class="form-text">10 caractères minimum, avec une majuscule, une minuscule, un chiffre et un caractère spécial.</div>
            </div>
        </div>
        <div>
            <label for="confirmation" class="form-label">Confirmez votre mot de passe</label>
            <input type="password" id="confirmation" name="confirmation" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Enregister le nouveau mot de passe</button>
    </form>
<?php endif; ?>
<?php require __DIR__ . '/morceaux/footer.php'; ?>