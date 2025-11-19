<?php include dirname(__DIR__) . '/layout.php'; ?>

<div style="margin-bottom: 2rem;">
    <a href="/articles/create" class="btn btn-primary">➕ Créer un Article</a>
    
    <form method="GET" style="display: inline; margin-left: 1rem;">
        <input type="search" name="search" placeholder="Rechercher..." value="<?= htmlspecialchars($search) ?>" style="padding: 0.5rem; border: 1px solid #ddd; border-radius: 4px;">
        <button type="submit" class="btn btn-secondary" style="padding: 0.5rem 1rem;">Rechercher</button>
    </form>
</div>

<table>
    <thead>
        <tr>
            <th>Code</th>
            <th>Désignation</th>
            <th>Catégorie</th>
            <th>Stock Min</th>
            <th>Stock Max</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($articles as $article): ?>
        <tr>
            <td><strong><?= htmlspecialchars($article['code_interne']) ?></strong></td>
            <td><?= htmlspecialchars($article['designation']) ?></td>
            <td><?= htmlspecialchars($article['categorie']) ?></td>
            <td><?= $article['stock_min'] ?></td>
            <td><?= $article['stock_max'] ?></td>
            <td>
                <a href="/articles/<?= $article['id'] ?>" class="btn btn-primary" style="padding: 0.4rem 0.8rem; font-size: 0.8rem;">Voir</a>
                <a href="/articles/<?= $article['id'] ?>/edit" class="btn btn-secondary" style="padding: 0.4rem 0.8rem; font-size: 0.8rem;">Éditer</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php if (empty($articles)): ?>
<div class="alert alert-info" style="margin-top: 2rem;">
    Aucun article trouvé. <a href="/articles/create">Créer le premier article</a>
</div>
<?php endif; ?>
