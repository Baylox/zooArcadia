// Attente du chargement complet du DOM avant d'exécuter le script
document.addEventListener('DOMContentLoaded', function () {
    // Sélection des boutons et du conteneur où afficher les résultats
    const employesButton = document.getElementById('employes-button');
    const veterinairesButton = document.getElementById('veterinaires-button');
    const resultsContainer = document.getElementById('results-container');

    // Vérification : si les deux boutons sont absents, on arrête l'exécution
    if (!employesButton && !veterinairesButton) {
        return; 
    }

    // Fonction pour récupérer et afficher les données utilisateur en évitant les failles XSS
    function handleUserFetch(url) {
        fetch(url) // Envoi d'une requête HTTP GET vers l'URL fournie
            .then(response => response.json()) // Conversion de la réponse en JSON
            .then(data => {
                // Nettoyage du conteneur avant d'afficher de nouveaux résultats
                resultsContainer.innerHTML = '';

                // Vérifie si aucune donnée n'est retournée
                if (data.length === 0) {
                    const message = document.createElement("p");
                    message.textContent = "Aucun résultat trouvé"; // Sécurisé contre les failles XSS
                    resultsContainer.appendChild(message);
                    return;
                }

                // Création de l'élément <table> pour afficher les données
                const table = document.createElement('table');
                table.classList.add('table', 'table-striped', 'table-bordered', 'mt-3'); // Ajout de classes Bootstrap

                // Création de l'en-tête du tableau <thead>
                const thead = document.createElement('thead');
                const headerRow = document.createElement('tr');

                // Définition des en-têtes de colonnes en toute sécurité
                ["Email", "Nom", "Prénom", "Rôles"].forEach(text => {
                    const th = document.createElement('th');
                    th.textContent = text; // Utilisation de textContent pour éviter XSS
                    headerRow.appendChild(th);
                });

                thead.appendChild(headerRow);
                table.appendChild(thead); // Ajout de l'en-tête au tableau

                // Création du corps du tableau <tbody>
                const tbody = document.createElement('tbody');

                // Parcours du tableau de données et création des lignes pour chaque utilisateur
                data.forEach(user => {
                    const row = document.createElement('tr'); // Création d'une ligne <tr>

                    // Création sécurisée des cellules de la ligne sans injection XSS
                    const emailCell = document.createElement('td');
                    emailCell.textContent = user.email; // Sécurisé

                    const nomCell = document.createElement('td');
                    nomCell.textContent = user.nom;

                    const prenomCell = document.createElement('td');
                    prenomCell.textContent = user.prenom;

                    const rolesCell = document.createElement('td');
                    rolesCell.textContent = (user.roles || []).join(' / ') || 'N/A';

                    // Ajout des cellules à la ligne
                    row.appendChild(emailCell);
                    row.appendChild(nomCell);
                    row.appendChild(prenomCell);
                    row.appendChild(rolesCell);

                    tbody.appendChild(row); // Ajout de la ligne au <tbody>
                });

                table.appendChild(tbody); // Ajoute le <tbody> au tableau
                resultsContainer.appendChild(table); // Ajoute le tableau au conteneur des résultats
            })
            .catch(error => {
                console.error("Erreur lors du chargement des utilisateurs :", error);
                const errorMessage = document.createElement("p");
                errorMessage.textContent = "Une erreur est survenue"; // Sécurisé contre XSS
                errorMessage.className = "text-danger";
                resultsContainer.appendChild(errorMessage);
            });
    }

    // Ajout d'un écouteur d'événements sur le bouton des employés
    employesButton.addEventListener('click', () => {
        handleUserFetch(employesButton.dataset.url); 
    });

    // Ajout d'un écouteur d'événements sur le bouton des vétérinaires
    veterinairesButton.addEventListener('click', () => {
        handleUserFetch(veterinairesButton.dataset.url); 
    });
});


