<?php

/** @var array $commandes */
/** @var bool $annulee */
/** @var bool $modifiee */
/** @var bool $erreur */
/** @var bool $avis */
/** @var bool $avisImpossible */
/** @var string $csrf */
require __DIR__ . '/morceaux/header.php'; ?>
<h1>Mes commandes</h1>
<?php if ($annulee): ?>
    <div class="alert alert-success">
        <p>Votre commande a bien été annulée</p>
    </div>
<?php endif; ?>
<?php if ($modifiee): ?>
    <div class="alert alert-success">
        <p>Votre commande a bien été modifiée</p>
    </div>
<?php endif; ?>
<?php if ($avis): ?>
    <div class="alert alert-success">Merci pour votre avis ! Il sera publié après validation.</div>
<?php endif; ?>
<?php if ($avisImpossible): ?>
    <div class="alert alert-danger">Vous ne pouvez pas donner d’avis sur cette commande.</div>
<?php endif; ?>
<?php if ($erreur): ?>
    <div class="alert alert-danger">
        <p>Cette commande ne peut plus être modifiée ni annulée.</p>
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
                        <?php if ($commande['statut'] !== 'en attente' && $commande['statut'] !== 'annulée'): ?>
                            <a href="/commande/suivi?id=<?= $commande['id_commande'] ?>" class="btn btn-sm btn-outline-secondary">Suivi</a>
                        <?php endif; ?>
                        <?php if ($commande['statut'] === 'terminée' && $commande['nb_avis'] == 0): ?>
                            <a href="/commande/avis?id=<?= $commande['id_commande'] ?>" class="btn btn-sm btn-outline-secondary">Donner mon avis</a>
                        <?php elseif ($commande['statut'] === 'terminée' && $commande['nb_avis'] > 0): ?>
                            <span class="badge text-bg-success">Avis déjà donné ✓</span>
                        <?php endif; ?>
                        <?php if ($commande['statut'] === 'en attente'): ?>
                            <a href="/commande/modifier?id=<?= $commande['id_commande'] ?>" class="btn btn-sm btn-outline-primary">Modifier</a>
                            <form method="post" action="/commande/annuler">
                                <input type="hidden" name="csrf" value="<?= $csrf ?>">
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