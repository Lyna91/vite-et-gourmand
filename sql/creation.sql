DROP TABLE IF EXISTS avis;
DROP TABLE IF EXISTS suivi_commande ;
DROP TABLE IF EXISTS commande;
DROP TABLE IF EXISTS horaire;
DROP TABLE IF EXISTS allergene_plat;
DROP TABLE IF EXISTS menu_plat;
DROP TABLE IF EXISTS plat;
DROP TABLE IF EXISTS allergene;
DROP TABLE IF EXISTS image;
DROP TABLE IF EXISTS menu;
DROP TABLE IF EXISTS regime;
DROP TABLE IF EXISTS theme;
DROP TABLE IF EXISTS utilisateur;
DROP TABLE IF EXISTS role;

CREATE TABLE role (
    id_role INT AUTO_INCREMENT PRIMARY KEY,
    libelle VARCHAR(20) NOT NULL UNIQUE
    );


CREATE TABLE utilisateur (
    id_utilisateur INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(50) NOT NULL,
    prenom VARCHAR(50) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    telephone VARCHAR(15) NOT NULL,
    adresse VARCHAR(255) NOT NULL,
    cp VARCHAR(5) NOT NULL,
    ville VARCHAR(100) NOT NULL,
    actif BOOLEAN NOT NULL DEFAULT TRUE,
    mot_de_passe VARCHAR(255) NOT NULL,
    token_reinitialisation VARCHAR(255),
    token_expiration DATETIME,
    id_role INT NOT NULL,
    FOREIGN KEY (id_role) REFERENCES role(id_role)
    );

CREATE TABLE theme (
    id_theme INT AUTO_INCREMENT PRIMARY KEY,
    libelle VARCHAR(50) NOT NULL UNIQUE
);

CREATE TABLE regime (
    id_regime INT AUTO_INCREMENT PRIMARY KEY,
    libelle VARCHAR(50) NOT NULL UNIQUE
);

CREATE TABLE menu (
    id_menu INT AUTO_INCREMENT PRIMARY KEY,
    titre VARCHAR(100) NOT NULL,
    description TEXT NOT NULL,
    conditions TEXT NOT NULL,
    stock INT NOT NULL,
    prix_par_personne DECIMAL (6,2) NOT NULL,
    nbre_personne_mini INT NOT NULL,
    id_regime INT NOT NULL,
    id_theme INT NOT NULL,
    FOREIGN KEY (id_regime) REFERENCES regime(id_regime),
    FOREIGN KEY (id_theme) REFERENCES theme(id_theme)
);


CREATE TABLE image (
    id_image INT AUTO_INCREMENT PRIMARY KEY,
    chemin VARCHAR(255) NOT NULL,
    texte_image VARCHAR(255) NOT NULL,
    id_menu INT NOT NULL,
    FOREIGN KEY (id_menu) REFERENCES menu(id_menu)
);

CREATE TABLE allergene (
    id_allergene INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(50) NOT NULL UNIQUE
);

CREATE TABLE plat (
    id_plat INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    type VARCHAR(10) NOT NULL
);

CREATE TABLE menu_plat (
    id_plat INT NOT NULL,
    id_menu INT NOT NULL,
    PRIMARY KEY (id_menu, id_plat),
    FOREIGN KEY (id_plat) REFERENCES plat(id_plat),
    FOREIGN KEY (id_menu) REFERENCES menu(id_menu)
);

CREATE TABLE allergene_plat (
    id_allergene INT NOT NULL,
    id_plat INT NOT NULL,
    PRIMARY KEY (id_allergene, id_plat),
    FOREIGN KEY (id_allergene) REFERENCES allergene(id_allergene),
    FOREIGN KEY (id_plat) REFERENCES plat(id_plat)
);

CREATE TABLE horaire (
    id_horaire INT AUTO_INCREMENT PRIMARY KEY,
    jour VARCHAR(10) NOT NULL UNIQUE,
    heure_ouverture TIME,
    heure_fermeture TIME
);

CREATE TABLE commande (
    id_commande INT AUTO_INCREMENT PRIMARY KEY,
    numero_commande VARCHAR(50) NOT NULL UNIQUE,
    nbre_personne INT NOT NULL,
    prix_menu DECIMAL (8,2) NOT NULL,
    date_commande DATE NOT NULL,
    date_livraison DATE NOT NULL,
    heure_livraison TIME NOT NULL,
    adresse_livraison VARCHAR(255) NOT NULL,
    cp_livraison VARCHAR (5) NOT NULL,
    ville_livraison VARCHAR(100) NOT NULL,
    frais_livraison DECIMAL (5,2) NOT NULL,
    montant_reduction DECIMAL(6,2) NOT NULL DEFAULT 0,
    pret_materiel BOOLEAN NOT NULL DEFAULT FALSE,
    restitution_materiel BOOLEAN NOT NULL DEFAULT FALSE,
    mode_contact VARCHAR(50),
    motif_annulation VARCHAR(255),
    id_utilisateur INT NOT NULL,
    id_menu INT NOT NULL,
    FOREIGN KEY (id_utilisateur) REFERENCES utilisateur(id_utilisateur),
    FOREIGN KEY (id_menu) REFERENCES menu(id_menu)
);


CREATE TABLE suivi_commande (
    id_suivi_commande INT AUTO_INCREMENT PRIMARY KEY,
    statut VARCHAR(50) NOT NULL,
    date_statut DATE NOT NULL,
    heure_statut TIME NOT NULL,
    id_commande INT NOT NULL,
    FOREIGN KEY (id_commande) REFERENCES commande(id_commande)
);


CREATE TABLE avis (
    id_avis INT AUTO_INCREMENT PRIMARY KEY,
    description_avis TEXT NOT NULL,
    note INT NOT NULL,
    statut VARCHAR(50) NOT NULL,
    date_avis DATE NOT NULL,
    id_commande INT NOT NULL UNIQUE,
    FOREIGN KEY (id_commande) REFERENCES commande(id_commande),
    CHECK (note BETWEEN 1 AND 5)
);