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

# Insertion des données

INSERT INTO role (libelle) VALUES
    ('Administrateur'),
    ('Employé'),
    ('Utilisateur');

INSERT INTO theme (libelle) VALUES
    ('Mariage'),
    ('Noël'),
    ('Cremallière'),
    ('Séminaire'),
    ('Anniversaire'),
    ('Retraite'),
    ('Evènement de vie'),
    ('Religieux');

INSERT INTO regime (libelle) VALUES
    ('Classique'),
    ('Végétarien'),
    ('Végétalien'),
    ('Pescétarien');

INSERT INTO horaire (jour, heure_ouverture, heure_fermeture) VALUES
    ('Lundi', '10:00', '19:00'),
    ('Mardi', '10:00', '19:00'),
    ('Mercredi', '10:00', '19:00'),
    ('Jeudi', '10:00', '20:00'),
    ('Vendredi', '10:00', '20:00'),
    ('Samedi', '10:00', '20:00'),
    ('Dimanche', '14:00', '17:00');

INSERT INTO utilisateur (id_role, nom, prenom, mot_de_passe, email, telephone, adresse, cp, ville) VALUES
    (1, 'Gourmand', 'José', '$2y$10$06rWw2DjRTEKrDhPvLZgqeLIPWEtZ88sUN3UHBQjBhlUkbkyGHtbS', 'joseg@vite-gourmand.fr', '0102202020', '1 rue de la Place', '33000','Bordeaux'),
    (2, 'Durand', 'Philippe', '$2y$10$50BoCXZd8DnVs1jP9FHJy.p/T7.AJ2h89qlR.FxbbD./nXJen189u', 'philipe.durand@vite-gourmand.fr', '0102030201', '3 rue de la mairie', '33300', 'Bordeaux'),
    (3, 'Dupond', 'Marie', '$2y$10$6Q7/2rFODwM4scCrEfp8Qer55qzj4NgdRn85BycOXWi6Rk614PwUu', 'marie.dupond@utilisateur.fr', '0202030401', '20 avenue du monde', '13000', 'Marseille');

INSERT INTO menu (titre, description, conditions, stock, prix_par_personne, nbre_personne_mini, id_theme, id_regime) VALUES
    ('Pour la vie', 'Un menu dédié aux amoureux du jour, composé avec amour', 'Commander six mois avant la date, à conserver au frais, prêt de matériel possible (voir CGV)', 450, 60.00, 50, 1, 1),
    ('Réveillon de la mer', 'Un Noël sous le signe de la mer et de la convivialité', 'Commander deux mois avant la date', 150, 35.00, 15, 2, 4),
    ('Réveillon magique', 'Un menu de Noël façon traditionnelle et familiale', 'Commander trois mois avant la date', 400, 25.00, 15, 2, 1),
    ('Veg. Anniv', 'Un menu anniversaire pour les vegans', 'Commander 2 semaines avant la date', 200, 15.00, 10, 7, 3),
    ('Bonne Continuation', 'Un menu pour souhaiter bonne route pour la deuxième vie', 'Commander trois semaines avant la date', 150, 30.00, 20, 8, 2);

INSERT INTO allergene (nom) VALUES
    ('Oeufs'),
    ('Gluten'),
    ('Fruits à coque'),
    ('Lait'),
    ('Poissons'),
    ('Arachides'),
    ('Céleri'),
    ('Soja'),
    ('Moutarde'),
    ('Crustacés');

INSERT INTO plat (nom, type) VALUES
    ('Foie gras de canard, chutney de figues', 'Entrée'),
    ('Verrine avocat et crevette pamplemousse', 'Entrée'),
    ('Oeuf mimosa et mayonnaise maison', 'Entrée'),
    ('Assortiment charcuterie & fromages', 'Entrée'),
    ('Velouté de potimarron au lait de coco', 'Entrée'),
    ('Dos de cabillaud et riz sauvage', 'Plat'),
    ('Suprême de poulet et pomme de terre grenailles', 'Plat'),
    ('Curry de légumes et riz basmati', 'Plat'),
    ('Blanquette de veau et tagliatelles', 'Plat'),
    ('Filet de bar et risotto champignons', 'Plat'),
    ('Pièce montée de choux vanille chocolat', 'Dessert'),
    ('Fraisier pistache', 'Dessert'),
    ('Salade de fruits frais', 'Dessert'),
    ('Café gourmand ou thé gourmand', 'Dessert'),
    ('Crème brûlée vanille bourbon', 'Dessert');


INSERT INTO menu_plat (id_menu, id_plat) VALUES
    (1, 2),
    (1, 10),
    (1, 11),
    (2, 2),
    (2, 6),
    (2, 15),
    (3, 1),
    (3, 7),
    (3, 12),
    (4, 5),
    (4, 8),
    (4, 13),
    (5, 3),
    (5, 8),
    (5, 14);

INSERT INTO allergene_plat (id_plat, id_allergene) VALUES
    (2, 10),
    (3, 1),
    (3, 9),
    (4, 4),
    (6, 5),
    (7, 2),
    (9, 1),
    (9, 2),
    (9, 4),
    (10, 5),
    (11, 1),
    (11, 2),
    (11, 3),
    (11, 4),
    (12, 1),
    (12, 2),
    (12, 3),
    (12, 4),
    (14, 1),
    (14, 2),
    (14, 3),
    (14, 4),
    (15, 1),
    (15, 4);

