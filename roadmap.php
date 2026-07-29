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

        /* Modal Styles */
        #resource-modal {
            display: none;
            opacity: 0;
            z-index: 100;
        }

        .modal-overlay {
            background: rgba(0, 0, 0, 0.95);
            backdrop-filter: blur(15px);
        }

        .modal-content-glass {
            background: rgba(10, 10, 10, 0.95);
            border: 1px solid rgba(255, 140, 0, 0.4);
            box-shadow: 0 0 100px rgba(0, 0, 0, 1);
            position: relative;
            overflow: hidden;
        }

        /* Neon Border Animation */
        .modal-content-glass::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: conic-gradient(transparent,
                    transparent,
                    transparent,
                    #ff8c00);
            animation: rotate-border 4s linear infinite;
        }

        .modal-inner {
            position: relative;
            background: rgba(10, 10, 10, 0.98);
            margin: 2px;
            border-radius: 14px;
            z-index: 10;
        }

        @keyframes rotate-border {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        /* Page Fade In */
        .page-fade-in {
            opacity: 0;
            transform: translateY(10px);
            transition: opacity 0.8s ease-out, transform 0.8s ease-out;
        }

        .page-fade-in.active {
            opacity: 1;
            transform: translateY(0);
        }

        /* Mini Roadmap Styles */
        .mini-roadmap-line {
            position: absolute;
            left: 19px;
            top: 2rem;
            bottom: 2rem;
            width: 2px;
            background: linear-gradient(180deg, #ff8c00 0%, rgba(255, 140, 0, 0.1) 100%);
            z-index: 0;
        }

        .mini-roadmap-item {
            position: relative;
            padding-left: 3.5rem;
            margin-bottom: 1.5rem;
            opacity: 0;
            animation: slideInLeft 0.5s ease-out forwards;
        }

        .mini-roadmap-dot {
            position: absolute;
            left: 10px;
            top: 50%;
            width: 20px;
            height: 20px;
            background: #ff8c00;
            border: 4px solid #000;
            border-radius: 50%;
            z-index: 10;
            box-shadow: 0 0 10px rgba(255, 140, 0, 0.5);
            transform: translateY(-50%);
        }

        .mini-roadmap-card {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.05);
            border-radius: 0.75rem;
            transition: all 0.3s ease;
        }

        .mini-roadmap-card:hover {
            border-color: #ff8c00;
            background: rgba(255, 140, 0, 0.05);
            transform: translateX(5px);
        }

        .mini-material-link {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem;
            margin-top: 0.5rem;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 0.5rem;
            font-size: 0.75rem;
            color: #a8a29e;
            transition: all 0.2s;
        }

        .mini-material-link:hover {
            background: rgba(255, 140, 0, 0.1);
            border-color: #ff8c00;
            color: white;
        }

        @keyframes slideInLeft {
            from {
                opacity: 0;
                transform: translateX(-20px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
    </style>
</head>

<body class="text-white bg-black min-h-screen">

    <!-- Background Animation -->
    <canvas id="bg"></canvas>

    <!-- Main Content Wrapper -->
    <div id="page-content" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 relative z-10 page-fade-in">
        <!-- Site Header -->
        <header class="mb-16 flex justify-between items-center">
            <div class="flex items-center gap-6">
                <div class="logo">
                    <a href="<?= BASE_URL ?>">
                        <img src="img/logo.png" alt="Salva Technology Logo"
                            class="h-12 hover:scale-105 transition-transform">
                    </a>
                </div>
                <a href="<?= BASE_URL ?>academia"
                    class="text-accent hover:text-white transition-colors flex items-center gap-2 font-mono text-xs group">
                    <svg class="w-4 h-4 transform group-hover:-translate-x-1 transition-transform" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7">
                        </path>
                    </svg>
                    VOLVER A ACADEMIA
                </a>
            </div>
            <div class="font-mono text-accent text-sm tracking-widest hidden md:block">
                LEARNING PATH </div>
        </header>

        <main class="max-w-4xl mx-auto">
            <section class="text-center mb-24">
                <h1 class="text-4xl md:text-6xl font-black italic futuristic-title mb-6 leading-tight">
                    RUTA <span class="text-white">SALVATECHNOLOGY</span>
                </h1>
                <p class="text-lg text-stone-400 font-mono">Domina el desarrollo web impulsado por la <span
                        class="text-accent">IA</span>.</p>
            </section>

            <!-- Roadmap Container -->
            <div class="relative">
                <!-- Central vertical line -->
                <div class="absolute left-4 md:left-1/2 transform md:-translate-x-1/2 w-1 h-full roadmap-line z-0">
                </div>

                <!-- Step 1 -->
                <div
                    class="relative z-10 mb-20 flex flex-col md:flex-row items-center justify-between group roadmap-step">
                    <div class="md:w-5/12 mb-8 md:mb-0 order-2 md:order-1">
                        <div onclick="openModal(1)"
                            class="glass-panel overflow-hidden rounded-2xl border border-accent/30 hover:border-accent transition-all cursor-pointer">
                            <div class="h-32 overflow-hidden">
                                <img src="img/roadmap_frontend.png" alt="Frontend Fundamentals"
                                    class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                            </div>
                            <div class="p-6">
                                <h3 class="text-xl font-bold text-accent mb-2">MÓDULO 1: FRONTEND</h3>
                                <p class="text-stone-400 text-sm mb-4">La Interfaz del Sistema. Fundamentos universales, layouts y lógica de cliente.</p>
                                <div class="flex flex-wrap gap-2">
                                    <span
                                        class="px-3 py-1 bg-accent/10 text-accent text-[10px] font-bold rounded border border-accent/20 group-hover:bg-accent group-hover:text-black transition-all uppercase">VER CONTENIDO</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="w-8 h-8 bg-accent rounded-full glow-dot z-20 order-1 md:order-2"></div>
                    <div class="md:w-5/12 order-3"></div>
                </div>

                <!-- Step 2 -->
                <div
                    class="relative z-10 mb-20 flex flex-col md:flex-row items-center justify-between group roadmap-step">
                    <div class="md:w-5/12 order-3 md:order-1"></div>
                    <div class="w-8 h-8 bg-accent rounded-full glow-dot z-20 order-1 md:order-2"></div>
                    <div class="md:w-5/12 mb-8 md:mb-0 order-2 md:order-3">
                        <div onclick="openModal(2)"
                            class="glass-panel overflow-hidden rounded-2xl border border-accent/30 hover:border-accent transition-all text-right md:text-left cursor-pointer">
                            <div class="h-32 overflow-hidden">
                                <img src="img/roadmap_logic.png" alt="Javascript Logic"
                                    class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                            </div>
                            <div class="p-6">
                                <h3 class="text-xl font-bold text-accent mb-2">MÓDULO 2: BACKEND</h3>
                                <p class="text-stone-400 text-sm mb-4">La Lógica del Negocio. Arquitectura de APIs, seguridad y gestión de archivos.</p>
                                <div class="flex flex-wrap gap-2 justify-end md:justify-start">
                                    <span
                                        class="px-3 py-1 bg-accent/10 text-accent text-[10px] font-bold rounded border border-accent/20 group-hover:bg-accent group-hover:text-black transition-all uppercase">VER CONTENIDO</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Step 3 (Highlight: IA) -->
                <div
                    class="relative z-10 mb-20 flex flex-col md:flex-row items-center justify-between group roadmap-step">
                    <div class="md:w-5/12 mb-8 md:mb-0 order-2 md:order-1">
                        <div onclick="openModal(3)"
                            class="glass-panel overflow-hidden rounded-2xl border-2 border-accent shadow-[0_0_30px_rgba(255,140,0,0.2)] cursor-pointer">
                            <div class="h-32 overflow-hidden">
                                <img src="img/project_isp.png" alt="AI Co-coding"
                                    class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                            </div>
                            <div class="p-6">
                                <div
                                    class="inline-block px-2 py-1 bg-accent text-black text-[10px] font-black rounded mb-2">FUNDAMENTAL</div>
                                <h3 class="text-2xl font-bold text-accent mb-2">MÓDULO 3: PERSISTENCIA DE DATOS</h3>
                                <p class="text-stone-300 text-sm mb-4">Bases de Datos. Modelado, normalización, SQL, NoSQL y Caché.</p>
                                <div class="flex flex-wrap gap-2">
                                    <span
                                        class="px-4 py-2 bg-accent text-black text-xs font-bold rounded shadow-lg group-hover:scale-105 transition-transform uppercase">EXPLORAR MÓDULO</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div
                        class="w-12 h-12 bg-white rounded-full flex items-center justify-center glow-dot border-4 border-accent z-20 order-1 md:order-2">
                        <svg class="w-6 h-6 text-accent" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2L4.5 20.29l.71.71L12 18l6.79 3 .71-.71L12 2z" />
                        </svg>
                    </div>
                    <div class="md:w-5/12 order-3"></div>
                </div>

                <!-- Step 4 -->
                <div
                    class="relative z-10 mb-20 flex flex-col md:flex-row items-center justify-between group roadmap-step">
                    <div class="md:w-5/12 order-3 md:order-1"></div>
                    <div class="w-8 h-8 bg-accent rounded-full glow-dot z-20 order-1 md:order-2"></div>
                    <div class="md:w-5/12 mb-8 md:mb-0 order-2 md:order-3">
                        <div onclick="openModal(4)"
                            class="glass-panel overflow-hidden rounded-2xl border border-accent/30 hover:border-accent transition-all cursor-pointer">
                            <div class="h-32 overflow-hidden">
                                <img src="img/project_retail.png" alt="Backend and Data"
                                    class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                            </div>
                            <div class="p-6">
                                <h3 class="text-xl font-bold text-accent mb-2">MÓDULO 4: IA APLICADA</h3>
                                <p class="text-stone-400 text-sm mb-4">El Plus Moderno. Consumo de modelos, Prompt Engineering y automatización.</p>
                                <div class="flex flex-wrap gap-2">
                                    <span
                                        class="px-3 py-1 bg-white/5 text-stone-300 text-[10px] font-bold rounded border border-white/10 group-hover:bg-accent group-hover:text-black transition-all uppercase">VER CONTENIDO</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Step 5 -->
                <div class="relative z-10 flex flex-col md:flex-row items-center justify-between group roadmap-step">
                    <div class="md:w-5/12 mb-8 md:mb-0 order-2 md:order-1">
                        <div onclick="openModal(5)"
                            class="glass-panel overflow-hidden rounded-2xl border border-accent/30 hover:border-accent transition-all cursor-pointer">
                            <div class="h-32 overflow-hidden">
                                <img src="img/project_food.png" alt="Professional Launch"
                                    class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                            </div>
                            <div class="p-6">
                                <h3 class="text-xl font-bold text-accent mb-2">MÓDULO 5: INFRAESTRUCTURA</h3>
                                <p class="text-stone-400 text-sm mb-4">Despliegue y Cloud. Docker, CI/CD y servicios PaaS/IaaS.</p>
                                <div class="flex flex-wrap gap-2">
                                    <span
                                        class="px-3 py-1 bg-white/5 text-stone-300 text-[10px] font-bold rounded border border-white/10 group-hover:bg-accent group-hover:text-black transition-all uppercase">VER CONTENIDO</span>
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
            <p class="text-stone-600 text-xs font-mono tracking-tighter uppercase">© 2026 SALVATECHNOLOGY ACADEMY //
                APRENDIZAJE ACELERADO</p>
        </footer>
    </div>

    <!-- Interactive Resource Modal -->
    <div id="resource-modal" class="fixed inset-0 flex items-center justify-center pointer-events-none p-4">
        <div class="modal-overlay absolute inset-0 cursor-pointer" onclick="closeModal()"></div>
        <div class="modal-content-glass max-w-2xl w-full rounded-2xl overflow-hidden relative pointer-events-auto">
            <div class="modal-inner rounded-2xl overflow-hidden">
                <!-- Modal Header -->
                <div class="p-6 border-b border-white/10 flex justify-between items-center bg-accent/5">
                    <div class="flex items-center gap-4">
                        <div id="modal-icon"
                            class="w-12 h-12 bg-accent/20 rounded-xl flex items-center justify-center text-accent">
                            <!-- Dynamic Icon -->
                        </div>
                        <div>
                            <h2 id="modal-title"
                                class="text-2xl font-black italic text-white uppercase tracking-tighter">PASO 01</h2>
                            <p id="modal-subtitle" class="text-accent text-xs font-mono uppercase">Mini Ruta de Recursos
                            </p>
                        </div>
                    </div>
                    <button onclick="closeModal()" class="text-stone-500 hover:text-white transition-colors">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Modal Body -->
                <div class="p-8 max-h-[70vh] overflow-y-auto">
                    <p id="modal-description" class="text-stone-300 font-mono text-sm mb-8 leading-relaxed">
                        <!-- Dynamic Description -->
                    </p>

                    <div class="relative min-h-[100px]">
                        <div class="mini-roadmap-line"></div>
                        <div id="modal-resources-list" class="grid gap-2">
                            <!-- Dynamic Content -->
                        </div>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="p-6 bg-white/5 border-t border-white/10 text-center">
                    <p class="text-stone-500 text-[10px] font-mono tracking-tighter uppercase">Recursos actualizados
                        semanalmente // Salvatechnology</p>
                </div>
            </div>
        </div>
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
        const roadmapData = {
            1: {
                title: "FRONTEND (LA INTERFAZ DEL SISTEMA)",
                desc: "Es la parte visual del software con la que interactúan los usuarios. Incluye todo lo que ves en pantalla: botones, menús, animaciones y la experiencia de usuario (UX/UI).",
                icon: '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 21l3-1 3 1-.75-4M12 3v17m0-17l-3 3m3-3l3 3"></path></svg>',
                modules: [
                    { 
                        title: "Curso de HTML y CSS desde CERO (Completo)", 
                        link: "https://youtu.be/ELSm-G201Ls?si=h8aNJbLFM7mqwdrV", 
                        instructor: "Lucas Dalto (SoyDalto)",
                        description: "Lucas Dalto es un referente de la educación tecnológica. <br><br><strong class='text-white'>Sobre el curso (24h):</strong> Guía definitiva que abarca desde los fundamentos de la Web, HTML5 semántico y CSS3, hasta dominar layouts modernos con Flexbox y Grid. Incluye Responsive Design, Animaciones, metodologías profesionales y proyectos prácticos finales.",
                        materials: [] 
                    },
                    { title: "Layout & Dashboards: CSS moderno (Flexbox/Grid)", link: "#", materials: [] },
                    { title: "Lógica de Cliente: Manipulación de datos real-time", link: "#", materials: [] },
                    { title: "Ruta React: Estándar industrial", link: "#", materials: [] },
                    { title: "Ruta Vue.js: Curva de aprendizaje suave", link: "#", materials: [] },
                    { title: "Ruta Angular: Sistemas de gran escala", link: "#", materials: [] }
                ]
            },
            2: {
                title: "BACKEND (LA LÓGICA DEL NEGOCIO)",
                desc: "Es el cerebro del sistema que opera detrás de escena. Procesa los datos, gestiona la seguridad, conecta con la base de datos y ejecuta las reglas del negocio.",
                icon: '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path></svg>',
                modules: [
                    { title: "Arquitectura de APIs: Servicios RESTful y JSON", link: "#", materials: [] },
                    { title: "Seguridad y Sesiones: JWT, OAuth", link: "#", materials: [] },
                    { title: "Gestión de Archivos: PDF y Excel", link: "#", materials: [] },
                    { title: "Ruta Node.js: Visualización Real-time", link: "#", materials: [] },
                    { title: "Ruta PHP (Laravel): Rapidez y Madurez", link: "#", materials: [] },
                    { title: "Ruta Python (Django/FastAPI): Datos e IA", link: "#", materials: [] },
                    { title: "Ruta Java (Spring Boot): Robustez máxima", link: "#", materials: [] }
                ]
            },
            3: {
                title: "PERSISTENCIA DE DATOS",
                desc: "Es el sistema encargado de almacenar, organizar y recuperar la información de manera estructurada y segura, asegurando que los datos perduren en el tiempo.",
                icon: '<svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2L4.5 20.29l.71.71L12 18l6.79 3 .71-.71L12 2z"/></svg>',
                modules: [
                    { title: "Modelado Entidad-Relación", link: "#", materials: [] },
                    { title: "Normalización de Datos", link: "#", materials: [] },
                    { title: "Relacionales (SQL): PostgreSQL / MySQL", link: "#", materials: [] },
                    { title: "No Relacionales (NoSQL): MongoDB", link: "#", materials: [] },
                    { title: "Caché: Redis para alto tráfico", link: "#", materials: [] }
                ]
            },
            4: {
                title: "IA APLICADA A SISTEMAS",
                desc: "Es la integración de modelos de aprendizaje automático para dotar al software de capacidades cognitivas, como entender lenguaje natural, reconocer imágenes o predecir eventos.",
                icon: '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.58 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.58 4 8 4s8-1.79 8-4M4 7c0-2.21 3.58-4 8-4s8 1.79 8 4m0 5c0 2.21-3.58 4-8 4s-8-1.79-8-4"></path></svg>',
                modules: [
                    { title: "Consumo de Modelos: APIs (Gemini, OpenAI)", link: "#", materials: [] },
                    { title: "Prompt Engineering: Datos Estructurados JSON", link: "#", materials: [] },
                    { title: "Análisis Predictivo: Stock e Historial", link: "#", materials: [] },
                    { title: "Asistentes de Lenguaje Natural (Chat)", link: "#", materials: [] },
                    { title: "Automatización de Datos: Visión Artificial", link: "#", materials: [] }
                ]
            },
            5: {
                title: "INFRAESTRUCTURA Y DESPLIEGUE",
                desc: "Es el conjunto de servidores, redes y entornos donde se ejecuta el software. Permite que tu aplicación esté disponible en internet de forma segura y escalable.",
                icon: '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>',
                modules: [
                    { title: "Contenedores (Docker): Portabilidad", link: "#", materials: [] },
                    { title: "CI/CD: Automatización de Pruebas", link: "#", materials: [] },
                    { title: "PaaS: Vercel / Railway", link: "#", materials: [] },
                    { title: "IaaS: AWS / DigitalOcean", link: "#", materials: [] }
                ]
            }
        };

        function openModal(stepId) {
            const data = roadmapData[stepId];
            if (!data) return;

            document.getElementById('modal-icon').innerHTML = data.icon;
            document.getElementById('modal-title').innerText = `0${stepId}. ${data.title}`;
            document.getElementById('modal-description').innerText = data.desc;

            const listContainer = document.getElementById('modal-resources-list');
            let contentHTML = '';

            if (!data.modules || data.modules.length === 0) {
                contentHTML = '<p class="text-center text-stone-500 text-sm py-4">Próximamente...</p>';
            } else {
                data.modules.forEach((mod, index) => {
                    const animDelay = index * 0.1;

                    let materialsHTML = '';
                    if (mod.materials && mod.materials.length > 0) {
                        materialsHTML = '<div class="mt-3 pt-3 border-t border-white/5">';
                        materialsHTML += '<p class="text-[9px] uppercase text-stone-500 font-bold mb-2">Material Adicional</p>';
                        mod.materials.forEach(mat => {
                            materialsHTML += `
                                <a href="${mat.link}" target="_blank" class="mini-material-link group/link">
                                    <svg class="w-3 h-3 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                    <span class="group-hover/link:underline">${mat.name}</span>
                                </a>
                            `;
                        });
                        materialsHTML += '</div>';
                    }

                    let descriptionHTML = '';
                    if (mod.description) {
                        descriptionHTML = `
                            <div class="mt-2 mb-3 text-xs text-stone-400 font-mono leading-relaxed border-l-2 border-accent/20 pl-3">
                                ${mod.instructor ? `<strong class="text-accent block text-[10px] uppercase mb-1">Instructor: ${mod.instructor}</strong>` : ''}
                                ${mod.description}
                            </div>
                        `;
                    }

                    contentHTML += `
                        <div class="mini-roadmap-item" style="animation-delay: ${animDelay}s">
                            <div class="mini-roadmap-dot"></div>
                            <div class="mini-roadmap-card p-4 group">
                                <a href="${mod.link}" target="_blank" class="block">
                                    <div class="flex items-center justify-between mb-2">
                                        <div class="flex items-center gap-3">
                                            <div class="p-2 bg-white/5 rounded-lg text-accent group-hover:scale-110 transition-transform">
                                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M19.615 3.184c-3.604-.246-11.631-.245-15.23 0-3.897.266-4.356 2.62-4.385 8.816.029 6.185.484 8.549 4.385 8.816 3.6.245 11.626.246 15.23 0 3.897-.266 4.356-2.62 4.385-8.816-.029-6.185-.484-8.549-4.385-8.816zm-10.615 12.816v-8l8 3.993-8 4.007z"/></svg>
                                            </div>
                                            <div>
                                                <span class="text-[10px] font-black uppercase text-accent tracking-widest mb-1 block opacity-70">VIDEO</span>
                                                <h4 class="text-sm font-mono text-stone-300 group-hover:text-white transition-colors">${mod.title}</h4>
                                            </div>
                                        </div>
                                        <svg class="w-4 h-4 text-stone-600 group-hover:text-accent transition-colors transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                    </div>
                                </a>
                                ${descriptionHTML}
                                ${materialsHTML}
                            </div>
                        </div>
                    `;
                });
            }

            listContainer.innerHTML = contentHTML;

            const modal = document.getElementById('resource-modal');
            modal.style.display = 'flex';
            gsap.to(modal, { opacity: 1, duration: 0.4, ease: "power2.out" });
            gsap.from(".modal-content-glass", { y: 20, scale: 0.95, duration: 0.4, ease: "back.out(1.7)" });
        }

        function closeModal() {
            const modal = document.getElementById('resource-modal');
            gsap.to(modal, {
                opacity: 0,
                duration: 0.3,
                ease: "power2.in",
                onComplete: () => {
                    modal.style.display = 'none';
                }
            });
        }

        gsap.registerPlugin(ScrollTrigger);

        // Animation for steps
        gsap.utils.toArray('.roadmap-step').forEach((step, i) => {
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