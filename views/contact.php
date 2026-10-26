<?php 
/** @var string[] $erreurs */
/** @var bool $envoye */
require __DIR__ . '/morceaux/header.php'; ?>

<h1>Nous contacter</h1>
<?php if ($envoye): ?>
     <div class="alert alert-success">
         <p class="mb-0">Votre message a été envoyé</p>
     </div>
 <?php endif; ?>
<?php if (!empty($erreurs)): ?>
     <div class="alert alert-danger">
         <ul class="mb-0">
             <?php foreach ($erreurs as $erreur): ?>
                 <li><?= htmlspecialchars($erreur) ?></li>
             <?php endforeach; ?>
         </ul>
     </div>
 <?php endif; ?>
<form method="post" action="/contact">
     <div class="mb-3">
         <label for="mail" class="form-label">Votre mail</label>
         <input type="email" id="mail" name="mail" class="form-control" required>
     </div>
     <div class="mb-3">
         <label for="titre" class="form-label">Titre du message</label>
         <input type="text" id="titre" name="titre" class="form-control" required>
     </div>
     <div class="mb-3">
    <label for="message" class="form-label">Votre message</label>
    <textarea id="message" name="message" class="form-control" rows="6" placeholder="Votre message ici" required></textarea>
</div>
      <button type="submit" class="btn btn-primary">Envoyer</button>
</form>
     
<?php require __DIR__ . '/morceaux/footer.php'; ?>