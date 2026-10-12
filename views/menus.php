   <?php
    /** @var Menu[] $menus */
    /** @var string|null $erreur */
    require __DIR__ . '/morceaux/header.php';
    ?>

   <?php require __DIR__ . '/morceaux/header.php'; ?>

   <h1>Nos menus</h1>
   <?php
    if ($erreur) {
        echo "<p>" . htmlspecialchars($erreur) . "</p>";
    }
    foreach ($menus as $menu) {
        echo "<h2>" . htmlspecialchars($menu->getTitre()) . "</h2>";
        echo "<p>" . number_format($menu->getPrixParPersonne(), 2, ',', ' ') . " €</p>"; //number_format renvoie un texte qui ne contient que des chiffres, une virgule et des espaces
    }

    ?>


   <?php require __DIR__ . '/morceaux/footer.php'; ?>