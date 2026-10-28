const formCommande = document.getElementById('form-commande');

const prixCommande = Number (formCommande.dataset.prix);
const miniPersonne = Number (formCommande.dataset.mini);

console.log(typeof prixCommande,typeof miniPersonne);