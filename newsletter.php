<?php require_once __DIR__ . '/config/app.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Newsletter | Salvatechnology</title>
    <base href="<?= BASE_URL ?>">
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
        .form-input {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 140, 0, 0.2);
            transition: all 0.3s ease;
        }
        .form-input:focus {
            border-color: #ff8c00;
            box-shadow: 0 0 15px rgba(255, 140, 0, 0.3);
            outline: none;
            background: rgba(255, 255, 255, 0.05);
        }
        .benefit-card {
            background: rgba(255, 255, 255, 0.02);
            border: 1px solid rgba(255, 255, 255, 0.05);
            transition: all 0.4s ease;
        }
        .benefit-card:hover {
            border-color: rgba(255, 140, 0, 0.3);
            background: rgba(255, 255, 255, 0.05);
            transform: translateY(-5px);
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
                    <a href="<?= BASE_URL ?>">
                        <img src="img/logo.png" alt="Salva Technology Logo" class="h-12 hover:scale-105 transition-transform">
                    </a>
                </div>
                <a href="<?= BASE_URL ?>" class="text-accent hover:text-white transition-colors flex items-center gap-2 font-mono text-xs group">
                    <svg class="w-4 h-4 transform group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                    VOLVER AL INICIO
                </a>
            </div>
            <div class="font-mono text-accent text-sm tracking-widest hidden md:block">
                INSIDER ACCESS
            </div>
        </header>

        <main class="max-w-4xl mx-auto text-center">
            <!-- Hero -->
            <section class="mb-20">
                <h1 class="text-5xl md:text-7xl font-black italic futuristic-title mb-6 leading-tight">
                    NEWSLETTER: <span class="text-white">CÓDIGO & ESTRATEGIA</span>
                </h1>
                <p class="text-xl text-stone-400 max-w-2xl mx-auto leading-relaxed font-mono">
                    Únete a más de <span class="text-accent">2,000+ builders</span>. Recibe tips técnicos, recursos exclusivos y estrategias de monetización cada semana.
                </p>
            </section>

            <!-- Subscription Form -->
            <section class="mb-24 glass-panel p-10 rounded-3xl border border-accent/20 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-accent/5 blur-3xl rounded-full -translate-y-1/2 translate-x-1/2"></div>
                
                <h2 class="text-2xl font-bold mb-8 uppercase tracking-tighter">Únete a la élite tecnológica</h2>
                <form action="#" method="POST" class="flex flex-col md:flex-row gap-4 max-w-lg mx-auto">
                    <input type="email" placeholder="Tu email principal..." required 
                           class="flex-grow px-6 py-4 rounded-xl form-input text-white font-mono">
                    <button type="submit" 
                            class="px-8 py-4 bg-accent text-black font-black uppercase tracking-widest rounded-xl hover:bg-orange-600 hover:scale-105 transition-all shadow-[0_0_20px_rgba(255,140,0,0.4)]">
                        SUSCRIBIRSE
                    </button>
                </form>
                <p class="text-stone-500 text-[10px] mt-6 font-mono uppercase tracking-widest">Cero spam. Solo valor real. Cancela cuando quieras.</p>
            </section>

            <!-- Benefits Grid -->
            <section class="grid md:grid-cols-3 gap-6 text-left">
                <div class="benefit-card p-8 rounded-2xl">
                    <div class="text-accent mb-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                    </div>
                    <h3 class="font-bold text-white mb-2 uppercase text-sm">RECURSOS GRATIS</h3>
                    <p class="text-stone-400 text-xs leading-relaxed">PDFs, Roadmaps y Blueprints técnicos exclusivos solo para suscriptores.</p>
                </div>
                <div class="benefit-card p-8 rounded-2xl">
                    <div class="text-accent mb-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    </div>
                    <h3 class="font-bold text-white mb-2 uppercase text-sm">TIPS DE IA</h3>
                    <p class="text-stone-400 text-xs leading-relaxed">Las últimas herramientas y flujos de trabajo automatizados para programar 10x más rápido.</p>
                </div>
                <div class="benefit-card p-8 rounded-2xl">
                    <div class="text-accent mb-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <h3 class="font-bold text-white mb-2 uppercase text-sm">MONETIZACIÓN</h3>
                    <p class="text-stone-400 text-xs leading-relaxed">Estrategias psicológicas y de ventas para cerrar clientes de alto valor sin esfuerzo.</p>
                </div>
            </section>
        </main>

        <footer class="mt-32 text-center">
            <p class="text-stone-600 text-xs font-mono tracking-tighter uppercase">© 2026 SALVATECHNOLOGY // INSIDER LIST </p>
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
