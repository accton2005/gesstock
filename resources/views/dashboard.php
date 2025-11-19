<?php include 'layout.php'; ?>

<div class="grid">
    <div class="card">
        <h3>📦 Articles</h3>
        <div class="stat-number"><?= $stats['articlesTotal'] ?? 0 ?></div>
        <div class="stat-label">Articles en base</div>
    </div>

    <div class="card">
        <h3>⚠️ Alertes Stock</h3>
        <div class="stat-number"><?= $stats['articlesEnAlerte'] ?? 0 ?></div>
        <div class="stat-label">Articles en dessous du seuil</div>
    </div>

    <div class="card">
        <h3>📊 Mouvements Jour</h3>
        <div class="stat-number"><?= $stats['mouvementsJour'] ?? 0 ?></div>
        <div class="stat-label">Opérations enregistrées</div>
    </div>

    <div class="card">
        <h3>📋 Demandes</h3>
        <div class="stat-number"><?= $stats['demandesEnAttente'] ?? 0 ?></div>
        <div class="stat-label">En attente de validation</div>
    </div>

    <div class="card">
        <h3>👥 Utilisateurs</h3>
        <div class="stat-number"><?= $stats['utilisateurs'] ?? 0 ?></div>
        <div class="stat-label">Comptes actifs</div>
    </div>
</div>

<?php if (!empty($alertes)): ?>
<div class="card" style="margin-top: 2rem;">
    <h3>⚠️ Stock Critique</h3>
    <table>
        <thead>
            <tr>
                <th>Code</th>
                <th>Designation</th>
                <th>Stock Actuel</th>
                <th>Seuil Min</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach (array_slice($alertes, 0, 5) as $article): ?>
            <tr>
                <td><?= htmlspecialchars($article['code_interne']) ?></td>
                <td><?= htmlspecialchars($article['designation']) ?></td>
                <td><span class="badge badge-danger"><?= $article['stock_actuel'] ?? 0 ?></span></td>
                <td><?= $article['stock_min'] ?></td>
                <td>
                    <a href="/articles/<?= $article['id'] ?>" class="btn btn-primary" style="font-size: 0.8rem; padding: 0.5rem 1rem;">Détails</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php endif; ?>

<?php if (!empty($demandes)): ?>
<div class="card" style="margin-top: 2rem;">
    <h3>📋 Demandes en Attente</h3>
    <table>
        <thead>
            <tr>
                <th>Numéro</th>
                <th>Service</th>
                <th>Priorité</th>
                <th>Date</th>
                <th>Statut</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach (array_slice($demandes, 0, 5) as $demande): ?>
            <tr>
                <td><a href="/demandes/<?= $demande['id'] ?>" style="color: #003d82; text-decoration: none; font-weight: 600;"><?= htmlspecialchars($demande['numero_demande']) ?></a></td>
                <td><?= htmlspecialchars($demande['service_demandeur']) ?></td>
                <td><span class="badge badge-<?= $demande['priorite'] === 'urgente' ? 'danger' : 'warning' ?>"><?= ucfirst($demande['priorite']) ?></span></td>
                <td><?= date('d/m/Y', strtotime($demande['created_at'])) ?></td>
                <td><span class="badge badge-info"><?= ucfirst(str_replace('_', ' ', $demande['statut'])) ?></span></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php endif; ?>

<?php if (!empty($mouvements)): ?>
<div class="card" style="margin-top: 2rem;">
    <h3>📊 Mouvements du Jour</h3>
    <table>
        <thead>
            <tr>
                <th>Heure</th>
                <th>Type</th>
                <th>Article</th>
                <th>Quantité</th>
                <th>Utilisateur</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach (array_slice($mouvements, 0, 10) as $mouvement): ?>
            <tr>
                <td><?= date('H:i', strtotime($mouvement['date_mouvement'])) ?></td>
                <td><span class="badge badge-<?= $mouvement['type'] === 'entree' ? 'success' : 'danger' ?>"><?= ucfirst($mouvement['type']) ?></span></td>
                <td><?= htmlspecialchars($mouvement['designation']) ?></td>
                <td><?= $mouvement['quantite'] ?></td>
                <td><?= htmlspecialchars($mouvement['name']) ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php endif; ?>
