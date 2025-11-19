<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'GES STOCK - Administration Publique' ?></title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f5f5f5;
            color: #333;
        }
        .navbar {
            background: linear-gradient(135deg, #003d82 0%, #005fa3 100%);
            color: white;
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .navbar h1 {
            font-size: 1.5rem;
            font-weight: 600;
        }
        .navbar .user-menu {
            display: flex;
            gap: 2rem;
            align-items: center;
        }
        .navbar a {
            color: white;
            text-decoration: none;
            font-size: 0.95rem;
        }
        .navbar a:hover {
            opacity: 0.8;
        }
        .container {
            display: flex;
            min-height: calc(100vh - 70px);
        }
        .sidebar {
            width: 250px;
            background: white;
            border-right: 1px solid #e0e0e0;
            padding: 2rem 0;
            box-shadow: 2px 0 4px rgba(0,0,0,0.05);
        }
        .sidebar nav a {
            display: block;
            padding: 0.75rem 1.5rem;
            color: #333;
            text-decoration: none;
            border-left: 4px solid transparent;
            transition: all 0.3s;
        }
        .sidebar nav a:hover, .sidebar nav a.active {
            background: #f0f0f0;
            border-left-color: #003d82;
            color: #003d82;
            font-weight: 600;
        }
        .main-content {
            flex: 1;
            padding: 2rem;
            overflow-y: auto;
        }
        .header {
            margin-bottom: 2rem;
        }
        .header h2 {
            font-size: 2rem;
            color: #003d82;
            margin-bottom: 0.5rem;
        }
        .header p {
            color: #666;
            font-size: 0.95rem;
        }
        .alert {
            padding: 1rem;
            margin-bottom: 1rem;
            border-radius: 4px;
            border-left: 4px solid;
        }
        .alert-success {
            background: #d4edda;
            border-color: #28a745;
            color: #155724;
        }
        .alert-error {
            background: #f8d7da;
            border-color: #dc3545;
            color: #721c24;
        }
        .alert-warning {
            background: #fff3cd;
            border-color: #ffc107;
            color: #856404;
        }
        .btn {
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 0.95rem;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s;
        }
        .btn-primary {
            background: #003d82;
            color: white;
        }
        .btn-primary:hover {
            background: #002d5c;
        }
        .btn-secondary {
            background: #6c757d;
            color: white;
        }
        .btn-success {
            background: #28a745;
            color: white;
        }
        .btn-danger {
            background: #dc3545;
            color: white;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            margin-top: 1rem;
        }
        table thead {
            background: #f0f0f0;
            border-bottom: 2px solid #ddd;
        }
        table th {
            padding: 1rem;
            text-align: left;
            font-weight: 600;
            color: #003d82;
        }
        table td {
            padding: 1rem;
            border-bottom: 1px solid #eee;
        }
        table tr:hover {
            background: #f9f9f9;
        }
        .form-group {
            margin-bottom: 1.5rem;
        }
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: #333;
        }
        .form-group input, .form-group textarea, .form-group select {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 0.95rem;
            font-family: inherit;
        }
        .form-group input:focus, .form-group textarea:focus, .form-group select:focus {
            outline: none;
            border-color: #003d82;
            box-shadow: 0 0 0 3px rgba(0, 61, 130, 0.1);
        }
        .grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem; }
        .card {
            background: white;
            border-radius: 4px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            padding: 1.5rem;
        }
        .card h3 {
            color: #003d82;
            margin-bottom: 1rem;
            font-size: 1.2rem;
        }
        .stat-number {
            font-size: 2.5rem;
            font-weight: 700;
            color: #003d82;
            margin-bottom: 0.5rem;
        }
        .stat-label {
            color: #666;
            font-size: 0.9rem;
        }
        .badge {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: 12px;
            font-size: 0.85rem;
            font-weight: 600;
        }
        .badge-success { background: #d4edda; color: #155724; }
        .badge-warning { background: #fff3cd; color: #856404; }
        .badge-danger { background: #f8d7da; color: #721c24; }
        .badge-info { background: #d1ecf1; color: #0c5460; }
        footer {
            background: white;
            border-top: 1px solid #e0e0e0;
            padding: 1.5rem 2rem;
            text-align: center;
            color: #666;
            font-size: 0.9rem;
            margin-top: auto;
        }
    </style>
</head>
<body>
    <div class="navbar">
        <h1>📊 GES STOCK</h1>
        <div class="user-menu">
            <?php if (\Core\Auth::check()): ?>
                <span>Bienvenue, <?= htmlspecialchars(\Core\Auth::user()['email']) ?></span>
                <a href="/change-password">Mot de passe</a>
                <a href="/logout">Déconnexion</a>
            <?php endif; ?>
        </div>
    </div>

    <div class="container">
        <?php if (\Core\Auth::check()): ?>
        <div class="sidebar">
            <nav>
                <a href="/dashboard" class="<?= $_SERVER['REQUEST_URI'] === '/dashboard' ? 'active' : '' ?>">📈 Tableau de Bord</a>
                <a href="/articles" class="<?= strpos($_SERVER['REQUEST_URI'], '/articles') === 0 ? 'active' : '' ?>">📦 Articles</a>
                <a href="/stock" class="<?= strpos($_SERVER['REQUEST_URI'], '/stock') === 0 ? 'active' : '' ?>">🏭 Stock</a>
                <a href="/demandes" class="<?= strpos($_SERVER['REQUEST_URI'], '/demandes') === 0 ? 'active' : '' ?>">📋 Demandes</a>
                <a href="/approvisionnements" class="<?= strpos($_SERVER['REQUEST_URI'], '/approvisionnements') === 0 ? 'active' : '' ?>">🚚 Approvisionnements</a>
                <a href="/inventaires" class="<?= strpos($_SERVER['REQUEST_URI'], '/inventaires') === 0 ? 'active' : '' ?>">📝 Inventaires</a>
                <?php if (\Core\Auth::role() === 'admin'): ?>
                <a href="/admin/users" class="<?= strpos($_SERVER['REQUEST_URI'], '/admin') === 0 ? 'active' : '' ?>">👥 Administration</a>
                <?php endif; ?>
            </nav>
        </div>
        <?php endif; ?>

        <div class="main-content">
            <?php if (isset($_SESSION['message'])): ?>
                <div class="alert alert-success">
                    <?= htmlspecialchars($_SESSION['message']) ?>
                    <?php unset($_SESSION['message']); ?>
                </div>
            <?php endif; ?>
            
            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-error">
                    <?= htmlspecialchars($_SESSION['error']) ?>
                    <?php unset($_SESSION['error']); ?>
                </div>
            <?php endif; ?>

            <?php if (isset($title) && \Core\Auth::check()): ?>
            <div class="header">
                <h2><?= htmlspecialchars($title) ?></h2>
            </div>
            <?php endif; ?>

            <!-- Le contenu de la page s'insère ici -->
        </div>
    </div>

    <footer>
        <p>&copy; 2025 GES STOCK - Application de Gestion Publique | Conformité RGPD | Tous les mouvements sont tracés</p>
    </footer>
</body>
</html>
