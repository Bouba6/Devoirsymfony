let currentPage = 1;
let totalPages = 1; // Définir un total initial

// Fonction pour charger les articles depuis le backend
function loadCours(page) {
    fetch(`/api/cours?page=${page}`)
        .then(response => response.json())
        .then(data => {

            displayCours(data.cours);
            totalPages = data.pagination.total_pages;
            currentPage = data.pagination.current_page;
            updatePagination(page, totalPages, currentPage); // Mettre à jour la pagination avec la nouvelle page
        })
        .catch(error => console.error('Erreur:', error));
}



function displayCours(cours) {
    const tbody = document.getElementById('articlesBody');
    tbody.innerHTML = ''; // Réinitialiser le contenu du tableau

    cours.forEach(cour => {
        const row = document.createElement('tr');
        console.log(cour);
        row.innerHTML = `
            <td class="py-3 px-6 text-left text-gray-700">${cour.id}</td>
            <td class="py-3 px-6 text-left text-gray-700">${cour.nomCours}</td>
            <td class="py-3 px-6 text-left text-gray-700">${cour.module}</td>      
        `;
        tbody.appendChild(row);
    });
}



function updatePagination(page, totalPages, currentPpage) {
    const prevButton = document.getElementById('prev');
    const nextButton = document.getElementById('next');

    prevButton.disabled = page === 1;
    nextButton.disabled = page >= totalPages;

    prevButton.onclick = () => {
        if (currentPage > 1) {

            currentPage--;
            loadCours(currentPage);
        }
    };

    nextButton.onclick = () => {

        console.log(totalPages);
        if (currentPage < totalPages) {

            currentPage++;
            loadCours(currentPage);
        }
    };
}

// Charger les articles au démarrage
loadCours(currentPage);