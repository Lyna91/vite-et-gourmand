<?php

/** @var bool $erreur */
/** @var bool $modifie */
/** @var Horaire[] $horaires */
/** @var string $csrf */
require __DIR__ . '/../morceaux/header.php'; ?>
<h1>Gérer les horaires</h1>
<a href="/employe" class="btn btn-outline-secondary mb-3">← Retour à l'espace employé</a>
<?php if ($modifie): ?>
    <div class="alert alert-success">
        <p>Horaires modifiés ✓</p>
    </div>
<?php endif ?>
<?php if ($erreur): ?>
    <div class="alert alert-danger">
        <p>Une erreur est survenue.</p>
    </div>
<?php endif ?>
<div>
    <table class="table">
        <thead>
            <tr>
                <th scope="col">Jour</th>
                <th scope="col" colspan="3"> Ouverture . Fermeture</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($horaires as $horaire): ?>
                <tr>
                    <td><?= htmlspecialchars($horaire->getJour()) ?></td>
                    <td colspan="3">
                        <form method="post" action="/employe/horaires/modifier" class="d-flex gap-2 align-items-center">
                            <input type="hidden" name="csrf" value="<?= $csrf ?>">
                            <input type="hidden" name="id_horaire" value="<?= $horaire->getId() ?>">
                            <input type="time" name="ouverture" class="form-control form-control-sm"
                                value="<?= substr($horaire->getHeureOuverture() ?? '', 0, 5) ?>">
                            <input type="time" name="fermeture" class="form-control form-control-sm"
                                value="<?= substr($horaire->getHeureFermeture() ?? '', 0, 5) ?>">
                            <button type="submit" class="btn btn-sm btn-primary">Enregistrer</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach ?>
        </tbody>
    </table>
</div>
<?php require __DIR__ . '/../morceaux/footer.php'; ?>