<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Salvatechnology</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
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
                    <li><a href="#">Proyectos Realizados</a></li>
                    <li><a href="#">Clientes Ayudados</a></li>
                    <li><a href="#">Contacto</a></li>
                    <li><a href="#">Academia</a></li>
                    <li><a href="#">Servicios</a></li>
                    <li><a href="#">Newsletter</a></li>
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
</body>
</html>
