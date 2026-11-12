<?php

/** @var array $employes */
require __DIR__ . '/../morceaux/header.php'; ?>
<h1>Gérer les employés</h1>
<a href="/employe" class="btn btn-outline-secondary mb-3">← Retour à l'espace employé</a>
<a href="/admin/employes/creer" class="btn btn-primary mb-3">Créer un compte employé</a>
<?php if (empty($employes)): ?>
    <p>Aucun employé</p>
<?php else: ?>
    <table class="table">
        <thead>
            <tr>
                <th scope="col">Prénom</th>
                <th scope="col">Nom</th>
                <th scope="col">E-mail</th>
                <th scope="col">Statut</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($employes as $employe): ?>
                <tr>
                    <td><?= htmlspecialchars($employe['prenom']) ?></td>
                    <td><?= htmlspecialchars($employe['nom']) ?></td>
                    <td><?= htmlspecialchars($employe['email']) ?></td>
                    <td><?= $employe['actif'] ? '<span class="badge bg-success">Actif</span>' : '<span class="badge bg-secondary">Désactivé</span>' ?></td>
                </tr>
            <?php endforeach ?>
        </tbody>
    </table>
<?php endif ?>

<?php require __DIR__ . '/../morceaux/footer.php'; ?>