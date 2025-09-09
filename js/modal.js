document.addEventListener('DOMContentLoaded', () => {
    const contactLink = document.getElementById('contact-link');
    const contactModal = document.getElementById('contact-modal');
    const closeModal = document.querySelector('.close-modal');

    if (contactLink && contactModal && closeModal) {
        contactLink.addEventListener('click', (e) => {
            e.preventDefault();
            contactModal.style.display = 'flex';
        });

        const hideModal = () => {
            contactModal.style.display = 'none';
        };

        closeModal.addEventListener('click', hideModal);

        window.addEventListener('click', (e) => {
            if (e.target === contactModal) {
                hideModal();
            }
        });

        const form = contactModal.querySelector('form');
        const formContainer = contactModal.querySelector('.contact-form');

        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            const formData = new FormData(form);
            
            try {
                const response = await fetch('contact.php', {
                    method: 'POST',
                    body: formData
                });

                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }

                const result = await response.json();

                let responseHTML = '';
                if (result.status === 'success') {
                    responseHTML = `
                        <div class='form-response success'>
                            <h1>¡Gracias!</h1>
                            <p>${result.message}</p>
                        </div>`;
                } else {
                    responseHTML = `
                        <div class='form-response error'>
                            <h1>Error</h1>
                            <p>${result.message}</p>
                        </div>`;
                }
                formContainer.innerHTML = responseHTML;

            } catch (error) {
                formContainer.innerHTML = `
                    <div class='form-response error'>
                        <h1>Error</h1>
                        <p>Ocurrió un problema al enviar tu mensaje. Por favor, inténtalo de nuevo más tarde.</p>
                    </div>`;
                console.error('Fetch error:', error);
            }
        });
    }
});
