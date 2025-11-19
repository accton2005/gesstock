<?php include dirname(__DIR__) . '/layout.php'; ?>

<div style="max-width: 600px;">
    <form method="POST" action="/articles/<?= $article['id'] ?>">
        <div class="form-group">
            <label for="designation">Désignation *</label>
            <input type="text" id="designation" name="designation" value="<?= htmlspecialchars($article['designation']) ?>" required>
        </div>

        <div class="form-group">
            <label for="description">Description</label>
            <textarea id="description" name="description" rows="4"><?= htmlspecialchars($article['description'] ?? '') ?></textarea>
        </div>

        <div class="form-group">
            <label for="categorie">Catégorie *</label>
            <input type="text" id="categorie" name="categorie" value="<?= htmlspecialchars($article['categorie']) ?>" required>
        </div>

        <div class="form-group">
            <label for="stock_min">Stock Minimum</label>
            <input type="number" id="stock_min" name="stock_min" value="<?= $article['stock_min'] ?>">
        </div>

        <div class="form-group">
            <label for="stock_max">Stock Maximum</label>
            <input type="number" id="stock_max" name="stock_max" value="<?= $article['stock_max'] ?>">
        </div>

        <div class="form-group">
            <label for="stock_critique">Stock Critique</label>
            <input type="number" id="stock_critique" name="stock_critique" value="<?= $article['stock_critique'] ?>">
        </div>

        <div class="form-group">
            <label for="prixunitaire">Prix Unitaire</label>
            <input type="number" id="prixunitaire" name="prixunitaire" step="0.01" value="<?= $article['prixunitaire'] ?>">
        </div>

        <button type="submit" class="btn btn-primary">Mettre à jour</button>
        <a href="/articles/<?= $article['id'] ?>" class="btn btn-secondary" style="margin-left: 0.5rem;">Annuler</a>
    </form>

    <div style="margin-top: 2rem; padding: 1rem; background: #fff3cd; border-radius: 4px;">
        <form method="POST" action="/articles/<?= $article['id'] ?>/archive" style="display:inline;">
            <label for="motif">Motif d'archivage:</label>
            <input type="text" id="motif" name="motif" placeholder="Raison de l'archivage" required>
            <button type="submit" class="btn btn-danger" onclick="return confirm('Êtes-vous sûr ?');">Archiver l'Article</button>
        </form>
    </div>
</div>
