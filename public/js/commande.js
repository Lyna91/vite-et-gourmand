const TAUX_REMISE = 0.10;
const SEUIL_REMISE = 5;          // personnes au-delà du minimum
const FRAIS_LIVRAISON = 5;
const PRIX_KM = 0.59;

const formCommande = document.getElementById('form-commande');

const prixCommande = Number (formCommande.dataset.prix);
const miniPersonne = Number (formCommande.dataset.mini);

const nombrePersonne = document.getElementById('nbre_personne');
const distance = document.getElementById('distance');
const ville = document.getElementById('ville');
const prixMenu = document.getElementById('prix-menus');
const prixRemise = document.getElementById('prix-remise');
const prixLivraison = document.getElementById('prix-livraison');
const prixTotal = document.getElementById('prix-total');

function calculerPrix(){
    const nombre = Number (nombrePersonne.value);
    const trajet = Number (distance.value);
    const totalMenus = prixCommande * nombre;
    let remise = 0;
    if(nombre >= miniPersonne + SEUIL_REMISE){
        remise = totalMenus * TAUX_REMISE;
    }

    let livraison = 0;
    if(ville.value.trim().toLowerCase() !== "bordeaux"){
        livraison = FRAIS_LIVRAISON + PRIX_KM * trajet;
    }
    const total = totalMenus - remise + livraison;

    prixMenu.textContent = totalMenus.toFixed(2);
    prixRemise.textContent = remise.toFixed(2);
    prixLivraison.textContent = livraison.toFixed(2);
    prixTotal.textContent = total.toFixed(2);

}

calculerPrix();

nombrePersonne.addEventListener('input', calculerPrix);
ville.addEventListener('input', calculerPrix);
distance.addEventListener('input', calculerPrix);
