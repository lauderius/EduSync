document.addEventListener('DOMContentLoaded', () => {
    const loginSection = document.getElementById('admin-login');
    const registerSection = document.getElementById('admin-register');
    const dashboardSection = document.getElementById('admin-dashboard');

    const showRegisterLink = document.getElementById('show-register');
    const showLoginLink = document.getElementById('show-login');

    const loginForm = document.getElementById('login-form');
    const registerForm = document.getElementById('register-form');
    const schoolForm = document.getElementById('school-form');
    const schoolsList = document.getElementById('schools-list');

    let loggedInAdmin = null;

    function showSection(section) {
        loginSection.style.display = 'none';
        registerSection.style.display = 'none';
        dashboardSection.style.display = 'none';
        section.style.display = 'block';
    }

    showRegisterLink.addEventListener('click', (e) => {
        e.preventDefault();
        showSection(registerSection);
    });

    showLoginLink.addEventListener('click', (e) => {
        e.preventDefault();
        showSection(loginSection);
    });

    registerForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        const name = document.getElementById('register-name').value;
        const email = document.getElementById('register-email').value;
        const password = document.getElementById('register-password').value;
        const confirmPassword = document.getElementById('register-confirm-password').value;

        if (password !== confirmPassword) {
            alert('As senhas não coincidem.');
            return;
        }

        const success = await saveAdmin({ name, email, password });
        if (success) {
            alert('Administrador cadastrado com sucesso!');
            showSection(loginSection);
            registerForm.reset();
        } else {
            alert('Erro ao cadastrar administrador. O email já pode estar em uso.');
        }
    });

    loginForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        const email = document.getElementById('login-email').value;
        const password = document.getElementById('login-password').value;

        const admin = await validateLogin(email, password);
        if (admin) {
            loggedInAdmin = admin;
            showSection(dashboardSection);
            loadSchools();
        } else {
            alert('Email ou senha incorretos.');
        }
    });

    schoolForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        const school = {
            nome: document.getElementById('school-name').value,
            nivel: document.getElementById('school-level').value,
            curso: document.getElementById('school-course').value,
            periodo: document.getElementById('school-period').value,
            cidade: document.getElementById('school-city').value,
            estado: document.getElementById('school-state').value,
        };

        const success = await saveSchool(school);
        if (success) {
            alert('Escola salva com sucesso!');
            schoolForm.reset();
            loadSchools();
        } else {
            alert('Erro ao salvar a escola.');
        }
    });

    async function loadSchools() {
        const schools = await parseCSV('schools.csv');
        schoolsList.innerHTML = '';
        if (schools) {
            schools.forEach(school => {
                const schoolItem = document.createElement('div');
                schoolItem.className = 'school-item';
                schoolItem.innerHTML = `
                    <span>${school.nome} - ${school.cidade}</span>
                    <div class="actions">
                        <button class="edit-btn">Editar</button>
                        <button class="delete-btn">Excluir</button>
                    </div>
                `;
                schoolsList.appendChild(schoolItem);
            });
        }
    }
});
