let currentPage = 1;
let totalPages = 1; // Définir un total initial

// Fonction pour charger les articles depuis le backend
function loadNiveau(page) {
    fetch(`/api/niveau?page=${page}`)
        .then(response => response.json())
        .then(data => {

            displayNiveau(data.niveaux);
            totalPages = data.pagination.total_pages;
            currentPage = data.pagination.current_page;
            updatePagination(page, totalPages, currentPage); // Mettre à jour la pagination avec la nouvelle page
        })
        .catch(error => console.error('Erreur:', error));
}



function displayNiveau(niveaux) {
    const tbody = document.getElementById('articlesBody');
    tbody.innerHTML = ''; // Réinitialiser le contenu du tableau

    niveaux.forEach(niveau => {
        const row = document.createElement('tr');
        console.log(niveau);
        row.innerHTML = `
            <td class="py-3 px-6 text-left text-gray-700">${niveau.id}</td>
            <td class="py-3 px-6 text-left text-gray-700">${niveau.nomNiveau}</td>      
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
            loadNiveau(currentPage);
        }
    };

    nextButton.onclick = () => {

        console.log(totalPages);
        if (currentPage < totalPages) {

            currentPage++;
            loadNiveau(currentPage);
        }
    };
}

loadNiveau(currentPage);