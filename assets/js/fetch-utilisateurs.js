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
                data.forEach(user => {
                    const userElement = document.createElement('div');
                    userElement.classList.add('user-card', 'mb-3', 'p-3', 'border');
                    userElement.innerHTML = `
                        <p>Email: ${user.email || 'N/A'}</p>
                        <p>Nom: ${user.nom || 'N/A'}</p>
                        <p>Prénom: ${user.prenom || 'N/A'}</p>
                        <p>Rôles: ${(user.roles || []).join(', ') || 'N/A'}</p>
                    `;
                    resultsContainer.appendChild(userElement);
                });
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
