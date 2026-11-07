<?php

/** @var int $idCommande */
/** @var array $erreurs */
require __DIR__ . '/../morceaux/header.php'; ?>
<h1>Annuler une commande</h1>

<div class="alert alert-danger">
    <p>Avant d’annuler, contactez le client par téléphone ou par mail.</p>
</div>
<?php if (!empty($erreurs)): ?>
    <div class="alert alert-danger">
        <ul class="mb-0">
            <?php foreach ($erreurs as $erreur): ?>
                <li><?= htmlspecialchars($erreur) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>
<form method="post" novalidate action="/employe/annuler">
    <input type="hidden" name="id_commande" value="<?= htmlspecialchars($idCommande) ?>">
    <div class="mb-3">
        <label for="mode_contact" class="form-label">Mode de contact</label>
        <select id="mode_contact" name="mode_contact" class="form-select" required>
            <option value="">Choisissez</option>
            <option value="GSM">Par téléphone</option>
            <option value="mail">Par mail</option>
        </select>
    </div>
    <div class="mb-3">
        <label for="motif" class="form-label">Motif de l'annulation</label>
        <textarea id="motif" name="motif" class="form-control" rows="4" required><?= htmlspecialchars($_POST['motif'] ?? '') ?></textarea>
        <button type="submit" class="btn btn-danger">Confirmer l'annulation</button>
    </div>

</form>

<a href="/employe" class="btn btn-link mt-3">Retour à l'espace employé</a>


<?php require __DIR__ . '/../morceaux/footer.php'; ?>