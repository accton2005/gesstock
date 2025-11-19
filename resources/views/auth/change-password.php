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

    <form method="POST" action="/change-password">
        <div class="form-group">
            <label for="current_password">Mot de passe actuel *</label>
            <input type="password" id="current_password" name="current_password" required>
        </div>

        <div class="form-group">
            <label for="new_password">Nouveau mot de passe *</label>
            <input type="password" id="new_password" name="new_password" required minlength="8">
            <small>Minimum 8 caractères</small>
        </div>

        <div class="form-group">
            <label for="confirm_password">Confirmez le mot de passe *</label>
            <input type="password" id="confirm_password" name="confirm_password" required minlength="8">
        </div>

        <button type="submit" class="btn btn-primary">Changer le Mot de Passe</button>
        <a href="/dashboard" class="btn btn-secondary" style="margin-left: 0.5rem;">Annuler</a>
    </form>
</div>
