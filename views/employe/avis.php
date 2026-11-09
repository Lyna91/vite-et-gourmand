<?php

/** @var bool $traite */
/** @var bool $erreur */
require __DIR__ . '/../morceaux/header.php'; ?>
<h1>Avis en attente de validation</h1>
<?php if ($traite): ?>
    <div class="alert alert-success">Avis traité ✓</div>
<?php endif; ?>
<?php if ($erreur): ?>
    <div class="alert alert-danger">Une erreur est survenue</div>
<?php endif; ?>
<?php if (empty($avis)): ?>
    <p>Aucun avis en attente</p>
<?php else: ?>
    <table class="table">
        <thead>
            <tr>
                <th scope="col">Date</th>
                <th scope="col">Commande</th>
                <th scope="col">Client</th>
                <th scope="col">Note</th>
                <th scope="col">Commentaire</th>
                <th scope="col">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($avis as $unAvis): ?>
                <tr>
                    <td><?= htmlspecialchars($unAvis['date_avis']) ?></td>
                    <td><?= htmlspecialchars($unAvis['numero_commande']) ?></td>
                    <td><?= htmlspecialchars($unAvis['prenom']) ?></td>
                    <td><?= htmlspecialchars($unAvis['note']) ?></td>
                    <td><?= htmlspecialchars($unAvis['description_avis']) ?></td>
                    <td>
                        <form method="post" action="/employe/avis/traiter">
                            <input type="hidden" name="id_avis" value="<?= $unAvis['id_avis'] ?>">
                            <button type="submit" name="statut" value="validé" class="btn btn-success btn-sm">Valider</button>
                            <button type="submit" name="statut" value="refusé" class="btn btn-danger btn-sm">Refuser</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach ?>
        </tbody>
    </table>
<?php endif ?>
<a href="../employe">Retour aux commandes</a>
<?php require __DIR__ . '/../morceaux/footer.php'; ?>