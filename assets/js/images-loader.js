document.addEventListener('DOMContentLoaded', function () {
    // Sélection des boutons et du conteneur où afficher les résultats
    const buttons = document.querySelectorAll("button[data-url]");
    const imageContainer = document.getElementById("image-container");

    if (!buttons.length) return; // Vérifie s'il y a des boutons avant d'exécuter le script

    // Fonction pour récupérer et afficher les images
    function fetchImages(url) {
        fetch(url)
            .then(response => response.json())
            .then(data => {
                imageContainer.innerHTML = ''; // Vide le conteneur avant d'ajouter les nouvelles images

                // Vérifie si aucune image n'est retournée
                if (data.length === 0) {
                    const message = document.createElement("p");
                    message.className = "text-center";
                    message.textContent = "Aucune image trouvée"; // Sécurité contre les failles XSS
                    imageContainer.appendChild(message);
                    return;
                }

                // Création des cartes Bootstrap pour chaque image
                data.forEach(image => {
                    const card = document.createElement('div');
                    card.className = "card m-2 shadow-sm border-0";
                    card.style.width = "250px";

                    // Création sécurisée de l'élément image
                    const cardImageDiv = document.createElement('div');
                    cardImageDiv.className = "card-image position-relative";

                    const img = document.createElement('img');
                    img.src = image.path; // Définit l'URL de l'image à partir des données
                    img.alt = `Image ${image.id}`;
                    img.className = "card-img-top rounded";
                    img.style.height = "180px";
                    img.style.objectFit = "cover";
                    img.style.transition = "transform 0.3s ease-in-out";

                    // Ajout de l'effet de zoom au survol
                    img.addEventListener("mouseover", () => img.style.transform = "scale(1.05)");
                    img.addEventListener("mouseout", () => img.style.transform = "scale(1)");

                    // Ajout de l'image sécurisée à la carte
                    cardImageDiv.appendChild(img);
                    card.appendChild(cardImageDiv);
                    imageContainer.appendChild(card);
                });
            })
            .catch(error => {
                console.error("Erreur lors du chargement des images:", error);
                const errorMessage = document.createElement("p");
                errorMessage.className = "text-danger";
                errorMessage.textContent = "Une erreur est survenue"; // Sécurité contre les failles XSS
                imageContainer.appendChild(errorMessage);
            });
    }

    // Ajout d'un écouteur d'événement sur chaque bouton
    buttons.forEach(button => {
        button.addEventListener('click', () => {
            fetchImages(button.dataset.url);
        });
    });
});






