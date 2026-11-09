<?php require __DIR__ . '/../morceaux/header.php'; ?>
<h1>Gérer les menus</h1>
<?php if (empty($menus)): ?>
    <p>Aucun menu</p>
<?php else: ?>
    <table class="table">
        <thead>
            <tr>
                <th scope="col">Titre</th>
                <th scope="col">Prix par personne</th>
                <th scope="col">Nombre personne minimum</th>
                <th scope="col">Stock</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($menus as $menu): ?>
                <tr>
                    <td><?= htmlspecialchars($menu->getTitre()) ?></td>
                    <td><?= number_format($menu->getPrixParPersonne(), 2, ',', ' ') ?></td>
                    <td><?= $menu->getNbrePersonneMini() ?> </td>
                    <td><?= $menu->getStock() ?></td>
                </tr>
            <?php endforeach ?>
        </tbody>
    </table>
<?php endif ?>
<?php require __DIR__ . '/../morceaux/footer.php'; ?>