
const form = document.getElementById('form');

form.addEventListener('submit', async (e) => {
    e.preventDefault();
    const niveau = document.getElementById('niveau').value;
    const formData = new FormData();
    formData.append('niveau', niveau);
    try {
        const response = await fetch('http://127.0.0.1:8000/api/add/niveau', {
            method: 'POST',
            body: formData
        });

        if (response.ok) {
            alert('Niveau created successfully');
            window.location.href = '/niveaux';

        } else {
            console.error('Error creating niveau');
        }
    } catch (error) {
        console.error('Error:', error);
    }
});