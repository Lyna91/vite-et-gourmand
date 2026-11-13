const canvas = document.getElementById('graphique-commandes');

if (canvas) {
    const menus = JSON.parse(canvas.dataset.menus);
    const nombres = JSON.parse(canvas.dataset.nombres);

    new Chart(canvas, { //graphique
        type: 'bar', //type de graphique (avec des barres ici)
        data: {
            labels: menus,
            datasets: [{
                label: 'Nombre de commandes',
                data: nombres, 
            }],
        },
        options: {
            scales: {
                y: { beginAtZero: true, ticks: { precision: 0 } }, //l’axe commence à 0 / seulement des nombres entiers.
            },
        },
    });
}