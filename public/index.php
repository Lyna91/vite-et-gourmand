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

require __DIR__ . '/../src/AvisManager.php';

require __DIR__ . '/../src/StatistiqueManager.php';




//controlleurs
require __DIR__ . '/../src/Controller.php';

require __DIR__ . '/../src/MenuController.php';

require __DIR__ . '/../src/PageController.php';

require __DIR__ . '/../src/UtilisateurController.php';

require __DIR__ . '/../src/CommandeController.php';

require __DIR__ . '/../src/EmployeController.php';

require __DIR__ . '/../src/Mailer.php';

require __DIR__ . '/../src/AdminController.php';




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
} elseif ($chemin === '/commande-confirmee') {
    $controller = new CommandeController();
    $controller->confirmation();
} elseif ($chemin === '/mes-commandes') {
    $controller = new CommandeController();
    $controller->mesCommandes();
} elseif ($chemin === '/commande/annuler') {
    $controller = new CommandeController();
    $controller->annuler();
} elseif ($chemin === '/commande/modifier') {
    $controller = new CommandeController();
    $controller->modifier();
} elseif ($chemin === '/commande/suivi') {
    $controller = new CommandeController();
    $controller->suivi();
} elseif ($chemin === '/commande/avis') {
    $controller = new CommandeController();
    $controller->avis();
} elseif ($chemin === '/mes-infos') {
    $controller = new UtilisateurController();
    $controller->mesInfos();
} elseif ($chemin === '/employe') {
    $controller = new EmployeController();
    $controller->accueil();
} elseif ($chemin === '/api/commandes') {
    $controller = new EmployeController();
    $controller->apiCommandes();
} elseif ($chemin === '/employe/statut') {
    $controller = new EmployeController();
    $controller->changerStatut();
} elseif ($chemin === '/employe/annuler') {
    $controller = new EmployeController();
    $controller->annulerCommande();
} elseif ($chemin === '/employe/avis') {
    $controller = new EmployeController();
    $controller->avisEnAttente();
} elseif ($chemin === '/employe/avis/traiter') {
    $controller = new EmployeController();
    $controller->traiterAvis();
} elseif ($chemin === '/employe/menus') {
    $controller = new EmployeController();
    $controller->menus();
} elseif ($chemin === '/employe/menus/creer') {
    $controller = new EmployeController();
    $controller->creerMenu();
} elseif ($chemin === '/employe/menus/modifier') {
    $controller = new EmployeController();
    $controller->modifierMenu();
} elseif ($chemin === '/employe/menus/supprimer') {
    $controller = new EmployeController();
    $controller->supprimerMenu();
} elseif ($chemin === '/employe/plats') {
    $controller = new EmployeController();
    $controller->plats();
} elseif ($chemin === '/employe/plats/creer') {
    $controller = new EmployeController();
    $controller->creerPlat();
} elseif ($chemin === '/employe/plats/modifier') {
    $controller = new EmployeController();
    $controller->modifierPlat();
} elseif ($chemin === '/employe/plats/supprimer') {
    $controller = new EmployeController();
    $controller->supprimerPlat();
} elseif ($chemin === '/employe/horaires') {
    $controller = new EmployeController();
    $controller->horaires();
} elseif ($chemin === '/employe/horaires/modifier') {
    $controller = new EmployeController();
    $controller->modifierHoraires();
} elseif ($chemin === '/admin/employes') {
    $controller = new AdminController();
    $controller->employes();
} elseif ($chemin === '/admin/employes/creer') {
    $controller = new AdminController();
    $controller->creerEmploye();
} elseif ($chemin === '/admin/employes/actif') {
    $controller = new AdminController();
    $controller->changerActifEmploye();
}else {
    $controller = new PageController();
    $controller->introuvable();
}
