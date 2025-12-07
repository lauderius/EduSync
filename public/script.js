document.addEventListener('DOMContentLoaded', () => {
    const startBtn = document.getElementById('start-btn');
    const sendBtn = document.getElementById('send-btn');
    const userInput = document.getElementById('user-input');
    const chatBox = document.getElementById('chat-box');

    let chatStarted = false;

    const initialMessage = "👋 Olá! Seja bem-vindo ao EduSync. Clique em Começar para encontrar escolas ideais para você.";

    function addMessage(message, sender) {
        const messageElement = document.createElement('div');
        messageElement.classList.add('chat-message', `${sender}-message`);
        messageElement.textContent = message;
        chatBox.appendChild(messageElement);
        chatBox.scrollTop = chatBox.scrollHeight;
    }

    function startChat() {
        if (chatStarted) return;
        chatStarted = true;
        startBtn.style.display = 'none';
        userInput.disabled = false;
        sendBtn.disabled = false;
        addMessage(initialMessage, 'ia');
    }

    async function handleUserInput() {
        const message = userInput.value.trim();
        if (!message) return;

        addMessage(message, 'user');
        userInput.value = '';

        const response = await getIaResponse(message);
        addMessage(response, 'ia');
    }

    async function getIaResponse(userMessage) {
        const lowerCaseMessage = userMessage.toLowerCase();

        if (lowerCaseMessage.includes('olá')) {
            return 'Olá! Tudo ótimo 😊, como posso te ajudar hoje?';
        }
        if (lowerCaseMessage.includes('tudo bem')) {
            return 'Tudo ótimo, obrigado por perguntar! E você?';
        }
        if (lowerCaseMessage.includes('quem é você')) {
            return 'Sou o EduSync, seu assistente inteligente para encontrar escolas perfeitas.';
        }

        return searchSchools(userMessage);
    }

    async function searchSchools(query) {
        const schools = await parseCSV('schools.csv');
        if (!schools) {
            return 'Desculpe, estou com problemas para acessar a lista de escolas no momento.';
        }

        const filters = extractFilters(query);
        const filteredSchools = schools.filter(school => {
            return Object.keys(filters).every(key => {
                return school[key] && school[key].toLowerCase().includes(filters[key]);
            });
        });

        if (filteredSchools.length === 0) {
            return 'Desculpe, não encontrei escolas com esses critérios. Deseja tentar outros filtros?';
        }

        let response = 'Encontrei as seguintes escolas:\n\n';
        filteredSchools.slice(0, 5).forEach(school => {
            response += `- ${school.nome}, ${school.nivel}, ${school.curso}, ${school.periodo} em ${school.cidade}, ${school.estado}\n`;
        });

        return response;
    }

    function extractFilters(query) {
        const filters = {};
        const lowerQuery = query.toLowerCase();

        // Simple keyword extraction
        const keywords = {
            nivel: ['ensino médio', 'ensino fundamental', 'ensino superior'],
            curso: ['informática', 'gestão', 'saúde', 'engenharia'],
            periodo: ['manhã', 'tarde', 'noite'],
            cidade: ['luanda', 'lisboa', 'são paulo'], // Add more cities as needed
            estado: ['luanda', 'lisboa', 'são paulo'] // Add more states as needed
        };

        for (const key in keywords) {
            for (const value of keywords[key]) {
                if (lowerQuery.includes(value)) {
                    filters[key] = value;
                }
            }
        }
        return filters;
    }

    startBtn.addEventListener('click', startChat);
    sendBtn.addEventListener('click', handleUserInput);
    userInput.addEventListener('keypress', (e) => {
        if (e.key === 'Enter') {
            handleUserInput();
        }
    });
});
