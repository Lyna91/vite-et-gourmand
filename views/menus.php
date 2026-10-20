   <?php
    /** @var Menu[] $menus */
    /** @var string|null $erreur */
    ?>

   <?php require __DIR__ . '/morceaux/header.php'; ?>

   <h1>Nos menus</h1>
   <?php if ($erreur): ?>
       <div class="alert alert-danger"><?= htmlspecialchars($erreur) ?></div>
   <?php endif; ?>
   <div class="row">
       <?php foreach ($menus as $menu): ?>
           <div class="col-12 col-md-6 col-lg-4 mb-4">
               <div class="card h-100">
                   <div class="card-body">
                       <h2 class="card-title h5"><?= htmlspecialchars($menu->getTitre()) ?></h2>
                       <p class="card-text"><?= htmlspecialchars($menu->getDescription()) ?></p>
                       <p>À partir de <?= $menu->getNbrePersonneMini() ?> personnes</p>
                       <p><strong><?= number_format($menu->getPrixParPersonne(), 2, ',', ' ') ?> € / personne</strong></p>
                       <a href="/menu?id=<?= $menu->getId() ?>" class="btn btn-primary">Voir le détail</a>
                   </div>
               </div>
           </div>
       <?php endforeach; ?>
   </div>


   <?php require __DIR__ . '/morceaux/footer.php'; ?>