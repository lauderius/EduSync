// admin.js - Admin panel logic for EduSync

let schools = [];
let editingId = null;

document.addEventListener('DOMContentLoaded', async () => {
    schools = await loadSchoolsFromCSV('schools.csv');
    displaySchools();
});

document.getElementById('school-form').addEventListener('submit', handleFormSubmit);
document.getElementById('cancel-btn').addEventListener('click', cancelEdit);

function handleFormSubmit(e) {
    e.preventDefault();
    const formData = new FormData(e.target);
    const school = {
        nome: formData.get('nome'),
        nivel: formData.get('nivel'),
        curso: formData.get('curso'),
        periodo: formData.get('periodo'),
        cidade: formData.get('cidade'),
        estado: formData.get('estado')
    };

    if (editingId !== null) {
        // Update existing
        schools[editingId] = school;
        editingId = null;
    } else {
        // Add new
        schools.push(school);
    }

    // Simulate saving to CSV (in a real app, this would save to server)
    console.log('Schools updated:', schools);
    displaySchools();
    e.target.reset();
}

function cancelEdit() {
    editingId = null;
    document.getElementById('school-form').reset();
}

function displaySchools() {
    const list = document.getElementById('schools-list');
    list.innerHTML = '';
    schools.forEach((school, index) => {
        const li = document.createElement('li');
        li.innerHTML = `
            ${school.nome} - ${school.nivel}, ${school.curso}, ${school.periodo}, ${school.cidade}, ${school.estado}
            <button onclick="editSchool(${index})">Editar</button>
            <button onclick="deleteSchool(${index})">Remover</button>
        `;
        list.appendChild(li);
    });
}

function editSchool(index) {
    const school = schools[index];
    document.getElementById('nome').value = school.nome;
    document.getElementById('nivel').value = school.nivel;
    document.getElementById('curso').value = school.curso;
    document.getElementById('periodo').value = school.periodo;
    document.getElementById('cidade').value = school.cidade;
    document.getElementById('estado').value = school.estado;
    editingId = index;
}

function deleteSchool(index) {
    schools.splice(index, 1);
    displaySchools();
}
