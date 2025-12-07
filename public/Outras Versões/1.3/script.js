// script.js - Main logic for EduSync chat

let schools = [];

document.addEventListener('DOMContentLoaded', async () => {
    schools = await loadSchoolsFromCSV('schools.csv');
    console.log('Schools loaded:', schools);
});

document.getElementById('send-btn').addEventListener('click', handleSend);
document.getElementById('user-input').addEventListener('keypress', (e) => {
    if (e.key === 'Enter') {
        handleSend();
    }
});

function handleSend() {
    const input = document.getElementById('user-input');
    const query = input.value.trim();
    if (!query) return;

    addMessage(query, 'user');
    input.value = '';

    // Simulate AI processing
    setTimeout(() => {
        const response = generateResponse(query);
        addMessage(response, 'ai');
    }, 1000);
}

function addMessage(text, sender) {
    const messages = document.getElementById('messages');
    const messageDiv = document.createElement('div');
    messageDiv.className = `message ${sender}-message`;
    messageDiv.textContent = text;
    messages.appendChild(messageDiv);
    messages.scrollTop = messages.scrollHeight;
}

function generateResponse(query) {
    const filteredSchools = filterSchools(schools, query);
    if (filteredSchools.length === 0) {
        return "Desculpe, não encontrei escolas que correspondam à sua consulta. Tente reformular sua pergunta.";
    }

    let response = "Aqui estão algumas escolas que podem interessar:\n\n";
    filteredSchools.forEach((school, index) => {
        response += `${index + 1}. ${school.nome} - ${school.nivel}, ${school.curso}, ${school.periodo}, ${school.cidade}, ${school.estado}\n`;
    });
    return response;
}
