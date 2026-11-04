const listeCommande = document.getElementById('liste-commandes');
const filtreStatut = document.getElementById('filtre-statut'); 
const filtreClient = document.getElementById('filtre-client');

function echapper(texte) {
    return String(texte)
        .replaceAll('&', '&amp;')
        .replaceAll('<', '&lt;')
        .replaceAll('>', '&gt;')
        .replaceAll('"', '&quot;')
        .replaceAll("'", '&#039;');
}

async function chargerCommandes() {
    const url = '/api/commandes?statut=' + encodeURIComponent(filtreStatut.value) + 
    '&client=' + encodeURIComponent(filtreClient.value);

    const reponse = await fetch(url);
    const commandes = await reponse.json();

    if (commandes.length === 0) {
        listeCommande.innerHTML = '<tr><td colspan="8">Aucune commande.</td></tr>';
        return;
    }


    let lignes = '';
    for(const commande of commandes){
    const total = Number(commande.prix_menu) - Number(commande.montant_reduction) + Number(commande.frais_livraison)

    lignes += '<tr>'
        + '<td>' + echapper(commande.numero_commande) + '</td>'
        + '<td>' + echapper(commande.date_livraison) + ' à ' + echapper(commande.heure_livraison) + '</td>'
        + '<td>' + echapper(commande.nom) + ' ' + echapper(commande.prenom) + '</td>'
        + '<td>' + echapper(commande.email) + '<br>' + echapper(commande.telephone) + '</td>'
        + '<td>' + echapper(commande.titre) + '</td>'
        + '<td>' + echapper(commande.nbre_personne) + '</td>'
        + '<td>' + total.toFixed(2) + ' €</td>'
        + '<td>' + echapper(commande.statut) + '</td>'
        + '</tr>';

    }

    listeCommande.innerHTML = lignes;

}                                        // ← la fonction se ferme ICI, après tout le reste

filtreStatut.addEventListener('change', chargerCommandes);
filtreClient.addEventListener('input', chargerCommandes);
