
// équivalent de htmlspecialchars() en PHP : protège contre le XSS
function echapper(texte) {
    return String(texte)
        .replaceAll('&', '&amp;')
        .replaceAll('<', '&lt;')
        .replaceAll('>', '&gt;')
        .replaceAll('"', '&quot;')
        .replaceAll("'", '&#039;');
}

// fabrique une carte Bootstrap par menu, dans la zone #liste-menus
function afficherMenus(menus) {
    const zone = document.getElementById('liste-menus');
    zone.innerHTML = '';   // on vide la zone avant de redessiner

    for (const menu of menus) { //c’est le foreach du JavaScript
        const prix = menu.prix.toLocaleString('fr-FR', { minimumFractionDigits: 2 });

        zone.innerHTML += ` 
            <div class="col-12 col-md-6 col-lg-4 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <h2 class="card-title h5"> ${echapper(menu.titre)}</h2>
                        <p class="card-text">${echapper(menu.description)}</p>
                        <p>À partir de ${menu.nbrePersonneMini} personnes</p>
                        <p><strong>${prix} € / personne</strong></p>
                        <a href="/menu?id=${menu.id}" class="btn btn-primary">Voir le détail</a>
                    </div>
                </div>
            </div>
        `;
    }       // les backticks `` permettent d’écrire un texte sur plusieurs lignes, et d’y glisser des valeurs avec ${...}. Equivalent du <?= ... ?> des vues PHP
}           // innerHTML le contenu HTML d’une zone. = '' la vide, et += ajoute une carte à la suite
            //toLocaleString('fr-FR', ...) : formate le prix à la française (60,00). C’est l’équivalent de number_format
            //menu.titre, menu.prix… : ce sont les noms choisis dans toArray(), à gauche des =>.


// va chercher les menus auprès du serveur, en arrière-plan
async function chargerMenus() {                             // "async" obligatoire pour avoir le droit d’utiliser await dans la fonction ;
    const prixMax = document.getElementById('prix-max').value;
    const prixMin = document.getElementById('prix-min').value;     
    const reponse = await fetch(`/api/menus?prixMin=${prixMin}&prixMax=${prixMax}`); 
    const menus = await reponse.json();                     //attends la réponse avant de passer à la ligne suivante ». Le serveur met quelques millisecondes à répondre, et sans await, la ligne suivante s’exécuterait trop tôt, avec rien dedans ;
    afficherMenus(menus);
}
//reponse.json() : transforme le texte JSON en vrai tableau JavaScript, qu’on peut utiliser ;
chargerMenus();     //l’affiche dans la console, pour vérifier.

// à chaque fois que le visiteur tape dans le champ, on recharge les menus
document.getElementById('prix-min').addEventListener('input', chargerMenus);
document.getElementById('prix-max').addEventListener('input', chargerMenus);

//addEventListener('input', ...) veut dire « écoute le champ, et à chaque modification, lance chargerMenus ». C’est ce qui rend le filtre instantané.
                    
