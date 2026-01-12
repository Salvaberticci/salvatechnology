<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Salvatechnology</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="overflow-hidden">
    <script>
        // Pre-check for loader session
        if (sessionStorage.getItem('loader_shown')) {
            document.write('<style>#loader { display: none !important; }</style>');
            document.body.classList.remove('overflow-hidden');
            window.skipLoader = true;
        }
    </script>
    <div id="loader">
        <div class="loader-grid"></div>
        <div class="loader-content">
            <div class="loader-logo">
                <img src="img/logo.png" alt="Salva Technology Logo">
            </div>
            <div class="loader-text">INICIALIZANDO SISTEMA...</div>
            <div class="loader-bar-container">
                <div class="loader-bar"></div>
            </div>
            <button id="start-button">Iniciar</button>
        </div>
    </div>
    <canvas id="bg"></canvas>
    <div class="background-title">Salvatechnology</div>
    <div class="overlay">
        <header>
            <div class="logo">
                <img src="img/logo.png" alt="Salva Technology Logo">
            </div>
        </header>
        <div class="main-content">
            <div class="menu">
                <h2>Menú</h2>
                <ul>
                    <li><a href="clientes.php">Clientes Ayudados</a></li>
                    <li><a href="#" id="contact-link">Contacto</a></li>
                    <li><a href="academia.php">Academia</a></li>
                    <li><a href="servicios.php">Servicios</a></li>
                    <li><a href="newsletter.php">Newsletter</a></li>
                </ul>
            </div>
            <div class="search-bar">
                <input type="text" placeholder="Pregúntame lo que sea...">
            </div>
        </div>
    </div>

    <script type="importmap">
        {
            "imports": {
                "three": "https://unpkg.com/three@0.157.0/build/three.module.js",
                "three/addons/": "https://unpkg.com/three@0.157.0/examples/jsm/"
            }
        }
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <script type="module" src="js/portal-animation.js"></script>
    <script type="module" src="js/3d-scene.js"></script>
    <script src="js/audio-manager.js"></script>
    <script type="module" src="js/loader.js"></script>
    <script src="js/menu-sound.js"></script>

    <!-- Modal de Contacto -->
    <div id="contact-modal" class="modal-overlay">
        <div class="modal-content">
            <span class="close-modal">&times;</span>
            <div class="contact-form">
                <h1>Contacto</h1>
                <form action="contact.php" method="POST">
                    <div class="input-group">
                        <label for="name">Nombre</label>
                        <input type="text" id="name" name="name" required>
                    </div>
                    <div class="input-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" required>
                    </div>
                    <div class="input-group">
                        <label for="message">Mensaje</label>
                        <textarea id="message" name="message" required></textarea>
                    </div>
                    <button type="submit" class="submit-btn">Enviar Mensaje</button>
                </form>
            </div>
        </div>
    </div>

    <script src="js/modal.js" defer></script>
</body>
</html>
