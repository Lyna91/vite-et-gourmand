<?php
require __DIR__ . '/../src/database.php'; // require insère le contenu d'un autre fichier, __DIR__ indique le chemin du dossier du fichier actuel


try {
    $pdo = getPdo();

    $requete = $pdo->query("SELECT titre, prix_par_personne FROM menu");   // envoie la requête SQL à MySQL (prendre les titres et prix par personne de la table menu)
    $menus = $requete->fetchAll(PDO::FETCH_ASSOC);                          // récupère toutes les lignes sous forme de tableaux associatifs   

    foreach ($menus as $menu) {
        echo "<h2>" . htmlspecialchars($menu['titre']) . "</h2>";
        echo "<p>" . number_format($menu['prix_par_personne'], 2, ',', ' ') . " €</p>"; //number_format renvoie un texte qui ne contient que des chiffres, une virgule et des espaces
    }
} catch (PDOException $e) {
    echo "Erreur de base de données : " . $e->getMessage();
}
