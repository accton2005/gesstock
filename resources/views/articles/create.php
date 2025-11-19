<?php include dirname(__DIR__) . '/layout.php'; ?>

<div style="max-width: 600px;">
    <?php if (isset($_SESSION['errors'])): ?>
    <div class="alert alert-error">
        <ul>
        <?php foreach ($_SESSION['errors'] as $error): ?>
            <li><?= htmlspecialchars($error) ?></li>
        <?php endforeach; ?>
        </ul>
        <?php unset($_SESSION['errors']); ?>
    </div>
    <?php endif; ?>

    <form method="POST" action="/articles">
        <div class="form-group">
            <label for="code_interne">Code Interne *</label>
            <input type="text" id="code_interne" name="code_interne" required>
        </div>

        <div class="form-group">
            <label for="designation">Désignation *</label>
            <input type="text" id="designation" name="designation" required>
        </div>

        <div class="form-group">
            <label for="description">Description</label>
            <textarea id="description" name="description" rows="4"></textarea>
        </div>

        <div class="form-group">
            <label for="categorie">Catégorie *</label>
            <input type="text" id="categorie" name="categorie" required>
        </div>

        <div class="form-group">
            <label for="unite_base">Unité de Base</label>
            <select id="unite_base" name="unite_base">
                <option>piece</option>
                <option>kg</option>
                <option>litre</option>
                <option>metre</option>
                <option>carton</option>
                <option>colis</option>
            </select>
        </div>

        <div class="form-group">
            <label for="stock_min">Stock Minimum</label>
            <input type="number" id="stock_min" name="stock_min" value="0">
        </div>

        <div class="form-group">
            <label for="stock_max">Stock Maximum</label>
            <input type="number" id="stock_max" name="stock_max" value="0">
        </div>

        <div class="form-group">
            <label for="stock_critique">Stock Critique</label>
            <input type="number" id="stock_critique" name="stock_critique" value="0">
        </div>

        <div class="form-group">
            <label for="prixunitaire">Prix Unitaire</label>
            <input type="number" id="prixunitaire" name="prixunitaire" step="0.01" value="0">
        </div>

        <button type="submit" class="btn btn-primary">Créer l'Article</button>
        <a href="/articles" class="btn btn-secondary" style="margin-left: 0.5rem;">Annuler</a>
    </form>
</div>
