# 📋 Guide d'Installation - GES STOCK

## 🔧 Prérequis

- **PHP** 8.0 ou supérieur
- **MySQL** 5.7 ou supérieur
- **Apache/Nginx** avec support des URLs rewriting
- **Composer** (optionnel, pour les dépendances)

## 📦 Installation Pas à Pas

### 1. Téléchargement
```bash
cd c:\Users\home_pc\Desktop
# Le dossier 'ges stock' est déjà créé
```

### 2. Créer la Base de Données

```bash
mysql -u root -p < database.sql
```

Ou via PhpMyAdmin:
1. Aller sur `http://localhost/phpmyadmin`
2. Créer une base nommée `ges_stock`
3. Importer le fichier `database.sql`

### 3. Configuration

Éditer le fichier `.env`:
```
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ges_stock
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Déployer l'Application

#### Option A: Avec Apache
1. Copier le dossier `ges stock` dans `htdocs` (XAMPP) ou `www` (WAMP)
2. Configurer VirtualHost dans Apache (optionnel):
```apache
<VirtualHost *:80>
    ServerName gesstock.local
    DocumentRoot "c:/xampp/htdocs/ges stock/public"
    <Directory "c:/xampp/htdocs/ges stock/public">
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

#### Option B: Avec serveur intégré PHP
```bash
cd "c:\Users\home_pc\Desktop\ges stock"
php -S localhost:8000 -t public/
```

### 5. Accéder à l'Application

- **URL**: `http://localhost:8000` (serveur PHP) ou `http://localhost/ges%20stock` (Apache)
- **Email par défaut**: `admin@admin.local`
- **Mot de passe**: `Admin@123456`

## 🔐 Sécurité Post-Installation

1. **Changer le mot de passe admin**
   - Se connecter avec les identifiants par défaut
   - Aller à "Mot de passe" dans la barre du haut

2. **Créer les autres comptes utilisateurs**
   - Via le panneau Admin > Utilisateurs
   - Assignez les rôles appropriés

3. **Configurer les permissions**
   - Voir le fichier `config/App.php` pour les rôles et permissions

## 📁 Structure du Projet

```
ges stock/
├── app/
│   ├── Controllers/      # Contrôleurs
│   ├── Models/          # Modèles de données
│   └── Services/        # Services métier
├── core/                 # Classes de base
├── config/              # Configuration
├── database.sql         # Schéma SQL
├── resources/
│   └── views/          # Vues HTML
├── public/
│   └── index.php       # Point d'entrée
└── .env                # Variables d'environnement
```

## 🚀 Modules Disponibles

1. **Tableau de Bord** - Statistiques et alertes
2. **Articles** - Gestion du catalogue
3. **Stock** - Mouvements et inventaires
4. **Demandes** - Demandes de matériel
5. **Approvisionnements** - Bons d'entrée
6. **Inventaires** - Comptes de stock
7. **Admin** - Gestion utilisateurs et logs

## 📊 Rôles et Permissions

### Admin
- Accès complet à toutes les fonctionnalités
- Gestion des utilisateurs
- Accès aux logs

### Magasinier
- Gestion complète du stock
- Préparation des demandes
- Réception des livraisons

### Chef de Service
- Création de demandes
- Validation des demandes
- Consultation du stock

### Consultateur
- Lecture seule
- Consultation articles
- Consultation demandes

## ⚙️ Configuration Avancée

### Variables d'Environnement (.env)

```
APP_NAME=GES STOCK Administration
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ges_stock
DB_USERNAME=root
DB_PASSWORD=
```

### Paramètres Applicatifs

Éditables dans la base de données table `parametres`:
- `stock.alerte_min` - Seuil d'alerte minimum
- `secu.tentatives_max` - Tentatives de connexion avant verrouillage
- `rgpd.retention_logs` - Rétention des logs en jours

## 🐛 Dépannage

### Erreur de connexion à la base de données
- Vérifier que MySQL est lancé
- Vérifier les identifiants dans `.env`
- Vérifier que la base `ges_stock` existe

### Erreur 404 sur les pages
- Vérifier que le serveur web est lancé
- Vérifier que le `mod_rewrite` est activé (Apache)
- Vérifier les permissions sur les dossiers

### Problèmes de performance
- Vérifier les index de la base de données
- Nettoyer les logs anciens : `AuditService::cleanup()`
- Augmenter les limites PHP: `memory_limit`, `max_execution_time`

## 📝 Logs et Audit

Tous les mouvements sont tracés dans :
- `audit_logs` - Modifications CRUD
- `securite_logs` - Connexions et accès

Rétention: **7 ans** (configurable)

## 🔄 Mise à Jour

1. Faire une sauvegarde de la base de données
2. Télécharger la nouvelle version
3. Exécuter les migrations : `php artisan migrate`
4. Nettoyer les caches : `php artisan cache:clear`

## 📞 Support

Pour toute question ou problème:
1. Consulter le tableau de bord pour les alertes
2. Vérifier les logs de sécurité
3. Contacter l'administrateur système

## ✅ Checklist Post-Installation

- [ ] Base de données créée
- [ ] Configuration `.env` ajustée
- [ ] Serveur web lancé
- [ ] Connexion au compte admin réussie
- [ ] Mot de passe admin changé
- [ ] Utilisateurs créés
- [ ] Test de mouvement de stock
- [ ] Test de création de demande
- [ ] Backup initial effectué

---

**Version**: 1.0.0
**Dernière mise à jour**: 2025
**Licence**: MIT
