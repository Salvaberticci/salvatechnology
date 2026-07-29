<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Servicios & Productos | Salvatechnology</title>
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
        html, body {
            overflow-y: auto !important;
            height: auto !important;
            min-height: 100vh;
        }
        .page-fade-in {
            opacity: 0;
            transform: translateY(10px);
            transition: opacity 0.8s ease-out, transform 0.8s ease-out;
        }
        .page-fade-in.active {
            opacity: 1;
            transform: translateY(0);
        }
        .service-card {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 140, 0, 0.1);
            backdrop-filter: blur(10px);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .service-card:hover {
            border-color: rgba(255, 140, 0, 0.5);
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.4), 0 0 20px rgba(255, 140, 0, 0.1);
            background: rgba(255, 255, 255, 0.05);
        }
        .price-badge {
            background: linear-gradient(135deg, #ff8c00 0%, #ff5500 100%);
            box-shadow: 0 4px 15px rgba(255, 140, 0, 0.3);
        }
    </style>
</head>
<body class="text-white bg-black">

    <!-- Background Animation -->
    <canvas id="bg"></canvas>

    <!-- Main Content Wrapper -->
    <div id="page-content" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 relative z-10 page-fade-in">
        <!-- Site Header -->
        <header class="mb-16 flex justify-between items-center">
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
            <div class="font-mono text-accent text-sm tracking-widest hidden md:block uppercase">
                Premium Services Tier
            </div>
        </header>

        <main>
            <section class="text-center mb-24">
                <h1 class="text-4xl md:text-6xl font-black italic futuristic-title mb-6 leading-tight">
                    SERVICIOS <span class="text-white">& PRODUCTOS</span>
                </h1>
                <p class="text-lg text-stone-400 font-mono max-w-2xl mx-auto">Invierte en tu futuro. Recursos de élite diseñados para llevar tu carrera al <span class="text-accent underline">siguiente nivel</span>.</p>
            </section>

            <!-- Services Grid -->
            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8 pb-32">
                
                <!-- Product: Ebook -->
                <div class="service-card p-8 rounded-3xl flex flex-col group relative overflow-hidden">
                    <div class="absolute top-4 right-4 price-badge px-4 py-1 rounded-full text-black font-black text-sm z-20">
                        $19.99
                    </div>
                    <div class="w-16 h-16 bg-accent/10 rounded-2xl flex items-center justify-center mb-8 border border-accent/20 group-hover:bg-accent/20 transition-all">
                        <svg class="w-8 h-8 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                    </div>
                    <h3 class="text-xl font-black italic text-white mb-4 uppercase tracking-tighter">EBOOK: PRODUCTIVIDAD IA</h3>
                    <p class="text-stone-400 text-sm mb-8 flex-grow leading-relaxed">Domina las herramientas de IA para programar 10x más rápido. Flujos de trabajo reales para desarrolladores modernos.</p>
                    <a href="landing-ebook-ia.php" class="w-full py-4 bg-white/5 border border-white/10 rounded-xl text-center text-xs font-black uppercase tracking-widest hover:bg-accent hover:text-black hover:border-accent transition-all">ADQUIRIR AHORA</a>
                </div>

                <!-- Product: Masterclass/Curso -->
                <div class="service-card p-8 rounded-3xl flex flex-col group relative overflow-hidden">
                    <div class="absolute top-4 right-4 price-badge px-4 py-1 rounded-full text-black font-black text-sm z-20">
                        $97.00
                    </div>
                    <div class="w-16 h-16 bg-accent/20 rounded-2xl flex items-center justify-center mb-8 border border-accent/30 group-hover:bg-accent/30 transition-all">
                        <svg class="w-8 h-8 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                    </div>
                    <h3 class="text-xl font-black italic text-white mb-4 uppercase tracking-tighter">CURSO: FULLSTACK AI CORE</h3>
                    <p class="text-stone-300 text-sm mb-8 flex-grow leading-relaxed">Pasa de cero a desplegar aplicaciones SaaS integrando modelos de lenguaje. Formación técnica de alto impacto.</p>
                    <a href="landing-curso-fullstack.php" class="w-full py-4 bg-white/5 border border-white/10 rounded-xl text-center text-xs font-black uppercase tracking-widest hover:bg-accent hover:text-black hover:border-accent transition-all">RESERVAR CUPO</a>
                </div>

                <!-- Product: Mentoria -->
                <div class="service-card p-8 rounded-3xl flex flex-col group relative overflow-hidden">
                    <div class="absolute top-4 right-4 price-badge px-4 py-1 rounded-full text-black font-black text-sm z-20">
                        $299/m
                    </div>
                    <div class="w-16 h-16 bg-accent/10 rounded-2xl flex items-center justify-center mb-8 border border-accent/20 group-hover:bg-accent/20 transition-all">
                        <svg class="w-8 h-8 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    </div>
                    <h3 class="text-xl font-black italic text-white mb-4 uppercase tracking-tighter">MENTORÍA 1-A-1</h3>
                    <p class="text-stone-400 text-sm mb-8 flex-grow leading-relaxed">Acompañamiento personalizado para escalar tu carrera o negocio tech. Estrategia directa y resolución técnica.</p>
                    <a href="landing-mentoria.php" class="w-full py-4 bg-white/5 border border-white/10 rounded-xl text-center text-xs font-black uppercase tracking-widest hover:bg-accent hover:text-black hover:border-accent transition-all">AGENDAR SESIÓN</a>
                </div>

                <!-- Product: Clase Especializada -->
                <div class="service-card p-8 rounded-3xl flex flex-col group relative overflow-hidden">
                    <div class="absolute top-4 right-4 price-badge px-4 py-1 rounded-full text-black font-black text-sm z-20">
                        $49.00
                    </div>
                    <div class="w-16 h-16 bg-accent/10 rounded-2xl flex items-center justify-center mb-8 border border-accent/20 group-hover:bg-accent/20 transition-all">
                        <svg class="w-8 h-8 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                    </div>
                    <h3 class="text-xl font-black italic text-white mb-4 uppercase tracking-tighter">WORKSHOP: SAAS ARCH</h3>
                    <p class="text-stone-400 text-sm mb-8 flex-grow leading-relaxed">Clase técnica profunda sobre arquitectura escalable para SaaS. Seguridad, pagos y gestión de usuarios.</p>
                    <a href="landing-workshop-saas.php" class="w-full py-4 bg-white/5 border border-white/10 rounded-xl text-center text-xs font-black uppercase tracking-widest hover:bg-accent hover:text-black hover:border-accent transition-all">ACCEDER AHORA</a>
                </div>

            </div>
        </main>

        <footer class="mt-32 text-center">
            <p class="text-stone-600 text-xs font-mono tracking-tighter uppercase">© 2026 SALVATECHNOLOGY // SERVICIOS EXCLUSIVOS </p>
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
