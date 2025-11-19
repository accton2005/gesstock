<?php include dirname(__DIR__) . '/layout.php'; ?>

<div class="card">
    <h3>Informations de l'Inventaire</h3>
    <table style="width: 50%;">
        <tr><td><strong>Numéro:</strong></td><td><?= htmlspecialchars($inventaire['numero_inventaire']) ?></td></tr>
        <tr><td><strong>Type:</strong></td><td><?= ucfirst($inventaire['type']) ?></td></tr>
        <tr><td><strong>Statut:</strong></td><td><span class="badge badge-<?= $inventaire['statut'] === 'termine' ? 'success' : 'warning' ?>"><?= ucfirst($inventaire['statut']) ?></span></td></tr>
        <tr><td><strong>Début:</strong></td><td><?= date('d/m/Y H:i', strtotime($inventaire['date_debut'])) ?></td></tr>
        <tr><td><strong>Fin:</strong></td><td><?= $inventaire['date_fin'] ? date('d/m/Y H:i', strtotime($inventaire['date_fin'])) : 'En cours' ?></td></tr>
    </table>
</div>

<?php if (!empty($ecarts)): ?>
<div class="card" style="margin-top: 2rem;">
    <h3 style="color: #dc3545;">⚠️ Écarts Détectés</h3>
    <table>
        <thead>
            <tr>
                <th>Code</th>
                <th>Désignation</th>
                <th>Attendu</th>
                <th>Compté</th>
                <th>Écart</th>
                <th>Observation</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($ecarts as $ecart): ?>
            <tr style="background: <?= $ecart['difference'] > 0 ? '#d4edda' : '#f8d7da' ?>;">
                <td><?= htmlspecialchars($ecart['code_interne']) ?></td>
                <td><?= htmlspecialchars($ecart['designation']) ?></td>
                <td><?= $ecart['quantite_attendue'] ?></td>
                <td><?= $ecart['quantite_comptee'] ?></td>
                <td><strong><?= $ecart['difference'] > 0 ? '+' : '' ?><?= $ecart['difference'] ?></strong></td>
                <td><?= htmlspecialchars($ecart['observation'] ?? '-') ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php endif; ?>

<?php if (!empty($articles)): ?>
<div class="card" style="margin-top: 2rem;">
    <h3>Tous les Articles</h3>
    <table>
        <thead>
            <tr>
                <th>Code</th>
                <th>Désignation</th>
                <th>Attendu</th>
                <th>Compté</th>
                <th>Statut</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($articles as $art): ?>
            <tr>
                <td><?= htmlspecialchars($art['code_interne']) ?></td>
                <td><?= htmlspecialchars($art['designation']) ?></td>
                <td><?= $art['quantite_attendue'] ?></td>
                <td><?= $art['quantite_comptee'] ?? '-' ?></td>
                <td><?= $art['verifiee'] ? '✅ Vérifié' : '⏳ En attente' ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php endif; ?>

<div style="margin-top: 1rem;">
    <a href="/inventaires" class="btn btn-secondary">Retour</a>
</div>
