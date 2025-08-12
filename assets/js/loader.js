window.addEventListener('load', () => {
    const loader = document.getElementById('loader');
    const startButton = document.getElementById('start-button');
    const loaderText = document.getElementById('loaderText');
    const loaderBar = document.querySelector('.loader-bar');
    const animatedLogo = document.getElementById('animatedLogo');
    const word = 'SALVATECHNOLOGY';
    let currentChar = 0;
    let isAnimating = false;

    // Función para animar el logo
    const animateLogo = () => {
        if (currentChar < word.length) {
            animatedLogo.textContent = word.substring(0, currentChar + 1);
            currentChar++;
            setTimeout(animateLogo, 100); // Velocidad de la animación (100ms por letra)
        } else if (!isAnimating) {
            // Iniciar animación de rebote al terminar
            isAnimating = true;
            setTimeout(() => {
                animatedLogo.classList.add('bounce');
            }, 300);
        }
    };

    // Iniciar animación del logo después de un breve retraso
    setTimeout(animateLogo, 1000);

    // Función para manejar el final de la animación de la barra
    const handleAnimationEnd = () => {
        // Asegurarse de que la palabra completa esté mostrada
        animatedLogo.textContent = word;
        // Cambiar el texto a "SISTEMA LISTO"
        loaderText.textContent = 'SISTEMA LISTO';
        // Agregar clase para animación de texto
        loaderText.classList.add('ready');
        // Mostrar el botón de inicio
        startButton.style.display = 'block';
    };

    // Asegurarse de que el evento se dispare correctamente
    loaderBar.addEventListener('animationend', handleAnimationEnd, { once: true });

    // Configuración de partículas
    const particlesContainer = document.getElementById('particles');
    const particleCount = 100;
    const particles = [];

    // Crear partículas
    function createParticles() {
        for (let i = 0; i < particleCount; i++) {
            const particle = document.createElement('div');
            particle.className = 'particle';
            
            // Tamaño aleatorio entre 2px y 6px
            const size = Math.random() * 4 + 2;
            particle.style.width = `${size}px`;
            particle.style.height = `${size}px`;
            
            // Posición inicial aleatoria
            particle.style.left = `${Math.random() * 100}%`;
            particle.style.top = `${Math.random() * 100}%`;
            
            // Color con variación
            const hue = 30 + Math.random() * 30; // Tonos naranjas
            particle.style.background = `hsl(${hue}, 100%, 60%)`;
            particle.style.boxShadow = `0 0 ${size * 2}px hsl(${hue}, 100%, 60%)`;
            
            // Añadir al contenedor
            particlesContainer.appendChild(particle);
            particles.push({
                element: particle,
                x: Math.random() * window.innerWidth,
                y: Math.random() * window.innerHeight,
                size: size,
                speedX: (Math.random() - 0.5) * 10,
                speedY: (Math.random() - 0.5) * 10,
                angle: Math.random() * Math.PI * 2,
                angleSpeed: (Math.random() - 0.5) * 0.1
            });
        }
    }

    // Animar partículas
    function animateParticles() {
        particles.forEach(particle => {
            particle.angle += particle.angleSpeed;
            particle.x += Math.cos(particle.angle) * 2;
            particle.y += Math.sin(particle.angle) * 2;
            
            particle.element.style.transform = `translate3d(${particle.x}px, ${particle.y}px, 0) rotate(${particle.angle}rad)`;
            
            // Rebotar en los bordes
            if (particle.x < 0 || particle.x > window.innerWidth) {
                particle.speedX *= -1;
            }
            if (particle.y < 0 || particle.y > window.innerHeight) {
                particle.speedY *= -1;
            }
        });
        
        requestAnimationFrame(animateParticles);
    }

    // Iniciar animación de partículas
    createParticles();
    let animationId;

    // Manejar el clic en el botón de inicio
    startButton.addEventListener('click', () => {
        // Reproducir sonido si está disponible
        if (typeof audioManager !== 'undefined') {
            audioManager.unlock();
        }

        // Mostrar partículas
        particlesContainer.classList.add('visible');
        
        // Iniciar animación de partículas
        animationId = requestAnimationFrame(animateParticles);
        
        // Ocultar loader con efecto 3D
        loader.classList.add('hidden');
        
        // Mostrar contenido principal con retraso
        setTimeout(() => {
            document.querySelector('.overlay').classList.add('visible');
            
            // Detener animación de partículas después de la transición
            setTimeout(() => {
                cancelAnimationFrame(animationId);
                particlesContainer.style.opacity = '0';
                setTimeout(() => {
                    particlesContainer.style.display = 'none';
                }, 1000);
            }, 2000);
            
        }, 1000);
    }, { once: true });
});
