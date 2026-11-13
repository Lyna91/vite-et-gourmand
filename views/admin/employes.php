<?php

/** @var array $modifie */
/** @var bool $cree */
/** @var bool $erreur */
/** @var array $employes */
/** @var string $csrf */
require __DIR__ . '/../morceaux/header.php'; ?>
<h1>Gérer les employés</h1>
<a href="/employe" class="btn btn-outline-secondary mb-3">← Retour à l'espace employé</a>
<a href="/admin/employes/creer" class="btn btn-primary mb-3">Créer un compte employé</a>
<?php if ($cree): ?>
    <div class="alert alert-success">
        <p>Compte employé créé ✓</p>
    </div>
<?php endif ?>
<?php if ($modifie): ?>
    <div class="alert alert-success">
        <p>Compte employé modifié ✓</p>
    </div>
<?php endif ?>
<?php if ($erreur): ?>
    <div class="alert alert-danger">
        <p>Une erreur est survenue</p>
    </div>
<?php endif ?>
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
                <th scope="col">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($employes as $employe): ?>
                <tr>
                    <td><?= htmlspecialchars($employe['prenom']) ?></td>
                    <td><?= htmlspecialchars($employe['nom']) ?></td>
                    <td><?= htmlspecialchars($employe['email']) ?></td>
                    <td><?= $employe['actif'] ? '<span class="badge bg-success">Actif</span>' : '<span class="badge bg-secondary">Désactivé</span>' ?></td>
                    <td>
                        <form method="post" action="/admin/employes/actif" class="d-inline">
                            <input type="hidden" name="csrf" value="<?= $csrf ?>">
                            <input type="hidden" name="id_utilisateur" value="<?= $employe['id_utilisateur'] ?>">
                            <input type="hidden" name="actif" value="<?= $employe['actif'] ? 0 : 1 ?>">
                            <button type="submit" class="btn btn-sm <?= $employe['actif'] ? 'btn-outline-danger' : 'btn-outline-success' ?>">
                                <?= $employe['actif'] ? 'Désactiver' : 'Réactiver' ?>
                            </button>
                        </form>
                    </td>
                </tr>
            <?php endforeach ?>
        </tbody>
    </table>
<?php endif ?>

<?php require __DIR__ . '/../morceaux/footer.php'; ?>