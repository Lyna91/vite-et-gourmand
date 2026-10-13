</main>

<footer>
    <h2>Horaires</h2>
    <ul> <?php
            foreach ($horaires as $horaire) {
                if ($horaire->getHeureOuverture() === null) {
                    $heures = 'Fermé';
                } else {
                    $heures = substr($horaire->getHeureOuverture(), 0, 5) . " - " . substr($horaire->getHeureFermeture(), 0, 5);
                }
                echo "<li>" . htmlspecialchars($horaire->getJour()) . " : " . $heures . "</li>";
            }
            ?>
    </ul>
    <address>3 rue Notre-Dame, 33000 Bordeaux</address>
    <a href="/mentions-legales">Mentions légales</a>
    <a href="/cgv">Conditions générales de vente</a>
</footer>
</body>

</html>