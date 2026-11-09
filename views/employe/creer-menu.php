<?php require __DIR__ . '/../morceaux/header.php'; ?>

<form method="post"  action="/employe/annuler">
    <input type="hidden" name="id_commande" value="<?= htmlspecialchars($idCommande) ?>">
    <div class="mb-3">
        <label for="mode_contact" class="form-label">Mode de contact</label>
        <select id="mode_contact" name="mode_contact" class="form-select" required>
            <option value="">Choisissez</option>
            <option value="GSM">Par téléphone</option>
            <option value="mail">Par mail</option>
        </select>
    </div>
    <div class="mb-3">
        <label for="motif" class="form-label">Motif de l'annulation</label>
        <textarea id="motif" name="motif" class="form-control" rows="4" required><?= htmlspecialchars($_POST['motif'] ?? '') ?></textarea>
        <button type="submit" class="btn btn-danger">Confirmer l'annulation</button>
    </div>

</form>


<?php require __DIR__ . '/../morceaux/footer.php'; ?>