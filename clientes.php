<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clientes | Salvatechnology</title>
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
                    <a href="/salvatechnology/">
                        <img src="img/logo.png" alt="Salva Technology Logo" class="h-12 hover:scale-105 transition-transform">
                    </a>
                </div>
                <a href="/salvatechnology/" class="text-accent hover:text-white transition-colors flex items-center gap-2 font-mono text-xs group">
                    <svg class="w-4 h-4 transform group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                    VOLVER AL INICIO
                </a>
            </div>
            <div class="font-mono text-accent text-sm tracking-widest hidden md:block">
                CLIENTES & CASOS DE ÉXITO
            </div>
        </header>

        <main>
            <section class="text-center mb-20">
                <h1 class="text-5xl md:text-7xl font-black italic futuristic-title mb-6 leading-tight">
                    CLIENTES <span class="text-white">AYUDADOS</span>
                </h1>
                <p class="text-xl text-stone-300 max-w-2xl mx-auto leading-relaxed font-mono">
                    Sistemas robustos diseñados para la <span class="text-accent">eficiencia máxima</span>.
                </p>
            </section>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-10">
                <!-- Project: ISP -->
                <div class="glass-panel overflow-hidden rounded-2xl border border-accent/20 card-hover group">
                    <div class="h-48 overflow-hidden relative">
                        <img src="img/project_isp.png" alt="ISP Dashboard" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/80 to-transparent"></div>
                    </div>
                    <div class="p-8">
                        <h3 class="text-xl font-bold text-accent mb-2">TELECOMUNICACIONES (ISP)</h3>
                        <p class="text-stone-400 text-sm mb-4">Automatización de nómina e inventario masivo para proveedores de internet.</p>
                        <div class="flex gap-2">
                            <span class="px-2 py-1 bg-white/5 text-[10px] font-mono text-stone-500 rounded border border-white/5">BACKEND AI</span>
                            <span class="px-2 py-1 bg-white/5 text-[10px] font-mono text-stone-500 rounded border border-white/5">DATABASE</span>
                        </div>
                    </div>
                </div>

                <!-- Project: Retail -->
                <div class="glass-panel overflow-hidden rounded-2xl border border-accent/20 card-hover group">
                    <div class="h-48 overflow-hidden relative">
                        <img src="img/project_retail.png" alt="Retail System" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/80 to-transparent"></div>
                    </div>
                    <div class="p-8">
                        <h3 class="text-xl font-bold text-accent mb-2">GESTIÓN RETAIL</h3>
                        <p class="text-stone-400 text-sm mb-4">Sincronización de inventario en tiempo real para cadenas de tiendas físicas.</p>
                        <div class="flex gap-2">
                            <span class="px-2 py-1 bg-white/5 text-[10px] font-mono text-stone-500 rounded border border-white/5">REAL-TIME</span>
                            <span class="px-2 py-1 bg-white/5 text-[10px] font-mono text-stone-500 rounded border border-white/5">INVENTORY</span>
                        </div>
                    </div>
                </div>

                <!-- Project: Food -->
                <div class="glass-panel overflow-hidden rounded-2xl border border-accent/20 card-hover group">
                    <div class="h-48 overflow-hidden relative">
                        <img src="img/project_food.png" alt="Food Trazability" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/80 to-transparent"></div>
                    </div>
                    <div class="p-8">
                        <h3 class="text-xl font-bold text-accent mb-2">TRAZABILIDAD FOOD</h3>
                        <p class="text-stone-400 text-sm mb-4">Control total de márgenes y desperdicios en producción de alimentos.</p>
                        <div class="flex gap-2">
                            <span class="px-2 py-1 bg-white/5 text-[10px] font-mono text-stone-500 rounded border border-white/5">LOGISTICS</span>
                            <span class="px-2 py-1 bg-white/5 text-[10px] font-mono text-stone-500 rounded border border-white/5">FINANCE</span>
                        </div>
                    </div>
                </div>
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
