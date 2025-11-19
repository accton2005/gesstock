# GES STOCK - Gestion de Magasin et Inventaire
## Application Administrative pour Gestion Publique

### 📋 Vue d'Ensemble
Application complète de gestion de magasin et d'inventaire pour administrations publiques avec :
- Gestion centralisée des articles et ressources
- Suivi complet des mouvements de stock
- Demandes de matériel avec workflow de validation
- Approvisionnements auprès de fournisseurs
- Rapports et exports (PDF/Excel)
- Journalisation complète des opérations
- Conformité RGPD et audit complet

### 🎯 Modules Implémentés

1. **Authentification & Autorisation**
   - Connexion sécurisée (hashage, politique mot de passe, verrouillage)
   - Rôles : Admin, Magasinier, Chef Service, Consultateur
   - Permissions granulaires

2. **Gestion des Articles**
   - Création/édition/archivage
   - Catégorisation budgétaire
   - Seuils min/max/critique
   - Gestion des lots et dates d'expiration

3. **Gestion du Stock**
   - Entrées/sorties/transferts
   - Inventaires périodiques et exceptionnels
   - Alertes stock critique
   - Justification obligatoire

4. **Demandes Internes**
   - Workflow de demande avec validations hiérarchiques
   - Distribution de matériel
   - Historique complet

5. **Approvisionnements**
   - Gestion des bons d'entrée
   - Suivi fournisseurs
   - Suivi de livraison

6. **Rapports & Documents**
   - Export PDF/Excel
   - Rapports paramétrés
   - Fiches articles

7. **Tableau de Bord**
   - Stock critique
   - Demandes en attente
   - Mouvements du jour

8. **Journalisation**
   - Audit complet
   - Logs de sécurité
   - Traçabilité RGPD

### 🔧 Installation

#### Prérequis
- PHP 8.0+
- MySQL 5.7+
- Composer
- Apache/Nginx

#### Étapes

1. **Cloner le dépôt**
```bash
cd path/to/project
```

2. **Installer les dépendances**
```bash
composer install
```

3. **Créer la base de données**
```bash
mysql -u root -p < database.sql
```

4. **Configuration**
- Copier `.env.example` en `.env`
- Générer la clé APP_KEY : `php artisan key:generate`
- Configurer DB_HOST, DB_USERNAME, DB_PASSWORD

5. **Lancer le serveur**
```bash
php artisan serve
```

L'application sera accessible sur : `http://localhost:8000`

### 👤 Utilisateur par Défaut
- **Email** : admin@admin.local
- **Mot de passe** : Admin@123456
- **Rôle** : Administrateur

### 🔒 Sécurité

- **Chiffrement** : Passwords avec bcrypt
- **Sessions** : Sécurisées avec tokens CSRF
- **Audit** : Tous les CRUD tracés
- **RGPD** : Rétention 7 ans, anonymisation possible
- **Logs** : Séparation audit/sécurité/application
- **Rate limiting** : Protection brute force

### 📊 Architecture

```
ges-stock/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   ├── Requests/
│   │   └── Middleware/
│   ├── Models/
│   ├── Services/
│   └── Traits/
├── routes/
├── resources/
│   └── views/
├── public/
├── config/
├── database/
│   ├── migrations/
│   └── seeds/
└── storage/
    └── logs/
```

### 🧪 Tests

```bash
php artisan test
```

### 📝 Licences & Conformité

- Conformité : RGPD, standards publics français
- Architecture : Laravel 9.x MVC
- Base de données : MySQL 5.7+

deployé applaication :

 1. Créer la base de données
mysql -u root -p < database.sql

# 2. Démarrer le serveur PHP
php -S localhost:8000 -t public/

# 3. Accéder à l'application
# http://localhost:8000

# Compte par défaut:
# Email: admin@admin.local
# Mot de passe: Admin@123456

