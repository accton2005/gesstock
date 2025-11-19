# 📁 Structure du Projet GES STOCK

## 📂 Arborescence Complète

```
ges stock/
│
├── 📄 index.php                    # Point d'entrée (racine)
├── 📄 .env                         # Configuration environnement
├── 📄 .env.example                 # Exemple de configuration
├── 📄 composer.json                # Dépendances
├── 📄 database.sql                 # Schéma SQL complet
│
├── 📄 README.md                    # Documentation principale
├── 📄 INSTALLATION.md              # Guide d'installation
├── 📄 STRUCTURE.md                 # Ce fichier
│
├── public/
│   └── 📄 index.php               # Routeur principal
│
├── core/                           # Classes de base
│   ├── 📄 Database.php            # Singleton de base de données
│   ├── 📄 Model.php               # Classe mère pour tous les modèles
│   └── 📄 Auth.php                # Gestion authentification & autorisations
│
├── config/
│   └── 📄 App.php                 # Configuration applicative
│
├── app/
│   ├── Models/                    # Modèles de données
│   │   ├── 📄 Article.php        # Gestion articles
│   │   ├── 📄 MouvementStock.php # Mouvements de stock
│   │   ├── 📄 Demande.php        # Demandes internes
│   │   ├── 📄 BonEntree.php      # Bons d'entrée
│   │   ├── 📄 Inventaire.php     # Inventaires
│   │   ├── 📄 User.php           # Utilisateurs
│   │   ├── 📄 Fournisseur.php    # Fournisseurs
│   │   └── 📄 Lot.php            # Lots (optionnel)
│   │
│   ├── Controllers/               # Contrôleurs
│   │   ├── 📄 BaseController.php       # Classe de base
│   │   ├── 📄 AuthController.php       # Authentification
│   │   ├── 📄 ArticleController.php    # Gestion articles
│   │   ├── 📄 StockController.php      # Gestion stock
│   │   ├── 📄 DemandeController.php    # Demandes
│   │   ├── 📄 ApprovisionnementController.php  # Approvisionnements
│   │   ├── 📄 InventaireController.php # Inventaires
│   │   ├── 📄 DashboardController.php  # Tableau de bord
│   │   ├── 📄 RapportController.php    # Rapports
│   │   └── 📄 AdminController.php      # Administration
│   │
│   └── Services/                 # Services métier
│       ├── 📄 AuditService.php   # Journalisation & audit
│       └── 📄 ExportService.php  # Export PDF/Excel
│
├── resources/
│   └── views/                     # Vues HTML/PHP
│       ├── 📄 layout.php         # Layout principal
│       ├── 📄 dashboard.php      # Tableau de bord
│       │
│       ├── auth/
│       │   ├── 📄 login.php      # Page de connexion
│       │   └── 📄 change-password.php
│       │
│       ├── articles/
│       │   ├── 📄 index.php      # Liste des articles
│       │   ├── 📄 create.php     # Créer article
│       │   ├── 📄 edit.php       # Éditer article
│       │   └── 📄 show.php       # Détail article
│       │
│       ├── stock/
│       │   ├── 📄 index.php      # Gestion stock
│       │   └── 📄 mouvements.php # Historique mouvements
│       │
│       ├── demandes/
│       │   ├── 📄 index.php      # Liste demandes
│       │   ├── 📄 create.php     # Nouvelle demande
│       │   └── 📄 show.php       # Détail demande
│       │
│       ├── approvisionnements/
│       │   ├── 📄 index.php      # Liste bons
│       │   ├── 📄 create.php     # Nouveau bon
│       │   └── 📄 show.php       # Détail bon
│       │
│       ├── inventaires/
│       │   ├── 📄 index.php      # Liste inventaires
│       │   ├── 📄 create.php     # Nouvel inventaire
│       │   └── 📄 show.php       # Détail inventaire
│       │
│       └── admin/
│           ├── 📄 users.php      # Gestion utilisateurs
│           └── 📄 logs.php       # Logs & audit
│
└── storage/
    └── logs/                      # Fichiers de log (runtime)

```

## 🗄️ Base de Données

### Tables Principales

