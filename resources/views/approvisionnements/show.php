<?php include dirname(__DIR__) . '/layout.php'; ?>

<div class="card">
    <h3>Informations du Bon</h3>
    <table style="width: 50%;">
        <tr><td><strong>Numéro:</strong></td><td><?= htmlspecialchars($bon['numero_bon']) ?></td></tr>
        <tr><td><strong>Fournisseur:</strong></td><td><?= htmlspecialchars($bon['fournisseur']) ?></td></tr>
        <tr><td><strong>Statut:</strong></td><td><span class="badge badge-<?= $bon['statut'] === 'livree' ? 'success' : 'warning' ?>"><?= ucfirst($bon['statut']) ?></span></td></tr>
        <tr><td><strong>Date Livraison Prévue:</strong></td><td><?= date('d/m/Y', strtotime($bon['date_livraison_prevue'])) ?></td></tr>
        <tr><td><strong>Date Livraison Réelle:</strong></td><td><?= $bon['date_livraison_relle'] ? date('d/m/Y', strtotime($bon['date_livraison_relle'])) : 'En attente' ?></td></tr>
        <tr><td><strong>Montant:</strong></td><td><?= number_format($bon['montant_total'], 2) ?> €</td></tr>
    </table>
</div>

<?php if (!empty($details)): ?>
<div class="card" style="margin-top: 2rem;">
    <h3>Articles du Bon</h3>
    <table>
        <thead>
            <tr>
                <th>Code</th>
                <th>Désignation</th>
                <th>Quantité Commandée</th>
                <th>Quantité Livrée</th>
                <th>Prix Unitaire</th>
                <th>Montant</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($details as $det): ?>
            <tr>
                <td><?= htmlspecialchars($det['code_interne']) ?></td>
                <td><?= htmlspecialchars($det['designation']) ?></td>
                <td><?= $det['quantite_commandee'] ?></td>
                <td><?= $det['quantite_livree'] ?></td>
                <td><?= number_format($det['prix_unitaire'], 2) ?> €</td>
                <td><?= number_format($det['montant'], 2) ?> €</td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php endif; ?>

<div style="margin-top: 1rem;">
    <a href="/approvisionnements" class="btn btn-secondary">Retour</a>
</div>
