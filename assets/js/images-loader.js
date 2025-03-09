document.addEventListener('DOMContentLoaded', function () {
    const buttons = document.querySelectorAll("button[data-url]");
    const imageContainer = document.getElementById("image-container");

    if (!buttons.length) return; // Vérifie s'il y a des boutons avant d'exécuter le script

    // Fonction pour récupérer et afficher les images
    function fetchImages(url) {
        fetch(url)
            .then(response => response.json())
            .then(data => {
                imageContainer.innerHTML = ''; // Vide le conteneur avant d'ajouter les nouvelles images

                if (data.length === 0) {
                    imageContainer.innerHTML = '<p class="text-center">Aucune image trouvée</p>';
                    return;
                }

                data.forEach(image => {
                    const card = document.createElement('div');
                    card.className = "card m-2 shadow-sm border-0";
                    card.style.width = "250px"; 
                
                    card.innerHTML = `
                        <div class="card-image position-relative">
                            <img src="${image.path}" alt="Image ${image.id}" 
                                class="card-img-top rounded" 
                                style="height: 180px; object-fit: cover; transition: transform 0.3s ease-in-out;">
                        </div>
                    `;
                
                    // Ajout de l'effet de zoom au survol
                    card.querySelector("img").addEventListener("mouseover", () => {
                        card.querySelector("img").style.transform = "scale(1.05)";
                    });
                    card.querySelector("img").addEventListener("mouseout", () => {
                        card.querySelector("img").style.transform = "scale(1)";
                    });
                
                    imageContainer.appendChild(card);
                });
            })
            .catch(error => {
                console.error("Erreur lors du chargement des images:", error);
                imageContainer.innerHTML = '<p class="text-danger">Une erreur est survenue</p>';
            });
    }

    // Ajoute un écouteur d'événement sur chaque bouton
    buttons.forEach(button => {
        button.addEventListener('click', () => {
            fetchImages(button.dataset.url);
        });
    });
});





