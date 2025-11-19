<?php include dirname(__DIR__) . '/layout.php'; ?>

<div style="max-width: 600px;">
    <form method="POST" action="/demandes">
        <div class="form-group">
            <label for="justification">Justification *</label>
            <textarea id="justification" name="justification" rows="5" required></textarea>
        </div>

        <div class="form-group">
            <label for="priorite">Priorité</label>
            <select id="priorite" name="priorite">
                <option value="faible">Faible</option>
                <option value="normale" selected>Normale</option>
                <option value="urgente">Urgente</option>
            </select>
        </div>

        <div class="form-group">
            <label>Articles</label>
            <?php foreach ($articles as $article): ?>
            <div style="padding: 1rem; background: #f9f9f9; border-radius: 4px; margin-bottom: 1rem;">
                <div style="display: flex; gap: 1rem; align-items: center;">
                    <div style="flex: 1;">
                        <strong><?= htmlspecialchars($article['designation']) ?></strong><br>
                        <small><?= htmlspecialchars($article['code_interne']) ?></small>
                    </div>
                    <input type="number" name="articles[<?= $article['id'] ?>]" 
                           placeholder="Quantité" min="0" style="width: 120px;">
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <button type="submit" class="btn btn-primary">Soumettre la Demande</button>
        <a href="/demandes" class="btn btn-secondary" style="margin-left: 0.5rem;">Annuler</a>
    </form>
</div>
