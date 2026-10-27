<?php

/** @var string[] $erreurs */
/** @var bool $envoye */
require __DIR__ . '/morceaux/header.php'; ?>
<h1>Mot de passe oublié</h1>

<p>Indiquez votre adresse e-mail. Si un compte existe, vous recevrez un lien pour choisir un nouveau mot de passe.</p>
<?php if ($envoye): ?>
    <div class="alert alert-success">
        <p class="mb-0">Si un compte existe avec cette adresse, un e-mail vous a été envoyé.</p>
    </div>
<?php endif; ?>
<?php if (!empty($erreurs)): ?>
    <div class="alert alert-danger">
        <ul class="mb-0">
            <?php foreach ($erreurs as $erreur): ?>
                <li><?= htmlspecialchars($erreur) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>
<form method="post" action="/mot-de-passe-oublie">
    <div class="mb-3">
        <label for="mail" class="form-label">Indiquez votre e-mail</label>
        <input type="email" id="mail" name="mail" class="form-control" required>
    </div>
    <button type="submit" class="btn btn-primary">Recevoir le lien</button>
</form>
<?php require __DIR__ . '/morceaux/footer.php'; ?>