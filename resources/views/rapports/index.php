<?php include dirname(__DIR__) . '/layout.php'; ?>

<div class="grid">
    <div class="card">
        <h3>📦 Articles</h3>
        <p>Exporter la liste complète des articles avec leurs caractéristiques.</p>
        <a href="/rapports/articles" class="btn btn-primary">📥 Télécharger CSV</a>
    </div>

    <div class="card">
        <h3>📊 Mouvements</h3>
        <p>Exporter l'historique complet des mouvements de stock.</p>
        <a href="/rapports/mouvements" class="btn btn-primary">📥 Télécharger CSV</a>
    </div>

    <div class="card">
        <h3>📋 Rapport Stock</h3>
        <p>Générer un rapport PDF avec tous les stocks et seuils.</p>
        <a href="/rapports/stock-pdf" class="btn btn-primary">📥 Télécharger PDF</a>
    </div>

    <div class="card">
        <h3>📈 Statistiques</h3>
        <p>Accédez au tableau de bord pour les statistiques générales.</p>
        <a href="/dashboard" class="btn btn-primary">Voir Tableau de Bord</a>
    </div>
</div>

<div class="card" style="margin-top: 2rem;">
    <h3>ℹ️ Formats d'Export</h3>
    <ul style="margin-left: 1.5rem;">
        <li><strong>CSV</strong> - Importable dans Excel/Calc</li>
        <li><strong>PDF</strong> - Pour impression et archivage</li>
        <li>Tous les exports sont <strong>datés et horodatés</strong></li>
        <li>Traçabilité complète du téléchargement en logs</li>
    </ul>
</div>
