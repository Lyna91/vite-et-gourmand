<?php

/** @var array $commande */
/** @var array $statuts */
require __DIR__ . '/morceaux/header.php'; ?>
<h1>Suivi de la commande <?= htmlspecialchars($commande['numero_commande']) ?></h1>
<table class="table">
    <thead>
        <tr>
            <th scope="col">Statut</th>
            <th scope="col">Date et heure</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($statuts as $statut): ?>
            <tr>
                <td><?= htmlspecialchars($statut['statut']) ?></td>
                <td><?= htmlspecialchars($statut['date_statut'] . " à " . ($statut['heure_statut'])) ?></td>
            <tr>
            <?php endforeach ?>
    </tbody>
</table>
<a href="/mes-commandes">Retour à mes commandes</a>
<?php require __DIR__ . '/morceaux/footer.php'; ?>