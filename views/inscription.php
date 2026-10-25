 <?php
    /** @var bool $succes */
    /** @var string[] $erreurs */

    require __DIR__ . '/morceaux/header.php'; ?>
 <h1>Créer un compte</h1>
 <?php if (!empty($erreurs)): ?>
     <div class="alert alert-danger">
         <ul class="mb-0">
             <?php foreach ($erreurs as $erreur): ?>
                 <li><?= htmlspecialchars($erreur) ?></li>
             <?php endforeach; ?>
         </ul>
     </div>
 <?php endif; ?>
 <?php if ($succes): ?>
     <div class="alert alert-success">
         <p class="mb-0">Votre compte a été créé. Vous pouvez maintenant vous connecter</p>
     </div>
 <?php endif; ?>
 <form method="post" action="/inscription">
     <div class="mb-3">
         <label for="nom" class="form-label">Votre nom</label>
         <input type="text" id="nom" name="nom" class="form-control" required>
     </div>
     <div class="mb-3">
         <label for="prenom" class="form-label">Votre prénom</label>
         <input type="text" id="prenom" name="prenom" class="form-control" required>
     </div>
     <div class="mb-3">
         <label for="mail" class="form-label">Email</label>
         <input type="email" id="mail" name="mail" class="form-control" required>
     </div>
     <div class="mb-3">
         <label for="telephone" class="form-label">Numéro de téléphone</label>
         <input type="tel" id="telephone" name="telephone" class="form-control" required>
     </div>
     <div class="mb-3">
         <label for="adresse" class="form-label">Adresse</label>
         <input type="text" id="adresse" name="adresse" class="form-control" required>
     </div>
     <div class="mb-3">
         <label for="cp" class="form-label">Code postal</label>
         <input type="text" id="cp" name="cp" class="form-control" required>
     </div>
     <div class="mb-3">
         <label for="ville" class="form-label">Ville</label>
         <input type="text" id="ville" name="ville" class="form-control" required>
     </div>

     <div class="mb-3">
         <label for="motDePasse" class="form-label">Mot de passe</label>
         <input type="password" id="motDePasse" name="motDePasse" class="form-control" minlength="10" required>
         <div class="form-text">10 caractères minimum, avec une majuscule, une minuscule, un chiffre et un caractère spécial.</div>
     </div>
     </div>

     <div class="mb-3">
         <label for="confirmation" class="form-label">Confirmez votre mot de passe</label>
         <input type="password" id="confirmation" name="confirmation" class="form-control" required>

         <button type="submit" class="btn btn-primary">Créer mon compte</button>
 </form>
 <?php require __DIR__ . '/morceaux/footer.php'; ?>