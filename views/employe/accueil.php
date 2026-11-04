<?php

/** @var array $commandes */
require __DIR__ . '/../morceaux/header.php'; ?>

<h1>Espace employé</h1>

<table class="table">
    <thead>
        <tr>
            <th scope="col">N° de commande</th>
            <th scope="col">Livraison</th>
            <th scope="col">Client</th>
            <th scope="col">Contact</th>
            <th scope="col">Menu</th>
            <th scope="col">Personnes</th>
            <th scope="col">Total</th>
            <th scope="col">Statut</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($commandes as $commande): ?>
            <tr>
                <td><?= htmlspecialchars($commande['numero_commande']) ?></td>
                <td><?= htmlspecialchars($commande['date_livraison'] . " à " . ($commande['heure_livraison'])) ?></td>
                <td><?= htmlspecialchars($commande['prenom'] . ' ' . $commande['nom']) ?></td>
                <td><?= htmlspecialchars($commande['email']) ?><br><?= htmlspecialchars($commande['telephone']) ?></td>
                <td><?= htmlspecialchars($commande['titre']) ?></td>
                <td><?= htmlspecialchars($commande['nbre_personne']) ?></td>
                <td><?= number_format($commande['prix_menu'] - $commande['montant_reduction'] + $commande['frais_livraison'], 2, ',', ' ') ?> €</td>
                <td><?= htmlspecialchars($commande['statut']) ?></td>
                <td>
                    <?php if ($commande['statut'] === ''): ?>
                        <p>Aucune commande</p>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php require __DIR__ . '/../morceaux/footer.php'; ?>