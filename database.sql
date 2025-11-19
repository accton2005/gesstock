-- GES STOCK - Database Schema
-- Administration Publique - Gestion Magasin & Inventaire

CREATE DATABASE IF NOT EXISTS ges_stock;
USE ges_stock;

-- ============================================
-- 1. AUTHENTIFICATION & UTILISATEURS
-- ============================================

CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'magasinier', 'chef_service', 'consultateur') DEFAULT 'consultateur',
    department VARCHAR(255),
    is_active TINYINT(1) DEFAULT 1,
    last_login TIMESTAMP NULL,
    login_attempts INT DEFAULT 0,
    locked_until TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP NULL,
    INDEX idx_email (email),
    INDEX idx_role (role),
    INDEX idx_active (is_active)
);

-- ============================================
-- 2. GESTION DES ARTICLES
-- ============================================

CREATE TABLE articles (
    id INT PRIMARY KEY AUTO_INCREMENT,
    code_interne VARCHAR(50) UNIQUE NOT NULL,
    designation VARCHAR(255) NOT NULL,
    description LONGTEXT,
    categorie VARCHAR(100) NOT NULL,
    categorie_budgetaire VARCHAR(100),
    fournisseur_principal INT,
    unite_base ENUM('piece', 'kg', 'litre', 'metre', 'carton', 'colis') DEFAULT 'piece',
    stock_min INT DEFAULT 0,
    stock_max INT DEFAULT 0,
    stock_critique INT DEFAULT 0,
    prixunitaire DECIMAL(10,2) DEFAULT 0,
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    date_modification TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    actif TINYINT(1) DEFAULT 1,
    date_archivage TIMESTAMP NULL,
    motif_archivage VARCHAR(255),
    created_by INT,
    INDEX idx_code (code_interne),
    INDEX idx_designation (designation),
    INDEX idx_categorie (categorie),
    INDEX idx_actif (actif),
    FOREIGN KEY (created_by) REFERENCES users(id)
);

-- ============================================
-- 3. GESTION DES LOTS & PEREMPTION
-- ============================================

CREATE TABLE lots (
    id INT PRIMARY KEY AUTO_INCREMENT,
    article_id INT NOT NULL,
    numero_lot VARCHAR(100) UNIQUE NOT NULL,
    date_fabrication DATE,
    date_expiration DATE,
    quantite_initiale INT NOT NULL,
    quantite_actuelle INT NOT NULL,
    statut ENUM('actif', 'expire', 'consomme') DEFAULT 'actif',
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (article_id) REFERENCES articles(id),
    INDEX idx_article (article_id),
    INDEX idx_expiration (date_expiration),
    INDEX idx_statut (statut)
);

-- ============================================
-- 4. GESTION DU STOCK
-- ============================================

CREATE TABLE mouvements_stock (
    id INT PRIMARY KEY AUTO_INCREMENT,
    article_id INT NOT NULL,
    lot_id INT,
    type ENUM('entree', 'sortie', 'inventaire', 'transfert', 'ajustement') NOT NULL,
    quantite INT NOT NULL,
    justification LONGTEXT,
    reference_document VARCHAR(100),
    date_mouvement TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    user_id INT NOT NULL,
    approuve_par INT,
    date_approbation TIMESTAMP NULL,
    statut ENUM('brouillon', 'en_attente', 'approuve', 'rejete') DEFAULT 'en_attente',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (article_id) REFERENCES articles(id),
    FOREIGN KEY (lot_id) REFERENCES lots(id),
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (approuve_par) REFERENCES users(id),
    INDEX idx_article (article_id),
    INDEX idx_type (type),
    INDEX idx_date (date_mouvement),
    INDEX idx_statut (statut)
);

-- ============================================
-- 5. FOURNISSEURS
-- ============================================

CREATE TABLE fournisseurs (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(255) NOT NULL,
    contact VARCHAR(255),
    email VARCHAR(255),
    telephone VARCHAR(20),
    adresse LONGTEXT,
    ville VARCHAR(100),
    code_postal VARCHAR(20),
    conditions_paiement VARCHAR(255),
    delai_livraison_moyen INT,
    actif TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_nom (nom)
);

-- ============================================
-- 6. APPROVISIONNEMENTS & BON ENTREE
-- ============================================

CREATE TABLE bons_entree (
    id INT PRIMARY KEY AUTO_INCREMENT,
    numero_bon VARCHAR(50) UNIQUE NOT NULL,
    fournisseur_id INT NOT NULL,
    date_commande DATE,
    date_livraison_prevue DATE,
    date_livraison_relle DATE,
    statut ENUM('commandee', 'en_transit', 'livree', 'partielle', 'annulee') DEFAULT 'commandee',
    montant_total DECIMAL(12,2),
    notes LONGTEXT,
    created_by INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (fournisseur_id) REFERENCES fournisseurs(id),
    FOREIGN KEY (created_by) REFERENCES users(id),
    INDEX idx_numero (numero_bon),
    INDEX idx_statut (statut),
    INDEX idx_date (date_livraison_prevue)
);

CREATE TABLE details_bon_entree (
    id INT PRIMARY KEY AUTO_INCREMENT,
    bon_id INT NOT NULL,
    article_id INT NOT NULL,
    quantite_commandee INT NOT NULL,
    quantite_livree INT,
    prix_unitaire DECIMAL(10,2),
    montant DECIMAL(12,2),
    numero_lot VARCHAR(100),
    date_expiration DATE,
    FOREIGN KEY (bon_id) REFERENCES bons_entree(id),
    FOREIGN KEY (article_id) REFERENCES articles(id),
    INDEX idx_bon (bon_id)
);

