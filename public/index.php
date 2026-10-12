<?php

require __DIR__ . '/../src/database.php'; // require insère le contenu d'un autre fichier, __DIR__ indique le chemin du dossier du fichier actuel

require __DIR__ . '/../src/Menu.php';

require __DIR__ . '/../src/MenuManager.php';

require __DIR__ . '/../src/MenuController.php';


$chemin = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
if ($chemin === '/') {
    echo 'accueil';
} elseif ($chemin === '/menus') {
    $controller = new MenuController();
    $controller->liste();
} else {
    http_response_code(404);
    echo 'page introuvable';
}
