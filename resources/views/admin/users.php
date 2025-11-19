<?php include dirname(__DIR__) . '/layout.php'; ?>

<div style="margin-bottom: 2rem;">
    <a href="#" onclick="document.getElementById('addUserForm').style.display='block'; return false;" class="btn btn-primary">➕ Ajouter Utilisateur</a>
</div>

<div id="addUserForm" style="display:none; background: #f9f9f9; padding: 1.5rem; border-radius: 4px; margin-bottom: 2rem;">
    <h3>Ajouter un Utilisateur</h3>
    <form method="POST" action="/admin/users">
        <div class="form-group" style="max-width: 400px;">
            <label for="name">Nom *</label>
            <input type="text" id="name" name="name" required>
        </div>

        <div class="form-group" style="max-width: 400px;">
            <label for="email">Email *</label>
            <input type="email" id="email" name="email" required>
        </div>

        <div class="form-group" style="max-width: 400px;">
            <label for="password">Mot de passe *</label>
            <input type="password" id="password" name="password" required>
        </div>

        <div class="form-group" style="max-width: 400px;">
            <label for="role">Rôle *</label>
            <select id="role" name="role" required>
                <option value="consultateur">Consultateur</option>
                <option value="chef_service">Chef de Service</option>
                <option value="magasinier">Magasinier</option>
                <option value="admin">Administrateur</option>
            </select>
        </div>

        <div class="form-group" style="max-width: 400px;">
            <label for="department">Département</label>
            <input type="text" id="department" name="department">
        </div>

        <button type="submit" class="btn btn-primary">Créer</button>
        <button type="button" class="btn btn-secondary" onclick="document.getElementById('addUserForm').style.display='none';">Annuler</button>
    </form>
</div>

<table>
    <thead>
        <tr>
            <th>Nom</th>
            <th>Email</th>
            <th>Rôle</th>
            <th>Département</th>
            <th>Dernier Accès</th>
            <th>Actif</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($users as $user): ?>
        <tr>
            <td><?= htmlspecialchars($user['name']) ?></td>
            <td><?= htmlspecialchars($user['email']) ?></td>
            <td><span class="badge badge-info"><?= ucfirst(str_replace('_', ' ', $user['role'])) ?></span></td>
            <td><?= htmlspecialchars($user['department']) ?></td>
            <td><?= $user['last_login'] ? date('d/m/Y H:i', strtotime($user['last_login'])) : 'Jamais' ?></td>
            <td><?= $user['is_active'] ? '✅' : '❌' ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
