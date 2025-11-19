<?php include dirname(__DIR__) . '/layout.php'; ?>

<div style="margin-bottom: 2rem;">
    <a href="/inventaires/create" class="btn btn-primary">➕ Nouvel Inventaire</a>
</div>

<table>
    <thead>
        <tr>
            <th>Numéro</th>
            <th>Type</th>
            <th>Statut</th>
            <th>Début</th>
            <th>Fin</th>
            <th>Responsable</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($inventaires as $inv): ?>
        <tr>
            <td><strong><?= htmlspecialchars($inv['numero_inventaire']) ?></strong></td>
            <td><?= ucfirst($inv['type']) ?></td>
            <td>
                <span class="badge badge-<?= $inv['statut'] === 'termine' ? 'success' : 'warning' ?>">
                    <?= ucfirst($inv['statut']) ?>
                </span>
            </td>
            <td><?= date('d/m/Y H:i', strtotime($inv['date_debut'])) ?></td>
            <td><?= $inv['date_fin'] ? date('d/m/Y H:i', strtotime($inv['date_fin'])) : '-' ?></td>
            <td><?= htmlspecialchars($inv['responsable'] ?? 'N/A') ?></td>
            <td>
                <a href="/inventaires/<?= $inv['id'] ?>" class="btn btn-primary" style="padding: 0.4rem 0.8rem; font-size: 0.8rem;">Voir</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php if (empty($inventaires)): ?>
<div class="alert alert-info" style="margin-top: 2rem;">
    Aucun inventaire. <a href="/inventaires/create">Créer le premier inventaire</a>
</div>
<?php endif; ?>
