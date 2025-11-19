<?php include dirname(__DIR__) . '/layout.php'; ?>

<div style="margin-bottom: 2rem;">
    <a href="/demandes/create" class="btn btn-primary">➕ Nouvelle Demande</a>
</div>

<table>
    <thead>
        <tr>
            <th>Numéro</th>
            <th>Service</th>
            <th>Priorité</th>
            <th>Statut</th>
            <th>Date</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($demandes as $demande): ?>
        <tr>
            <td><strong><?= htmlspecialchars($demande['numero_demande']) ?></strong></td>
            <td><?= htmlspecialchars($demande['service_demandeur'] ?? 'N/A') ?></td>
            <td>
                <span class="badge badge-<?= $demande['priorite'] === 'urgente' ? 'danger' : ($demande['priorite'] === 'normale' ? 'warning' : 'info') ?>">
                    <?= ucfirst($demande['priorite']) ?>
                </span>
            </td>
            <td>
                <span class="badge badge-<?= $demande['statut'] === 'validee' ? 'success' : 'warning' ?>">
                    <?= ucfirst(str_replace('_', ' ', $demande['statut'])) ?>
                </span>
            </td>
            <td><?= date('d/m/Y', strtotime($demande['created_at'])) ?></td>
            <td>
                <a href="/demandes/<?= $demande['id'] ?>" class="btn btn-primary" style="padding: 0.4rem 0.8rem; font-size: 0.8rem;">Voir</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php if (empty($demandes)): ?>
<div class="alert alert-info" style="margin-top: 2rem;">
    Aucune demande. <a href="/demandes/create">Créer une nouvelle demande</a>
</div>
<?php endif; ?>
