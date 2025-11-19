<?php include dirname(__DIR__) . '/layout.php'; ?>

<div style="max-width: 600px;">
    <form method="POST" action="/approvisionnements">
        <div class="form-group">
            <label for="fournisseur_id">Fournisseur *</label>
            <select id="fournisseur_id" name="fournisseur_id" required>
                <option value="">-- Sélectionner --</option>
                <?php foreach ($fournisseurs as $f): ?>
                <option value="<?= $f['id'] ?>"><?= htmlspecialchars($f['nom']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="date_commande">Date Commande</label>
            <input type="date" id="date_commande" name="date_commande" value="<?= date('Y-m-d') ?>">
        </div>

        <div class="form-group">
            <label for="date_livraison_prevue">Date Livraison Prévue</label>
            <input type="date" id="date_livraison_prevue" name="date_livraison_prevue">
        </div>

        <div class="form-group">
            <label for="montant_total">Montant Total (€)</label>
            <input type="number" id="montant_total" name="montant_total" step="0.01" value="0">
        </div>

        <div class="form-group">
            <label for="notes">Notes</label>
            <textarea id="notes" name="notes" rows="4"></textarea>
        </div>

        <button type="submit" class="btn btn-primary">Créer le Bon</button>
        <a href="/approvisionnements" class="btn btn-secondary" style="margin-left: 0.5rem;">Annuler</a>
    </form>
</div>
