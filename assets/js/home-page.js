// Home Page JavaScript - Lightweight Bundle
// Apenas funcionalidades essenciais para a página inicial

class HomePageManager {
  constructor() {
    this.init();
  }

  init() {
    this.setupThemeSystem();
    this.setupAnimations();
    this.setupFileManagement();
    this.setupParticleBackground();
  }

  // Sistema de temas leve
  setupThemeSystem() {
    const themeToggle = document.getElementById('theme-toggle');
    if (!themeToggle) return;

    // Detectar tema salvo ou preferência do sistema
    const savedTheme = localStorage.getItem('theme');
    const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
    const theme = savedTheme || (prefersDark ? 'dark' : 'light');
    
    this.setTheme(theme);

    themeToggle.addEventListener('click', () => {
      const currentTheme = document.documentElement.getAttribute('data-bs-theme');
      const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
      this.setTheme(newTheme);
    });
  }

  setTheme(theme) {
    document.documentElement.setAttribute('data-bs-theme', theme);
    localStorage.setItem('theme', theme);
    
    const icon = document.querySelector('#theme-toggle i');
    if (icon) {
      icon.className = theme === 'dark' ? 'fas fa-sun' : 'fas fa-moon';
    }
  }

  // Animações simples
  setupAnimations() {
    // Animação de entrada para cards
    const observerOptions = {
      threshold: 0.1,
      rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.style.opacity = '1';
          entry.target.style.transform = 'translateY(0)';
        }
      });
    }, observerOptions);

    // Observar cards de ação
    document.querySelectorAll('.action-card').forEach(card => {
      card.style.opacity = '0';
      card.style.transform = 'translateY(20px)';
      card.style.transition = 'all 0.6s cubic-bezier(0.4, 0, 0.2, 1)';
      observer.observe(card);
    });
  }

  // Gerenciamento básico de arquivos
  setupFileManagement() {
    // Deletar arquivo
    document.addEventListener('click', (e) => {
      if (e.target.closest('.btn-delete-file')) {
        const filename = e.target.closest('.btn-delete-file').dataset.filename;
        if (confirm(`Tem certeza que deseja excluir "${filename}"?`)) {
          this.deleteFile(filename);
        }
      }
    });

    // Upload de arquivo
    const uploadInput = document.getElementById('upload-file');
    if (uploadInput) {
      uploadInput.addEventListener('change', (e) => {
        if (e.target.files.length > 0) {
          this.uploadFile(e.target.files[0]);
        }
      });
    }
  }

  async deleteFile(filename) {
    try {
      const formData = new FormData();
      formData.append('action', 'delete_file');
      formData.append('filename', filename);

      const response = await fetch('index.php', {
        method: 'POST',
        body: formData
      });

      if (response.ok) {
        location.reload();
      } else {
        throw new Error('Erro ao excluir arquivo');
      }
    } catch (error) {
      alert('Erro ao excluir arquivo: ' + error.message);
    }
  }

  async uploadFile(file) {
    try {
      const formData = new FormData();
      formData.append('action', 'upload_file');
      formData.append('file', file);

      const response = await fetch('index.php', {
        method: 'POST',
        body: formData
      });

      if (response.ok) {
        location.reload();
      } else {
        throw new Error('Erro ao fazer upload');
      }
    } catch (error) {
      alert('Erro no upload: ' + error.message);
    }
  }

  // Efeito de partículas leve
  setupParticleBackground() {
    const hero = document.querySelector('.home-hero');
    if (!hero) return;

    // Criar canvas para partículas
    const canvas = document.createElement('canvas');
    canvas.style.position = 'absolute';
    canvas.style.top = '0';
    canvas.style.left = '0';
    canvas.style.width = '100%';
    canvas.style.height = '100%';
    canvas.style.pointerEvents = 'none';
    canvas.style.opacity = '0.6';
    hero.appendChild(canvas);

    const ctx = canvas.getContext('2d');
    let animationId;
    let particles = [];

    const resizeCanvas = () => {
      canvas.width = hero.offsetWidth;
      canvas.height = hero.offsetHeight;
    };

    const createParticles = () => {
      particles = [];
      const particleCount = Math.floor((canvas.width * canvas.height) / 15000);
      
      for (let i = 0; i < particleCount; i++) {
        particles.push({
          x: Math.random() * canvas.width,
          y: Math.random() * canvas.height,
          radius: Math.random() * 2 + 1,
          vx: (Math.random() - 0.5) * 0.5,
          vy: (Math.random() - 0.5) * 0.5,
          alpha: Math.random() * 0.5 + 0.1
        });
      }
    };

    const animate = () => {
      ctx.clearRect(0, 0, canvas.width, canvas.height);

      particles.forEach(particle => {
        // Mover partícula
        particle.x += particle.vx;
        particle.y += particle.vy;

        // Wrapping
        if (particle.x < 0) particle.x = canvas.width;
        if (particle.x > canvas.width) particle.x = 0;
        if (particle.y < 0) particle.y = canvas.height;
        if (particle.y > canvas.height) particle.y = 0;

        // Desenhar partícula
        ctx.beginPath();
        ctx.arc(particle.x, particle.y, particle.radius, 0, Math.PI * 2);
        ctx.fillStyle = `rgba(99, 102, 241, ${particle.alpha})`;
        ctx.fill();
      });

      animationId = requestAnimationFrame(animate);
    };

    // Inicializar
    resizeCanvas();
    createParticles();
    animate();

    // Redimensionar quando necessário
    window.addEventListener('resize', () => {
      resizeCanvas();
      createParticles();
    });

    // Pausar animação quando não visível
    document.addEventListener('visibilitychange', () => {
      if (document.hidden) {
        cancelAnimationFrame(animationId);
      } else {
        animate();
      }
    });
  }
}

// Inicializar quando DOM estiver pronto
document.addEventListener('DOMContentLoaded', () => {
  new HomePageManager();
});

// Função global para navegação
function navigateToEditor(filename = null) {
  const url = filename ? `editor.php?file=${encodeURIComponent(filename)}` : 'editor.php';
  window.location.href = url;
}

function navigateToPreview(filename) {
  window.location.href = `preview.php?file=${encodeURIComponent(filename)}`;
}

// Exportar para uso global
window.HomePageManager = HomePageManager;