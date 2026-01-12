<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Academia | Salvatechnology</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/guia.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'accent': '#ff8c00',
                        'dark-bg': '#000000',
                    }
                }
            }
        }
    </script>
    <script>window.hideModel = true;</script>
    <style>
        .page-fade-in {
            opacity: 0;
            transform: translateY(10px);
            transition: opacity 0.8s ease-out, transform 0.8s ease-out;
        }
        .page-fade-in.active {
            opacity: 1;
            transform: translateY(0);
        }
    </style>
</head>
<body class="text-white bg-black min-h-screen">

    <!-- Background Animation -->
    <canvas id="bg"></canvas>

    <!-- Main Content Wrapper -->
    <div id="page-content" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 relative z-10 page-fade-in">
        <!-- Site Header -->
        <header class="mb-16 flex justify-between items-center text-white">
            <div class="flex items-center gap-6">
                <div class="logo">
                    <a href="index.php">
                        <img src="img/logo.png" alt="Salva Technology Logo" class="h-12 hover:scale-105 transition-transform">
                    </a>
                </div>
                <a href="index.php" class="text-accent hover:text-white transition-colors flex items-center gap-2 font-mono text-xs group">
                    <svg class="w-4 h-4 transform group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                    VOLVER AL INICIO
                </a>
            </div>
            <div class="font-mono text-accent text-sm tracking-widest hidden md:block">
                CENTRO DE RECURSOS
            </div>
        </header>

        <main>
            <section class="text-center mb-20 text-white">
                <h1 class="text-5xl md:text-7xl font-black italic futuristic-title mb-6 leading-tight">
                    ACADEMIA <span class="text-white">SALVATECHNOLOGY</span>
                </h1>
                <p class="text-xl text-stone-300 max-w-2xl mx-auto leading-relaxed font-mono">
                    Conviértete en un desarrollador de élite. <br>
                    <span class="text-accent">Codifica, Construye, Monetiza.</span>
                </p>
            </section>

            <div class="grid md:grid-cols-3 gap-8">
                <!-- Card: Guía Maestra -->
                <a href="roadmap.php" class="glass-panel p-8 rounded-2xl card-hover group border border-accent/20 flex flex-col items-center text-center">
                    <div class="w-16 h-16 bg-accent/10 rounded-full flex items-center justify-center mb-6 border border-accent/30 group-hover:bg-accent/20 transition-all">
                        <svg class="w-8 h-8 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 21l3-1 3 1-.75-4M12 3v17m0-17l-3 3m3-3l3 3"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold mb-4 text-accent uppercase">RUTA DE APRENDIZAJE</h3>
                    <p class="text-stone-400 mb-6 flex-grow">Ruta interactiva paso a paso para convertirte en desarrollador impulsado por IA desde cero.</p>
                    <span class="px-6 py-2 border border-accent text-accent rounded-full text-sm font-bold group-hover:bg-accent group-hover:text-black transition-all">VER RUTA</span>
                </a>

                <!-- Card: Marca Personal -->
                <a href="branding.php" class="glass-panel p-8 rounded-2xl card-hover group border border-accent/20 flex flex-col items-center text-center">
                    <div class="w-16 h-16 bg-accent/10 rounded-full flex items-center justify-center mb-6 border border-accent/30 group-hover:bg-accent/20 transition-all">
                        <svg class="w-8 h-8 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold mb-4 text-accent uppercase">MARCA PERSONAL</h3>
                    <p class="text-stone-400 mb-6 flex-grow">Estrategias avanzadas para construir autoridad, atraer clientes y escalar tu marca propia.</p>
                    <span class="px-6 py-2 border border-accent text-accent rounded-full text-sm font-bold group-hover:bg-accent group-hover:text-black transition-all">VER RUTA</span>
                </a>

                <!-- Card: Monetización -->
                <a href="monetizacion.php" class="glass-panel p-8 rounded-2xl card-hover group border border-accent/20 flex flex-col items-center text-center">
                    <div class="w-16 h-16 bg-accent/10 rounded-full flex items-center justify-center mb-6 border border-accent/30 group-hover:bg-accent/20 transition-all">
                        <svg class="w-8 h-8 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold mb-4 text-accent uppercase">MONETIZACIÓN</h3>
                    <p class="text-stone-400 mb-6 flex-grow">Aprende a vender tu tecnología y construir sistemas escalables que generen ingresos recurrentes.</p>
                    <span class="px-6 py-2 border border-accent text-accent rounded-full text-sm font-bold group-hover:bg-accent group-hover:text-black transition-all">VER RUTA</span>
                </a>
            </div>
        </main>

        <footer class="mt-24 text-center">
            <p class="text-stone-600 text-xs font-mono tracking-tighter uppercase">© 2026 SALVATECHNOLOGY ACADEMY </p>
        </footer>
    </div>

    <!-- Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <script type="importmap">
        {
            "imports": {
                "three": "https://unpkg.com/three@0.157.0/build/three.module.js",
                "three/addons/": "https://unpkg.com/three@0.157.0/examples/jsm/"
            }
        }
    </script>
    <script type="module" src="js/3d-scene.js"></script>
    <script>
        // Animación suave de aparición para toda la página
        window.addEventListener('load', () => {
            const content = document.getElementById('page-content');
            setTimeout(() => {
                content.classList.add('active');
            }, 100);
        });
    </script>
</body>
</html>
