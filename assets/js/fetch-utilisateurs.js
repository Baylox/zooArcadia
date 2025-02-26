// Attente du chargement du DOM avant d'exécuter le script
document.addEventListener('DOMContentLoaded', function () {
    // Sélection des boutons et du conteneur où afficher les résultats
    const employesButton = document.getElementById('employes-button');
    const veterinairesButton = document.getElementById('veterinaires-button');
    const resultsContainer = document.getElementById('results-container');

    // Ne fait rien si les deux boutons sont absents
    if (!employesButton && !veterinairesButton) {
        return; 
    }

    // Fonction pour récupérer et afficher les données utilisateur
    function handleUserFetch(url) {
        fetch(url) // Envoi de la requête HTTP GET vers l'URL fournie
            .then(response => response.json()) // Conversion de la réponse en JSON
            .then(data => {
                // Nettoyage du conteneur avant d'afficher de nouveaux résultats
                resultsContainer.innerHTML = '';

                // Vérifie si aucune donnée n'est retournée
                if (data.length === 0) {
                    resultsContainer.innerHTML = '<p>Aucun résultat trouvé</p>';
                    return; 
                }

                // Création de l'élément <table> pour afficher les données
                const table = document.createElement('table');
                table.classList.add('table', 'table-striped', 'table-bordered', 'mt-3'); // Ajout de classes Bootstrap pour le style

                // Création de l'en-tête du tableau <thead>
                const thead = document.createElement('thead');
                thead.innerHTML = `
                    <tr>
                        <th>Email</th>
                        <th>Nom</th>
                        <th>Prénom</th>
                        <th>Rôles</th>
                    </tr>
                `;
                table.appendChild(thead); // Ajoute l'en-tête au tableau

                // Création du corps du tableau <tbody>
                const tbody = document.createElement('tbody');

                // Parcours du tableau de données et création des lignes <tr> pour chaque utilisateur
                data.forEach(user => {
                    const row = document.createElement('tr'); // Création d'une ligne <tr>
                    row.innerHTML = `
                        <td>${user.email}</td>
                        <td>${user.nom}</td>
                        <td>${user.prenom}</td>
                        <td>${(user.roles || []).join(' / ') || 'N/A'}</td> 
                    `;
                    tbody.appendChild(row); // Ajout de la ligne au <tbody>
                });

                table.appendChild(tbody); // Ajoute le <tbody> au tableau
                resultsContainer.appendChild(table); // Ajoute le tableau au conteneur des résultats
            })
            .catch(error => {
                // Gestion des erreurs en affichant un message à l'utilisateur
                resultsContainer.innerHTML = '<p>Une erreur est survenue</p>';
            });
    }

    // Ajout d'un écouteur d'événements sur le bouton des employés
    employesButton.addEventListener('click', () => {
        handleUserFetch(employesButton.dataset.url); // Récupère l'URL stockée dans `data-url` et appelle la fonction
    });

    // Ajout d'un écouteur d'événements sur le bouton des vétérinaires
    veterinairesButton.addEventListener('click', () => {
        handleUserFetch(veterinairesButton.dataset.url); // Récupère l'URL stockée dans `data-url` et appelle la fonction
    });
});

