<?php

//base de données
require __DIR__ . '/../src/database.php'; // require insère le contenu d'un autre fichier, __DIR__ indique le chemin du dossier du fichier actuel

//modèles
require __DIR__ . '/../src/Menu.php';

require __DIR__ . '/../src/MenuManager.php';

require __DIR__ . '/../src/Plat.php';

require __DIR__ . '/../src/PlatManager.php';

require __DIR__ . '/../src/Horaire.php';

require __DIR__ . '/../src/HoraireManager.php';


//controlleurs
require __DIR__ . '/../src/Controller.php';

require __DIR__ . '/../src/MenuController.php';

require __DIR__ . '/../src/PageController.php';



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
} else {
    $controller = new PageController();
    $controller->introuvable();
}
