<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ruta de Aprendizaje | Salvatechnology</title>
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
        .roadmap-line {
            background: linear-gradient(180deg, transparent 0%, #ff8c00 15%, #ff8c00 85%, transparent 100%);
        }
        .step-inactive {
            filter: grayscale(1) opacity(0.5);
        }
        .glow-dot {
            box-shadow: 0 0 15px #ff8c00;
        }
    </style>
</head>
<body class="text-white bg-black min-h-screen">

    <!-- Background Animation -->
    <canvas id="bg"></canvas>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 relative z-10">
        <!-- Site Header -->
        <header class="mb-16 flex justify-between items-center">
            <div class="flex items-center gap-6">
                <div class="logo">
                    <a href="index.php">
                        <img src="img/logo.png" alt="Salva Technology Logo" class="h-12 hover:scale-105 transition-transform">
                    </a>
                </div>
                <a href="academia.php" class="text-accent hover:text-white transition-colors flex items-center gap-2 font-mono text-xs group">
                    <svg class="w-4 h-4 transform group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                    VOLVER A ACADEMIA
                </a>
            </div>
            <div class="font-mono text-accent text-sm tracking-widest hidden md:block">
                LEARNING PATH            </div>
        </header>

        <main class="max-w-4xl mx-auto">
            <section class="text-center mb-24">
                <h1 class="text-4xl md:text-6xl font-black italic futuristic-title mb-6 leading-tight">
                    RUTA <span class="text-white">SALVATECHNOLOGY</span>
                </h1>
                <p class="text-lg text-stone-400 font-mono">Domina el desarrollo web impulsado por la <span class="text-accent">IA</span>.</p>
            </section>

            <!-- Roadmap Container -->
            <div class="relative">
                <!-- Central vertical line -->
                <div class="absolute left-4 md:left-1/2 transform md:-translate-x-1/2 w-1 h-full roadmap-line z-0"></div>

                <!-- Step 1 -->
                <div class="relative z-10 mb-20 flex flex-col md:flex-row items-center justify-between group">
                    <div class="md:w-5/12 mb-8 md:mb-0 order-2 md:order-1">
                        <div class="glass-panel overflow-hidden rounded-2xl border border-accent/30 hover:border-accent transition-all">
                            <div class="h-32 overflow-hidden">
                                <img src="img/roadmap_frontend.png" alt="Frontend Fundamentals" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                            </div>
                            <div class="p-6">
                                <h3 class="text-xl font-bold text-accent mb-2">01. FUNDAMENTOS MODERNOS</h3>
                                <p class="text-stone-400 text-sm mb-4">HTML5 y CSS3 enfocado en layouts fluidos y diseño de interfaz (UI).</p>
                                <div class="flex flex-wrap gap-2">
                                    <a href="#" class="px-3 py-1 bg-accent/10 text-accent text-[10px] font-bold rounded border border-accent/20 hover:bg-accent hover:text-black transition-all">CURSO GRATIS</a>
                                    <a href="#" class="px-3 py-1 bg-white/5 text-stone-300 text-[10px] font-bold rounded border border-white/10 hover:bg-white/10 transition-all">PDF ESTRUCTURA</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="w-8 h-8 bg-accent rounded-full glow-dot z-20 order-1 md:order-2"></div>
                    <div class="md:w-5/12 order-3"></div>
                </div>

                <!-- Step 2 -->
                <div class="relative z-10 mb-20 flex flex-col md:flex-row items-center justify-between group">
                    <div class="md:w-5/12 order-3 md:order-1"></div>
                    <div class="w-8 h-8 bg-accent rounded-full glow-dot z-20 order-1 md:order-2"></div>
                    <div class="md:w-5/12 mb-8 md:mb-0 order-2 md:order-3">
                        <div class="glass-panel overflow-hidden rounded-2xl border border-accent/30 hover:border-accent transition-all text-right md:text-left">
                            <div class="h-32 overflow-hidden">
                                <img src="img/roadmap_logic.png" alt="Javascript Logic" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                            </div>
                            <div class="p-6">
                                <h3 class="text-xl font-bold text-accent mb-2">02. LÓGICA & JAVASCRIPT</h3>
                                <p class="text-stone-400 text-sm mb-4">Aprende a pensar como programador. Variables, funciones y manipulación del DOM.</p>
                                <div class="flex flex-wrap gap-2 justify-end md:justify-start">
                                    <a href="#" class="px-3 py-1 bg-accent/10 text-accent text-[10px] font-bold rounded border border-accent/20 hover:bg-accent hover:text-black transition-all">INTRO JS</a>
                                    <a href="#" class="px-3 py-1 bg-white/5 text-stone-300 text-[10px] font-bold rounded border border-white/10 hover:bg-white/10 transition-all">CHEAT SHEET</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Step 3 (Highlight: IA) -->
                <div class="relative z-10 mb-20 flex flex-col md:flex-row items-center justify-between group">
                    <div class="md:w-5/12 mb-8 md:mb-0 order-2 md:order-1">
                        <div class="glass-panel overflow-hidden rounded-2xl border-2 border-accent shadow-[0_0_30px_rgba(255,140,0,0.2)]">
                            <div class="h-32 overflow-hidden">
                                <img src="img/project_isp.png" alt="AI Co-coding" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                            </div>
                            <div class="p-6">
                                <div class="inline-block px-2 py-1 bg-accent text-black text-[10px] font-black rounded mb-2">RECOMENDADO</div>
                                <h3 class="text-2xl font-bold text-accent mb-2">03. CO-PROGRAMACIÓN IA</h3>
                                <p class="text-stone-300 text-sm mb-4">Prompt Engineering aplicado al código. Deja que la IA multiplique tu velocidad x10.</p>
                                <div class="flex flex-wrap gap-2">
                                    <a href="guia.php" class="px-4 py-2 bg-accent text-black text-xs font-bold rounded shadow-lg hover:scale-105 transition-transform">IR A LA GUÍA MAESTRA</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center glow-dot border-4 border-accent z-20 order-1 md:order-2">
                        <svg class="w-6 h-6 text-accent" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2L4.5 20.29l.71.71L12 18l6.79 3 .71-.71L12 2z"/></svg>
                    </div>
                    <div class="md:w-5/12 order-3"></div>
                </div>

                <!-- Step 4 -->
                <div class="relative z-10 mb-20 flex flex-col md:flex-row items-center justify-between group">
                    <div class="md:w-5/12 order-3 md:order-1"></div>
                    <div class="w-8 h-8 bg-accent rounded-full glow-dot z-20 order-1 md:order-2"></div>
                    <div class="md:w-5/12 mb-8 md:mb-0 order-2 md:order-3">
                        <div class="glass-panel overflow-hidden rounded-2xl border border-accent/30 hover:border-accent transition-all">
                            <div class="h-32 overflow-hidden">
                                <img src="img/project_retail.png" alt="Backend and Data" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                            </div>
                            <div class="p-6">
                                <h3 class="text-xl font-bold text-accent mb-2">04. BACKEND & DATOS</h3>
                                <p class="text-stone-400 text-sm mb-4">Sistemas CRUD, PHP y bases de datos relacionales para manejar información real.</p>
                                <div class="flex flex-wrap gap-2">
                                    <a href="#" class="px-3 py-1 bg-white/5 text-stone-300 text-[10px] font-bold rounded border border-white/10 opacity-50 cursor-not-allowed">PRÓXIMAMENTE</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Step 5 -->
                <div class="relative z-10 flex flex-col md:flex-row items-center justify-between group">
                    <div class="md:w-5/12 mb-8 md:mb-0 order-2 md:order-1">
                        <div class="glass-panel overflow-hidden rounded-2xl border border-accent/30 hover:border-accent transition-all">
                            <div class="h-32 overflow-hidden">
                                <img src="img/project_food.png" alt="Professional Launch" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                            </div>
                            <div class="p-6">
                                <h3 class="text-xl font-bold text-accent mb-2">05. LANZAMIENTO PRO</h3>
                                <p class="text-stone-400 text-sm mb-4">Marca personal y monetización. De desarrollador a dueño de producto SaaS.</p>
                                <div class="flex flex-wrap gap-2">
                                    <a href="#" class="px-3 py-1 bg-white/5 text-stone-300 text-[10px] font-bold rounded border border-white/10 opacity-50 cursor-not-allowed">BLOQUEADO</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="w-8 h-8 bg-accent rounded-full glow-dot z-20 order-1 md:order-2"></div>
                    <div class="md:w-5/12 order-3"></div>
                </div>
            </div>
        </main>

        <footer class="mt-32 text-center">
            <p class="text-stone-600 text-xs font-mono tracking-tighter uppercase">© 2026 SALVATECHNOLOGY ACADEMY // APRENDIZAJE ACELERADO</p>
        </footer>
    </div>

    <!-- Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollTrigger.min.js"></script>
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
        gsap.registerPlugin(ScrollTrigger);

        // Animation for steps
        gsap.utils.toArray('.z-10').forEach((step, i) => {
            gsap.from(step, {
                scrollTrigger: {
                    trigger: step,
                    start: "top 85%",
                    toggleActions: "play none none reverse"
                },
                y: 50,
                opacity: 0,
                duration: 0.8,
                ease: "power2.out"
            });
        });
    </script>

</body>
</html>
