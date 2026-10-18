</main>

<footer class="py-4">
    <div class="container">
        <div class="row align-items md-center">
            <div class="col-12 col-md-4 text-center text-md-start ">
                <h2>Horaires</h2>
                <ul class="list-unstyled">
                    <?php
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
            </div>
            <div class="text-center col-12 col-md-4">
                <iframe width="100%" height="200" src="https://www.openstreetmap.org/export/embed?bbox=-0.5741617083549501%2C44.848852312775136%2C-0.5718281865119935%2C44.850099752066164&amp;layer=mapnik" loading="lazy" title="Plan d'accès : 3 rue Notre-Dame, Bordeaux" style="border: 1px solid black"></iframe>
                <address>3 rue Notre-Dame, 33000 Bordeaux</address>
            </div>
            <div class="col-12 col-md-4 text-center text-md-end">
                <ul class="list-unstyled mb-0">
                    <li class="mb-2"><a href="/mentions-legales">Mentions légales</a></li>
                    <li><a href="/cgv">Conditions générales de vente</a></li>
                </ul>
            </div>
        </div>
    </div>
</footer>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>

</html>