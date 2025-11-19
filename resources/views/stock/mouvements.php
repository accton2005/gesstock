<?php include dirname(__DIR__) . '/layout.php'; ?>

<div style="margin-bottom: 2rem;">
    <form method="GET" style="display: grid; grid-template-columns: auto auto auto auto auto; gap: 1rem; align-items: end;">
        <div>
            <label>Type</label>
            <select name="type">
                <option value="">Tous</option>
                <option value="entree" <?= $type === 'entree' ? 'selected' : '' ?>>Entrée</option>
                <option value="sortie" <?= $type === 'sortie' ? 'selected' : '' ?>>Sortie</option>
                <option value="inventaire" <?= $type === 'inventaire' ? 'selected' : '' ?>>Inventaire</option>
            </select>
        </div>
        <div>
            <label>De</label>
            <input type="date" name="date_debut" value="<?= htmlspecialchars($dateDebut) ?>">
        </div>
        <div>
            <label>À</label>
            <input type="date" name="date_fin" value="<?= htmlspecialchars($dateFin) ?>">
        </div>
        <button type="submit" class="btn btn-primary">Filtrer</button>
        <a href="/stock/mouvements" class="btn btn-secondary">Réinitialiser</a>
    </form>
</div>

<table>
    <thead>
        <tr>
            <th>Date</th>
            <th>Type</th>
            <th>Article</th>
            <th>Code</th>
            <th>Quantité</th>
            <th>Justification</th>
            <th>Utilisateur</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($mouvements as $mvt): ?>
        <tr>
            <td><?= date('d/m/Y H:i', strtotime($mvt['date_mouvement'])) ?></td>
            <td><span class="badge badge-<?= $mvt['type'] === 'entree' ? 'success' : 'danger' ?>"><?= ucfirst($mvt['type']) ?></span></td>
            <td><?= htmlspecialchars($mvt['designation']) ?></td>
            <td><?= htmlspecialchars($mvt['code_interne']) ?></td>
            <td><?= $mvt['quantite'] ?></td>
            <td><small><?= htmlspecialchars(substr($mvt['justification'], 0, 50)) ?></small></td>
            <td><?= htmlspecialchars($mvt['name']) ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php if (empty($mouvements)): ?>
<div class="alert alert-info" style="margin-top: 2rem;">
    Aucun mouvement trouvé pour les critères sélectionnés.
</div>
<?php endif; ?>