-- ============================================
-- 7. DEMANDES INTERNES
-- ============================================

CREATE TABLE demandes (
    id INT PRIMARY KEY AUTO_INCREMENT,
    numero_demande VARCHAR(50) UNIQUE NOT NULL,
    service_demandeur INT NOT NULL,
    utilisateur_demandeur INT NOT NULL,
    date_demande TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    justification LONGTEXT NOT NULL,
    priorite ENUM('normale', 'urgente', 'faible') DEFAULT 'normale',
    statut ENUM('brouillon', 'soumise', 'validee', 'preparee', 'distribuee', 'rejetee', 'annulee') DEFAULT 'brouillon',
    validee_par INT,
    date_validation TIMESTAMP NULL,
    raison_rejet VARCHAR(255),
    distributeur INT,
    date_distribution TIMESTAMP NULL,
    notes LONGTEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (service_demandeur) REFERENCES users(id),
    FOREIGN KEY (utilisateur_demandeur) REFERENCES users(id),
    FOREIGN KEY (validee_par) REFERENCES users(id),
    FOREIGN KEY (distributeur) REFERENCES users(id),
    INDEX idx_numero (numero_demande),
    INDEX idx_statut (statut),
    INDEX idx_date (date_demande)
);

CREATE TABLE details_demande (
    id INT PRIMARY KEY AUTO_INCREMENT,
    demande_id INT NOT NULL,
    article_id INT NOT NULL,
    quantite_demandee INT NOT NULL,
    quantite_accordee INT,
    quantite_distribuee INT,
    notes VARCHAR(255),
    FOREIGN KEY (demande_id) REFERENCES demandes(id),
    FOREIGN KEY (article_id) REFERENCES articles(id),
    INDEX idx_demande (demande_id)
);

-- ============================================
-- 8. INVENTAIRES
-- ============================================

CREATE TABLE inventaires (
    id INT PRIMARY KEY AUTO_INCREMENT,
    numero_inventaire VARCHAR(50) UNIQUE NOT NULL,
    date_debut TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    date_fin TIMESTAMP NULL,
    type ENUM('periodique', 'exceptionnel', 'partiel') DEFAULT 'periodique',
    statut ENUM('en_cours', 'termine', 'annule') DEFAULT 'en_cours',
    responsable INT,
    notes LONGTEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (responsable) REFERENCES users(id),
    INDEX idx_numero (numero_inventaire),
    INDEX idx_statut (statut)
);

CREATE TABLE details_inventaire (
    id INT PRIMARY KEY AUTO_INCREMENT,
    inventaire_id INT NOT NULL,
    article_id INT NOT NULL,
    quantite_attendue INT,
    quantite_comptee INT,
    difference INT,
    observation VARCHAR(255),
    verifiee TINYINT(1) DEFAULT 0,
    FOREIGN KEY (inventaire_id) REFERENCES inventaires(id),
    FOREIGN KEY (article_id) REFERENCES articles(id),
    INDEX idx_inventaire (inventaire_id)
);

-- ============================================
-- 9. JOURNALISATION & AUDIT
-- ============================================

CREATE TABLE audit_logs (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    action VARCHAR(255) NOT NULL,
    table_name VARCHAR(100),
    record_id INT,
    old_values LONGTEXT,
    new_values LONGTEXT,
    ip_address VARCHAR(45),
    user_agent VARCHAR(255),
    timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    INDEX idx_user (user_id),
    INDEX idx_action (action),
    INDEX idx_timestamp (timestamp),
    INDEX idx_table (table_name)
);

CREATE TABLE securite_logs (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    type ENUM('login', 'logout', 'failed_login', 'permission_denied', 'export', 'delete', 'modification') NOT NULL,
    description VARCHAR(255),
    ip_address VARCHAR(45),
    timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    INDEX idx_user (user_id),
    INDEX idx_type (type),
    INDEX idx_timestamp (timestamp)
);

-- ============================================
-- 10. PARAMETRES ET CONFIGURATION
-- ============================================

CREATE TABLE parametres (
    id INT PRIMARY KEY AUTO_INCREMENT,
    cle VARCHAR(100) UNIQUE NOT NULL,
    valeur LONGTEXT,
    description VARCHAR(255),
    type ENUM('string', 'integer', 'boolean', 'json') DEFAULT 'string',
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- ============================================
-- 11. INDEX SUPPLÉMENTAIRES
-- ============================================

ALTER TABLE mouvements_stock ADD INDEX idx_user (user_id);
ALTER TABLE mouvements_stock ADD INDEX idx_approuve (approuve_par);

-- Insertion de données par défaut
INSERT INTO parametres (cle, valeur, description, type) VALUES
('app.nom', 'GES Stock Administration', 'Nom de l\'application', 'string'),
('app.version', '1.0.0', 'Version de l\'application', 'string'),
('stock.alerte_min', '5', 'Seuil d\'alerte stock minimum', 'integer'),
('secu.tentatives_max', '5', 'Nombre de tentatives de connexion avant verrouillage', 'integer'),
('secu.duree_verrouillage', '900', 'Durée du verrouillage en secondes (15 min)', 'integer'),
('rgpd.retention_logs', '2555', 'Rétention des logs en jours (7 ans)', 'integer');

-- Insertion d'un utilisateur admin par défaut
INSERT INTO users (name, email, password, role, department, is_active) VALUES
('Administrateur', 'admin@admin.local', '$2y$10$9vQgWe7tKGhDZLDfwPH38.wPmKNlK0P/rvwLvE.IjJJxZ5T0iWzUy', 'admin', 'IT', 1);
-- Mot de passe : Admin@123456

