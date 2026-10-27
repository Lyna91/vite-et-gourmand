 <?php
    /** @var string[] $erreurs */
    require __DIR__ . '/morceaux/header.php'; ?>
 <h1>Connexion</h1>
 <?php if (!empty($erreurs)): ?>
     <div class="alert alert-danger">
         <ul class="mb-0">
             <?php foreach ($erreurs as $erreur): ?>
                 <li><?= htmlspecialchars($erreur) ?></li>
             <?php endforeach; ?>
         </ul>
     </div>
 <?php endif; ?>
 <form method="post" action="/connexion">
     <div class="mb-3">
         <label for="mail" class="form-label">Email</label>
         <input type="email" id="mail" name="mail" class="form-control" required>
     </div>
     <div class="mb-3">
         <label for="motDePasse" class="form-label">Mot de passe</label>
         <input type="password" id="motDePasse" name="motDePasse" class="form-control" required>
     </div>
     <button type="submit" class="btn btn-primary">Se connecter</button>
     <p class="mt-3"><a href="/mot-de-passe-oublie">Mot de passe oublié ?</a></p>
 </form>
 <?php require __DIR__ . '/morceaux/footer.php'; ?>