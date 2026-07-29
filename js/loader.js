import { openPortal } from './3d-scene.js';

document.addEventListener('DOMContentLoaded', () => {
    const loader = document.getElementById('loader');
    const loaderBar = document.querySelector('.loader-bar');
    const startButton = document.getElementById('start-button');
    const loaderText = document.querySelector('.loader-text');


    // Animación de la barra de carga
    let progress = 0;
    const loadingInterval = setInterval(() => {
        progress += Math.random() * 15;
        if (progress >= 100) {
            progress = 100; // Permitir llegar al 100%
            clearInterval(loadingInterval);

            // Mostrar el botón con animación solo cuando llegue al 100%
            setTimeout(() => {
                startButton.style.opacity = '1';
                startButton.style.visibility = 'visible';
                startButton.style.transform = 'translateY(0)';
                loaderText.textContent = '¡Listo para comenzar!';
            }, 100);
        }
        loaderBar.style.width = `${progress}%`;
    }, 200);

    // Manejar el clic en el botón de inicio
    startButton.addEventListener('click', () => {

        // Completar la barra de carga
        loaderBar.style.width = '100%';

        // Reproducir sonido si está disponible
        if (typeof audioManager !== 'undefined') {
            audioManager.unlock();
        }

        // Ocultar el loader con transición
        loader.style.opacity = '0';
        loader.style.pointerEvents = 'none'; // Evita que el loader bloquee los clics
        openPortal(() => {
            document.body.style.overflow = 'auto';

            // Eliminar el loader del DOM después de la animación
            setTimeout(() => {
                if (loader) {
                    loader.remove();
                }
            }, 1000);
        });
    });
});
