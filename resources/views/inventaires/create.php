<?php include dirname(__DIR__) . '/layout.php'; ?>

<div style="max-width: 600px;">
    <form method="POST" action="/inventaires">
        <div class="form-group">
            <label for="type">Type d'Inventaire *</label>
            <select id="type" name="type" required>
                <option value="periodique">Périodique</option>
                <option value="exceptionnel">Exceptionnel</option>
                <option value="partiel">Partiel</option>
            </select>
        </div>

        <div class="form-group">
            <label for="notes">Notes/Observations</label>
            <textarea id="notes" name="notes" rows="4"></textarea>
        </div>

        <div class="alert alert-info">
            Un nouvel inventaire sera créé pour tous les articles. Vous pourrez saisir les quantités comptées après création.
        </div>

        <button type="submit" class="btn btn-primary">Créer l'Inventaire</button>
        <a href="/inventaires" class="btn btn-secondary" style="margin-left: 0.5rem;">Annuler</a>
    </form>
</div>
