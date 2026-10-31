<?php

session_start();
date_default_timezone_set('Europe/Paris');

require __DIR__ . '/../vendor/autoload.php';
//base de données
require __DIR__ . '/../src/database.php'; // require insère le contenu d'un autre fichier, __DIR__ indique le chemin du dossier du fichier actuel

//modèles
require __DIR__ . '/../src/Menu.php';

require __DIR__ . '/../src/MenuManager.php';

require __DIR__ . '/../src/Plat.php';

require __DIR__ . '/../src/PlatManager.php';

require __DIR__ . '/../src/Horaire.php';

require __DIR__ . '/../src/HoraireManager.php';

require __DIR__ . '/../src/Image.php';

require __DIR__ . '/../src/ImageManager.php';

require __DIR__ . '/../src/UtilisateurManager.php';

require __DIR__ . '/../src/CommandeManager.php';



//controlleurs
require __DIR__ . '/../src/Controller.php';

require __DIR__ . '/../src/MenuController.php';

require __DIR__ . '/../src/PageController.php';

require __DIR__ . '/../src/UtilisateurController.php';

require __DIR__ . '/../src/CommandeController.php';

require __DIR__ . '/../src/Mailer.php';



$chemin = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
if ($chemin === '/') {
    $controller = new PageController();
    $controller->accueil();
} elseif ($chemin === '/menus') {
    $controller = new MenuController();
    $controller->liste();
} elseif ($chemin === '/menu') {
    $controller = new MenuController();
    $controller->detail();
} elseif ($chemin === '/mentions-legales') {
    $controller = new PageController();
    $controller->mentionsLegales();
} elseif ($chemin === '/cgv') {
    $controller = new PageController();
    $controller->cgv();
} elseif ($chemin === '/api/menus') {
    $controller = new MenuController();
    $controller->apiListe();
} elseif ($chemin === '/inscription') {
    $controller = new UtilisateurController();
    $controller->inscription();
} elseif ($chemin === '/connexion') {
    $controller = new UtilisateurController();
    $controller->connexion();
} elseif ($chemin === '/deconnexion') {
    $controller = new UtilisateurController();
    $controller->deconnexion();
} elseif ($chemin === '/contact') {
    $controller = new PageController();
    $controller->contact();
} elseif ($chemin === '/mot-de-passe-oublie') {
    $controller = new UtilisateurController();
    $controller->motDePasseOublie();
} elseif ($chemin === '/reinitialisation') {
    $controller = new UtilisateurController();
    $controller->reinitialisation();
} elseif ($chemin === '/commande') {
    $controller = new CommandeController();
    $controller->commande();
} else {
    $controller = new PageController();
    $controller->introuvable();
}
