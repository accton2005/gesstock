<?php include dirname(__DIR__) . '/layout.php'; ?>

<div style="margin-bottom: 2rem;">
    <a href="/approvisionnements/create" class="btn btn-primary">➕ Nouveau Bon d'Entrée</a>
</div>

<table>
    <thead>
        <tr>
            <th>Numéro</th>
            <th>Fournisseur</th>
            <th>Statut</th>
            <th>Date Livraison Prévue</th>
            <th>Articles</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($bons as $bon): ?>
        <tr>
            <td><strong><?= htmlspecialchars($bon['numero_bon']) ?></strong></td>
            <td><?= htmlspecialchars($bon['fournisseur']) ?></td>
            <td>
                <span class="badge badge-<?= $bon['statut'] === 'livree' ? 'success' : 'warning' ?>">
                    <?= ucfirst($bon['statut']) ?>
                </span>
            </td>
            <td><?= date('d/m/Y', strtotime($bon['date_livraison_prevue'])) ?></td>
            <td><?= $bon['articles_count'] ?></td>
            <td>
                <a href="/approvisionnements/<?= $bon['id'] ?>" class="btn btn-primary" style="padding: 0.4rem 0.8rem; font-size: 0.8rem;">Voir</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php if (empty($bons)): ?>
<div class="alert alert-info" style="margin-top: 2rem;">
    Aucun bon d'entrée. <a href="/approvisionnements/create">Créer le premier bon</a>
</div>
<?php endif; ?>
