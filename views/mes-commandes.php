<?php

/** @var array $commandes */
/** @var bool $annulee */
/** @var bool $erreur */
require __DIR__ . '/morceaux/header.php'; ?>
<h1>Mes commandes</h1>
<?php if ($annulee): ?>
    <div class="alert alert-success">
        <p>Votre commande a bien été annulée</p>
    </div>
<?php endif; ?>
<?php if ($erreur): ?>
    <div class="alert alert-danger">
        <p>Cette commande ne peut plus être annulée</p>
    </div>
<?php endif; ?>
<?php if (empty($commandes)): ?>
    <div class="alert alert-info">
        <p>Vous n’avez pas encore passé de commande</p>
        <a href="/menus">Découvrir nos menus</a>
    </div>
<?php else : ?>
    <table class="table">
        <thead>
            <tr>
                <th scope="col">N° de commande</th>
                <th scope="col">Menu</th>
                <th scope="col">Livraison</th>
                <th scope="col">Personne</th>
                <th scope="col">Total</th>
                <th scope="col">Statut</th>
                <th scope="col">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($commandes as $commande): ?>
                <tr>
                    <td><?= htmlspecialchars($commande['numero_commande']) ?></td>
                    <td><?= htmlspecialchars($commande['titre']) ?></td>
                    <td><?= htmlspecialchars($commande['date_livraison'] . " à " . ($commande['heure_livraison'])) ?></td>
                    <td><?= htmlspecialchars($commande['nbre_personne']) ?></td>
                    <td><?= number_format($commande['prix_menu'] - $commande['montant_reduction'] + $commande['frais_livraison'], 2, ',', ' ') ?> €</td>
                    <td><?= htmlspecialchars($commande['statut']) ?></td>
                    <td>
                        <?php if ($commande['statut'] === 'en attente'): ?>
                            <form method="post" action="/commande/annuler">
                                <input type="hidden" name="id_commande" value="<?= $commande['id_commande'] ?>">
                                <button type="submit" class="btn btn-sm btn-outline-danger">Annuler</button>
                            </form>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>
<?php require __DIR__ . '/morceaux/footer.php'; ?>