```sql
-- Authentification
users                   -- Utilisateurs du système

-- Gestion des Articles
articles                -- Catalogue d'articles
lots                    -- Lots avec dates d'expiration

-- Stock
mouvements_stock        -- Historique des mouvements
details_inventaire      -- Détails des comptages

-- Demandes
demandes                -- Demandes de matériel
details_demande         -- Articles demandés

-- Approvisionnements
fournisseurs            -- Fournisseurs
bons_entree             -- Bons d'entrée
details_bon_entree      -- Articles du bon

-- Inventaires
inventaires             -- Inventaires effectués
details_inventaire      -- Articles inventoriés

-- Audit & Sécurité
audit_logs              -- Logs CRUD
securite_logs           -- Logs de sécurité

-- Configuration
parametres              -- Paramètres applicatifs
```

## 🔐 Sécurité

### Authentification
- Hash bcrypt (cost=10)
- Sessions PHP sécurisées
- Tentatives limitées + verrouillage (5 tentatives = 15 min)

### Autorisations (RBAC)
- **Admin**: Accès complet (`*`)
- **Magasinier**: Gestion stock, préparation demandes
- **Chef Service**: Création demandes, validation
- **Consultateur**: Lecture seule

### Audit
- Logs CRUD complets
- Logs de sécurité (login, modifications)
- Traçabilité par utilisateur et IP
- Rétention: 7 ans

## 🚀 Points d'Entrée Clés

### Routes Principales
- `GET /login` → Page de connexion
- `GET /dashboard` → Tableau de bord
- `GET /articles` → Liste articles
- `POST /articles` → Créer article
- `GET /stock` → Gestion stock
- `GET /demandes` → Demandes
- `POST /demandes` → Créer demande
- `GET /approvisionnements` → Approvisionnements
- `GET /inventaires` → Inventaires
- `GET /admin/users` → Admin utilisateurs
- `GET /admin/logs` → Admin logs

## 📦 Dépendances

```json
{
  "php": "^8.0",
  "barryvdh/laravel-dompdf": "^1.0",
  "maatwebsite/excel": "^3.1"
}
```

## 🔍 Modèles & Relations

### Article
- Récupère son stock actuel via `MouvementStock`
- Peut être archivé
- Lié à des lots

### MouvementStock
- Appartient à un `Article`
- Peut être approuvé ou en attente
- Tracé avec audit complet

### Demande
- Créée par un utilisateur
- Peut être validée, préparée, distribuée
- Contient plusieurs articles (`DetailsComande`)

### BonEntree
- Reçu d'un `Fournisseur`
- Crée automatiquement des `MouvementStock`
- Suivi des livraisons

### Inventaire
- Crée des `DetailsInventaire` pour chaque article
- Calcule les écarts (différence attendu vs compté)
- Peut générer des ajustements de stock

## 🛠️ Configuration

### .env
```
APP_NAME=GES STOCK Administration
APP_ENV=local
APP_DEBUG=true
DB_HOST=127.0.0.1
DB_DATABASE=ges_stock
DB_USERNAME=root
DB_PASSWORD=
```

### config/App.php
- Rôles & permissions
- Types de mouvement
- Unités de base
- Limites de sécurité

## 📊 Exports

### Format CSV
- Mouvements de stock
- Inventaires
- Demandes

### Format PDF
- Fiches articles
- Rapports périodiques
- Justificatifs

## 🔄 Workflows

### Création d'une Demande
1. Service créé demande + articles
2. Chef service valide
3. Magasinier prépare
4. Service reçoit

### Réception Fournisseur
1. Bon créé
2. Livraison reçue
3. Articles entrés en stock (auto-mouvement)
4. Historique tracé

### Inventaire
1. Inventaire créé (tous les articles)
2. Comptage effectué
3. Écarts calculés
4. Ajustements possibles

## 📝 Logs & Audit

Tous les CRUD sont tracés:
- `audit_logs` → Qui a fait quoi (CRUD)
- `securite_logs` → Connexions, modifications sensibles
- IP address & User agent stockés
- Timestamp précis

## ✅ Conventions

- **Nommage**: snake_case en base, camelCase en PHP
- **Dates**: Y-m-d H:i:s en base, formatées pour affichage
- **Montants**: DECIMAL(12,2)
- **Textes longs**: LONGTEXT
- **Soft delete**: deleted_at nullable

---

**Version**: 1.0.0
**Dernière mise à jour**: 2025
