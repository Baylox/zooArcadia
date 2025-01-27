document.addEventListener('DOMContentLoaded', function () {
    const employesButton = document.getElementById('employes-button');
    const veterinairesButton = document.getElementById('veterinaires-button');
    const resultsContainer = document.getElementById('results-container');

    function handleUserFetch(url) {
        fetch(url)
            .then(response => response.json())
            .then(data => {
                resultsContainer.innerHTML = '';

                if (data.length === 0) {
                    resultsContainer.innerHTML = '<p>Aucun résultat trouvé</p>';
                    return;
                }

                // Crée un tableau pour afficher les données
                const table = document.createElement('table');
                table.classList.add('table', 'table-striped', 'table-bordered', 'mt-3');

                // Crée l'en-tête du tableau
                const thead = document.createElement('thead');
                thead.innerHTML = `
                    <tr>
                        <th>Email</th>
                        <th>Nom</th>
                        <th>Prénom</th>
                        <th>Rôles</th>
                    </tr>
                `;
                table.appendChild(thead);

                // Crée le corps du tableau
                const tbody = document.createElement('tbody');

                data.forEach(user => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${user.email}</td>
                        <td>${user.nom}</td>
                        <td>${user.prenom}</td>
                        <td>${(user.roles || []).join(' / ') || 'N/A'}</td>
                    `;
                    tbody.appendChild(row);
                });

                table.appendChild(tbody);
                resultsContainer.appendChild(table);
            })
            .catch(error => {
                resultsContainer.innerHTML = '<p>Une erreur est survenue</p>';
            });
    }

    employesButton?.addEventListener('click', () => {
        handleUserFetch(employesButton.dataset.url);
    });

    veterinairesButton?.addEventListener('click', () => {
        handleUserFetch(veterinairesButton.dataset.url);
    });
});
