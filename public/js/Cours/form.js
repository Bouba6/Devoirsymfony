
function loadClasses() {
    fetch(`/api/Classe`)
        .then(response => response.json())
        .then(data => {
            const select = document.getElementById('Classe');
            data.classes.forEach(classe => {
                const option = document.createElement('option');
                option.value = classe.id;
                option.text = classe.nom;
                select.appendChild(option);
            });
        })
        .catch(error => console.error('Erreur:', error));
}


function loadProfesseurs() {
    fetch(`/api/Professeur`)
        .then(response => response.json())
        .then(data => {
            const select = document.getElementById('Professeur');
            data.professeurs.forEach(professeur => {
                const option = document.createElement('option');
                option.value = professeur.id;
                option.text = professeur.nom;
                select.appendChild(option);
            });
        })
        .catch(error => console.error('Erreur:', error));
}

function loadModules() {
    fetch(`/api/Module`)
        .then(response => response.json())
        .then(data => {
            const select = document.getElementById('Module');
            data.modules.forEach(module => {
                const option = document.createElement('option');
                option.value = module.id;
                option.text = module.value;
                select.appendChild(option);
            });
        })
        .catch(error => console.error('Erreur:', error));
}



const form = document.getElementById('form');

form.addEventListener('submit', async (e) => {
    e.preventDefault();
    const nomCours = document.getElementById('nomCours').value;
    const ModuleId = document.getElementById('Module').value;
    const ClasseId = document.getElementById('Classe').value;
    const ProfesseurId = document.getElementById('Professeur').value;
    const formData = new FormData();
    formData.append('nomCours', nomCours);
    formData.append('ModuleId', ModuleId);
    formData.append('ClasseId', ClasseId);
    formData.append('ProfesseurId', ProfesseurId);
    try {
        const response = await fetch('http://127.0.0.1:8000/api/add/cours', {
            method: 'POST',
            body: formData
        });

        if (response.ok) {
            alert('Cours created successfully');
            window.location.href = '/cours';

        } else {
            console.error('Error creating cours');
        }
    } catch (error) {
        console.error('Error:', error);
    }
});



loadModules();
loadClasses();
loadProfesseurs();


