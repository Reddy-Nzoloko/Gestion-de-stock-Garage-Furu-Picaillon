-- ============================================================
-- Base de données : Garage FURU / HAOJUE
-- DBMS           : MySQL 8.0+ / MariaDB
-- ============================================================

CREATE DATABASE IF NOT EXISTS garage_furu
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE garage_furu;

-- ------------------------------------------------------------
-- 1. Table : roles
-- ------------------------------------------------------------
CREATE TABLE roles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(50) NOT NULL UNIQUE, -- Ex: 'Administrateur', 'Magasinier', 'Vendeur'
    description VARCHAR(255)
) ENGINE=InnoDB;

-- ------------------------------------------------------------
-- 2. Table : utilisateurs
-- ------------------------------------------------------------
CREATE TABLE utilisateurs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    role_id INT NOT NULL,
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100),
    email VARCHAR(150) NOT NULL UNIQUE,
    mot_de_passe VARCHAR(255) NOT NULL, -- Hachage sécurisé (ex: bcrypt)
    actif TINYINT(1) DEFAULT 1,
    cree_le DATETIME DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_utilisateurs_role FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE RESTRICT
) ENGINE=InnoDB;

-- ------------------------------------------------------------
-- 3. Table : categories
-- ------------------------------------------------------------
CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL UNIQUE
) ENGINE=InnoDB;

-- ------------------------------------------------------------
-- 4. Table : produits (Fiche produit / Référence)
-- ------------------------------------------------------------
CREATE TABLE produits (
    id INT AUTO_INCREMENT PRIMARY KEY,
    sku VARCHAR(50) NOT NULL UNIQUE, -- SKU unique (converti en MAJUSCULES)
    nom VARCHAR(150) NOT NULL,
    category_id INT,
    emplacement VARCHAR(100),
    quantite INT NOT NULL DEFAULT 0, -- Nombre entier positive
    prix_achat DECIMAL(10, 2) NOT NULL DEFAULT 0.00, -- Montants positifs
    prix_vente DECIMAL(10, 2) NOT NULL DEFAULT 0.00,
    icone VARCHAR(255),
    cree_le DATETIME DEFAULT CURRENT_TIMESTAMP,
    mis_a_jour_le DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_produits_categorie FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL,
    CONSTRAINT chk_quantite_positive CHECK (quantite >= 0),
    CONSTRAINT chk_prix_achat_positif CHECK (prix_achat >= 0),
    CONSTRAINT chk_prix_vente_positif CHECK (prix_vente >= 0)
) ENGINE=InnoDB;

-- ------------------------------------------------------------
-- 5. Table : mouvements (Achats, Ventes, Ajustements)
-- ------------------------------------------------------------
CREATE TABLE mouvements (
    id INT AUTO_INCREMENT PRIMARY KEY,
    type_mouvement ENUM('ACHAT', 'VENTE', 'AJUSTEMENT') NOT NULL, -- Type de mouvement
    produit_id INT, -- NULL si le produit est supprimé mais l'historique conservé
    utilisateur_id INT NOT NULL,
    quantite INT NOT NULL,
    prix_unitaire DECIMAL(10, 2) NOT NULL,
    montant_total DECIMAL(10, 2) NOT NULL,
    tier_nom VARCHAR(150), -- Fournisseur (Achat) ou Client (Vente)
    mode_paiement ENUM('ESPECES', 'MOBILE_MONEY', 'CARTE', 'AUTRE'), -- Optionnel pour Vente[cite: 1]
    date_mouvement DATETIME DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_mouvements_produit FOREIGN KEY (produit_id) REFERENCES produits(id) ON DELETE SET NULL,
    CONSTRAINT fk_mouvements_utilisateur FOREIGN KEY (utilisateur_id) REFERENCES utilisateurs(id) ON DELETE RESTRICT
) ENGINE=InnoDB;

-- ------------------------------------------------------------
-- 6. Table : caisse (Entrées et Dépenses de caisse)
-- ------------------------------------------------------------
CREATE TABLE caisse (
    id INT AUTO_INCREMENT PRIMARY KEY,
    type_operation ENUM('ENTREE', 'SORTIE') NOT NULL, -- Entrée ou Dépense[cite: 1]
    montant DECIMAL(10, 2) NOT NULL,
    motif VARCHAR(255) NOT NULL, -- Explication / Motif[cite: 1]
    utilisateur_id INT NOT NULL,
    mouvement_id INT NULL,
    date_operation DATETIME DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_caisse_utilisateur FOREIGN KEY (utilisateur_id) REFERENCES utilisateurs(id) ON DELETE RESTRICT,
    CONSTRAINT fk_caisse_mouvement FOREIGN KEY (mouvement_id) REFERENCES mouvements(id) ON DELETE SET NULL,
    CONSTRAINT chk_caisse_montant_positif CHECK (montant > 0)
) ENGINE=InnoDB;

-- Pour une base déjà installée, exécuter: database/migrate.php

-- ------------------------------------------------------------
-- 7. Données initiales
-- ------------------------------------------------------------
INSERT INTO roles (nom, description)
VALUES ('Administrateur', 'Accès complet à tous les modules'),
       ('Vendeur', 'Tableau de bord et mouvements uniquement'),
       ('Dépôt', 'Même accès que le vendeur: tableau de bord et mouvements')
ON DUPLICATE KEY UPDATE description = VALUES(description);

INSERT INTO utilisateurs (role_id, nom, prenom, email, mot_de_passe)
SELECT id, 'FURU', 'Administrateur', 'admin@furu.local', '$2y$12$meIPNoFKEGoeWPA/GbzO4eQTYOjCQ1p0ywlErSE9I3JogduTrc5be'
FROM roles WHERE nom = 'Administrateur'
ON DUPLICATE KEY UPDATE actif = 1;

INSERT INTO categories (nom)
VALUES ('Moteur'), ('Freinage'), ('Transmission'), ('Électricité'), ('Carrosserie')
ON DUPLICATE KEY UPDATE nom = VALUES(nom);