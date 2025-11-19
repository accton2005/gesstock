<?php include dirname(__DIR__) . '/layout.php'; ?>

<div class="card">
    <h3>Informations Générales</h3>
    <table style="width: 50%;">
        <tr>
            <td><strong>Code Interne:</strong></td>
            <td><?= htmlspecialchars($article['code_interne']) ?></td>
        </tr>
        <tr>
            <td><strong>Désignation:</strong></td>
            <td><?= htmlspecialchars($article['designation']) ?></td>
        </tr>
        <tr>
            <td><strong>Catégorie:</strong></td>
            <td><?= htmlspecialchars($article['categorie']) ?></td>
        </tr>
        <tr>
            <td><strong>Stock Actuel:</strong></td>
            <td><span class="badge badge-info"><?= $stockActuel ?></span></td>
        </tr>
        <tr>
            <td><strong>Stock Min/Max:</strong></td>
            <td><?= $article['stock_min'] ?> / <?= $article['stock_max'] ?></td>
        </tr>
        <tr>
            <td><strong>Prix Unitaire:</strong></td>
            <td><?= number_format($article['prixunitaire'], 2) ?> €</td>
        </tr>
    </table>

    <div style="margin-top: 1rem;">
        <a href="/articles/<?= $article['id'] ?>/edit" class="btn btn-secondary">Éditer</a>
        <a href="/articles" class="btn btn-secondary">Retour</a>
    </div>
</div>

<?php if (!empty($mouvements)): ?>
<div class="card" style="margin-top: 2rem;">
    <h3>Derniers Mouvements</h3>
    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Type</th>
                <th>Quantité</th>
                <th>Justification</th>
                <th>Utilisateur</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach (array_slice($mouvements, 0, 10) as $mvt): ?>
            <tr>
                <td><?= date('d/m/Y H:i', strtotime($mvt['date_mouvement'])) ?></td>
                <td><span class="badge badge-<?= $mvt['type'] === 'entree' ? 'success' : 'danger' ?>"><?= ucfirst($mvt['type']) ?></span></td>
                <td><?= $mvt['quantite'] ?></td>
                <td><?= htmlspecialchars(substr($mvt['justification'], 0, 50)) ?></td>
                <td><?= htmlspecialchars($mvt['name']) ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php endif; ?>
