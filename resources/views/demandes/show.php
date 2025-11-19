<?php include dirname(__DIR__) . '/layout.php'; ?>

<div class="card">
    <h3>Informations de la Demande</h3>
    <table style="width: 50%;">
        <tr><td><strong>Numéro:</strong></td><td><?= htmlspecialchars($demande['numero_demande']) ?></td></tr>
        <tr><td><strong>Statut:</strong></td><td><span class="badge badge-info"><?= ucfirst(str_replace('_', ' ', $demande['statut'])) ?></span></td></tr>
        <tr><td><strong>Priorité:</strong></td><td><?= ucfirst($demande['priorite']) ?></td></tr>
        <tr><td><strong>Date:</strong></td><td><?= date('d/m/Y H:i', strtotime($demande['created_at'])) ?></td></tr>
        <tr><td><strong>Justification:</strong></td><td><?= htmlspecialchars($demande['justification']) ?></td></tr>
    </table>
</div>

<?php if (!empty($articles)): ?>
<div class="card" style="margin-top: 2rem;">
    <h3>Articles Demandés</h3>
    <table>
        <thead>
            <tr>
                <th>Code</th>
                <th>Désignation</th>
                <th>Quantité Demandée</th>
                <th>Quantité Accordée</th>
                <th>Quantité Distribuée</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($articles as $art): ?>
            <tr>
                <td><?= htmlspecialchars($art['code_interne']) ?></td>
                <td><?= htmlspecialchars($art['designation']) ?></td>
                <td><?= $art['quantite_demandee'] ?></td>
                <td><?= $art['quantite_accordee'] ?></td>
                <td><?= $art['quantite_distribuee'] ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php endif; ?>

<div style="margin-top: 1rem;">
    <a href="/demandes" class="btn btn-secondary">Retour</a>
</div>
