   <?php
    /** @var Menu[] $menus */
    /** @var string|null $erreur */
    /** @var string[] $themes */
    /** @var string[] $regimes */
    ?>

   <?php require __DIR__ . '/morceaux/header.php'; ?>

   <h1>Nos menus</h1>
   <?php if ($erreur): ?>
       <div class="alert alert-danger"><?= htmlspecialchars($erreur) ?></div>
   <?php endif; ?>
   <form class="row g-3 mb-4">
       <div class="col-12 col-md-4">
           <label for="prix-min" class="form-label">Prix minimum par personne (€)</label>
           <input type="number" id="prix-min" class="form-control" min="0" step="1">
           <label for="prix-max" class="form-label">Prix maximum par personne (€)</label>
           <input type="number" id="prix-max" class="form-control" min="0" step="1">
           <label for="nbre-personnes" class="form-label">Nombre de personnes</label>
           <input type="number" id="nbre-personnes" class="form-control" min="1" step="1">
           <div class="col-12 col-md-4">
               <label for="theme" class="form-label">Thème</label>
               <select id="theme" class="form-select">
                   <option value="">Tous les thèmes</option>
                   <?php foreach ($themes as $theme): ?>
                       <option value="<?= htmlspecialchars($theme) ?>"><?= htmlspecialchars($theme) ?></option>
                   <?php endforeach; ?>
               </select>
           </div>
           <div class="col-12 col-md-4">
               <label for="regime" class="form-label">Régime</label>
               <select id="regime" class="form-select">
                   <option value="">Tous les régimes</option>
                   <?php foreach ($regimes as $regime): ?>
                       <option value="<?= htmlspecialchars($regime) ?>"><?= htmlspecialchars($regime) ?></option>
                   <?php endforeach; ?>
               </select>
           </div>
       </div>
   </form>
   <div class="row" id="liste-menus">>
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

   <script src="/js/menus.js"></script>
   <?php require __DIR__ . '/morceaux/footer.php'; ?>