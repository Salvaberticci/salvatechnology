document.addEventListener('DOMContentLoaded', () => {
    const menuLinks = document.querySelectorAll('.menu a');

    menuLinks.forEach(link => {
        link.addEventListener('mouseenter', () => {
            audioManager.playSound('glitch');
        });
    });
});
