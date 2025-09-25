// Controle de tema escuro/claro
document.addEventListener('DOMContentLoaded', function() {
    const themeToggle = document.getElementById('theme-toggle');
    const htmlElement = document.documentElement;
    
    // Verificar tema salvo no localStorage
    const savedTheme = localStorage.getItem('theme') || 'light';
    htmlElement.setAttribute('data-bs-theme', savedTheme);
    
    // Definir ícone correto no carregamento
    if (themeToggle) {
        const icon = themeToggle.querySelector('i');
        if (savedTheme === 'dark') {
            icon.className = 'fas fa-sun';
        } else {
            icon.className = 'fas fa-moon';
        }
    }
    
    if (themeToggle) {
        themeToggle.addEventListener('click', function() {
            const currentTheme = htmlElement.getAttribute('data-bs-theme');
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
            
            htmlElement.setAttribute('data-bs-theme', newTheme);
            localStorage.setItem('theme', newTheme);
            
            // Atualizar ícone
            const icon = themeToggle.querySelector('i');
            if (newTheme === 'dark') {
                icon.className = 'fas fa-sun';
            } else {
                icon.className = 'fas fa-moon';
            }
        });
    }
});

// Validação do formulário de criação de projeto
function validateProjectName(name) {
    const regex = /^[a-zA-Z0-9_-]+$/;
    return regex.test(name) && name.length > 0;
}

// Confirmação para substituir arquivo existente
function confirmReplace(filename) {
    return confirm(`O arquivo "${filename}" já existe. Deseja substituí-lo?`);
}

// Upload de arquivo com validação
function handleFileUpload() {
    const fileInput = document.getElementById('file-upload');
    const file = fileInput.files[0];
    
    if (file && file.type === 'application/json') {
        return true;
    } else {
        alert('Por favor, selecione um arquivo JSON válido.');
        return false;
    }
}

// Deletar arquivo com confirmação
function deleteFile(filename) {
    if (confirm(`Tem certeza que deseja excluir o arquivo "${filename}"?`)) {
        window.location.href = `?action=delete&file=${encodeURIComponent(filename)}`;
    }
}

// Editor: navegação entre seções
function showSection(section) {
    // Esconder todas as seções
    const sections = document.querySelectorAll('.editor-section');
    sections.forEach(s => s.style.display = 'none');
    
    // Mostrar seção selecionada
    const targetSection = document.getElementById(section + '-section');
    if (targetSection) {
        targetSection.style.display = 'block';
    }
    
    // Atualizar navegação ativa
    const navLinks = document.querySelectorAll('.editor-nav .nav-link');
    navLinks.forEach(link => link.classList.remove('active'));
    
    const activeLink = document.querySelector(`.editor-nav .nav-link[onclick="showSection('${section}')"]`);
    if (activeLink) {
        activeLink.classList.add('active');
    }
}

// Inicializar primeira seção no editor
document.addEventListener('DOMContentLoaded', function() {
    if (document.querySelector('.editor-nav')) {
        showSection('header');
    }
});