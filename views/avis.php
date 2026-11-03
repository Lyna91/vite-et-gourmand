<?php

/** @var array $commande */
require __DIR__ . '/morceaux/header.php'; ?>

<h1>Donner mon avis sur la commande <?= htmlspecialchars($commande['numero_commande']) ?></h1>
<?php if (!empty($erreurs)): ?>
    <div class="alert alert-danger">
        <ul class="mb-0">
            <?php foreach ($erreurs as $erreur): ?>
                <li><?= htmlspecialchars($erreur) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>
<form id="form-avis" method="post" action="/commande/avis">
    <div class="mb-3">
        <input type="hidden" id="id_commande" name="id_commande" class="form-control" value="<?= htmlspecialchars($commande['id_commande']) ?>">
    </div class="mb-3">
    <label for="note" class="form-label">Note</label>
    <select id="note" name="note" class="form-select" required>
        <option value="">Choisissez une note</option>
        <option value="5">5 - Excellent</option>
        <option value="4">4 - Très bien</option>
        <option value="3">3 - Assez bien</option>
        <option value="2">2 - Mauvais</option>
        <option value="1">1 - Très Mauvais</option>
    </select>
    <label for="commentaire" class="form-label">Commentaire</label>
    <textarea id="commentaire" name="commentaire" class="form-control" rows="4" required><?= htmlspecialchars($_POST['commentaire'] ?? '') ?></textarea>
    <button type="submit" class="btn btn-primary">Envoyer mon avis</button>

</form>

<?php require __DIR__ . '/morceaux/footer.php'; ?>