<?php include dirname(__DIR__) . '/layout.php'; ?>

<div style="margin-bottom: 2rem;">
    <a href="/stock/mouvements" class="btn btn-primary">📊 Voir Mouvements</a>
    <a href="/stock" class="btn btn-secondary">🔄 Nouveau Mouvement</a>
</div>

<?php if (!empty($articlesEnAlerte)): ?>
<div class="card" style="margin-bottom: 2rem;">
    <h3 style="color: #dc3545;">⚠️ Stock Critique</h3>
    <table>
        <thead>
            <tr>
                <th>Code</th>
                <th>Désignation</th>
                <th>Stock Actuel</th>
                <th>Seuil Min</th>
                <th>Écart</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($articlesEnAlerte as $article): ?>
            <tr>
                <td><?= htmlspecialchars($article['code_interne']) ?></td>
                <td><?= htmlspecialchars($article['designation']) ?></td>
                <td><span class="badge badge-danger"><?= $article['stock_actuel'] ?></span></td>
                <td><?= $article['stock_min'] ?></td>
                <td><?= $article['stock_actuel'] - $article['stock_min'] ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php endif; ?>

<div class="card">
    <h3>📦 Tous les Articles</h3>
    <table>
        <thead>
            <tr>
                <th>Code</th>
                <th>Désignation</th>
                <th>Stock</th>
                <th>Min</th>
                <th>Max</th>
                <th>Catégorie</th>
                <th>État</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($articles as $article): ?>
            <tr>
                <td><strong><?= htmlspecialchars($article['code_interne']) ?></strong></td>
                <td><?= htmlspecialchars($article['designation']) ?></td>
                <td><?= $article['stock_actuel'] ?? 0 ?></td>
                <td><?= $article['stock_min'] ?></td>
                <td><?= $article['stock_max'] ?></td>
                <td><?= htmlspecialchars($article['categorie']) ?></td>
                <td>
                    <?php 
                    $stock = $article['stock_actuel'] ?? 0;
                    if ($stock <= $article['stock_critique']) {
                        echo '<span class="badge badge-danger">Critique</span>';
                    } elseif ($stock <= $article['stock_min']) {
                        echo '<span class="badge badge-warning">Faible</span>';
                    } else {
                        echo '<span class="badge badge-success">Normal</span>';
                    }
                    ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
