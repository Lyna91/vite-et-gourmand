<?php require __DIR__ . '/../morceaux/header.php'; ?>
<h1>Avis en attente de validation</h1>
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
                </tr>
            <?php endforeach ?>
        </tbody>
    </table>
<?php endif ?>
<?php require __DIR__ . '/../morceaux/footer.php'; ?>