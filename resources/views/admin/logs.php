<?php include dirname(__DIR__) . '/layout.php'; ?>

<table>
    <thead>
        <tr>
            <th>Timestamp</th>
            <th>Utilisateur</th>
            <th>Action</th>
            <th>Table</th>
            <th>ID Record</th>
            <th>Détails</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($logs as $log): ?>
        <tr>
            <td><small><?= date('d/m/Y H:i:s', strtotime($log['timestamp'])) ?></small></td>
            <td><?= htmlspecialchars($log['user_name'] ?? 'Système') ?></td>
            <td><span class="badge badge-info"><?= htmlspecialchars($log['action']) ?></span></td>
            <td><?= htmlspecialchars($log['table_name']) ?></td>
            <td><?= $log['record_id'] ?></td>
            <td>
                <details style="font-size: 0.85rem;">
                    <summary>Voir</summary>
                    <?php if ($log['old_values']): ?>
                        <strong>Ancien:</strong> <code><?= htmlspecialchars(substr($log['old_values'], 0, 100)) ?></code><br>
                    <?php endif; ?>
                    <?php if ($log['new_values']): ?>
                        <strong>Nouveau:</strong> <code><?= htmlspecialchars(substr($log['new_values'], 0, 100)) ?></code>
                    <?php endif; ?>
                </details>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php if (empty($logs)): ?>
<div class="alert alert-info" style="margin-top: 2rem;">
    Aucun log enregistré.
</div>
<?php endif; ?>
