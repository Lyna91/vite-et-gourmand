// va chercher les menus auprès du serveur, en arrière-plan
async function chargerMenus() { // "async" obligatoire pour avoir le droit d’utiliser await dans la fonction ;
    const reponse = await fetch('/api/menus'); //envoie la demande au serveur, mais en arrière-plan ;
    const menus = await reponse.json(); //attends la réponse avant de passer à la ligne suivante ». Le serveur met quelques millisecondes à répondre, et sans await, la ligne suivante s’exécuterait trop tôt, avec rien dedans ;
    console.log(menus);
}

chargerMenus(); //l’affiche dans la console, pour vérifier.

//reponse.json() : transforme le texte JSON en vrai tableau JavaScript, qu’on peut utiliser ;
