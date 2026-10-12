<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menus Vite &amp; Gourmand</title>
</head>

<body>
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


</body>

</html>