<?php

/** @var array $commandes */
require __DIR__ . '/../morceaux/header.php'; ?>

<h1>Espace employé</h1>
<div class="row mb-3">
    <div class="col-md-6">
        <label for="filtre-statut" class="form-label">Statut</label>
        <select id="filtre-statut" class="form-select">
            <option value="">Tous les statuts</option>
            <option value="en attente">En attente</option>
            <option value="accepté">Accepté</option>
            <option value="en préparation">En préparation</option>
            <option value="en cours de livraison">En cours de livraison</option>
            <option value="livré">Livré</option>
            <option value="en attente du retour de matériel">En attente du retour de matériel</option>
            <option value="terminée">Terminée</option>
            <option value="annulée">Annulée</option>
        </select>
    </div>
    <div class="col-md-6">
        <label for="filtre-client" class="form-label">Client (nom, prénom ou e-mail)</label>
        <input type="text" id="filtre-client" class="form-control">
    </div>
</div>
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
    <tbody id="liste-commandes">
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

<script src="/js/employe-commandes.js"></script>
<?php require __DIR__ . '/../morceaux/footer.php'; ?>