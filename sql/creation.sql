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
