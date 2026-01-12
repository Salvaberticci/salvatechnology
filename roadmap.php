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
            background: conic-gradient(
                transparent, 
                transparent, 
                transparent, 
                #ff8c00
            );
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
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
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
                <div class="relative z-10 mb-20 flex flex-col md:flex-row items-center justify-between group roadmap-step">
                    <div class="md:w-5/12 mb-8 md:mb-0 order-2 md:order-1">
                        <div onclick="openModal(1)" class="glass-panel overflow-hidden rounded-2xl border border-accent/30 hover:border-accent transition-all cursor-pointer">
                            <div class="h-32 overflow-hidden">
                                <img src="img/roadmap_frontend.png" alt="Frontend Fundamentals" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                            </div>
                            <div class="p-6">
                                <h3 class="text-xl font-bold text-accent mb-2">01. FUNDAMENTOS MODERNOS</h3>
                                <p class="text-stone-400 text-sm mb-4">HTML5 y CSS3 enfocado en layouts fluidos y diseño de interfaz (UI).</p>
                                <div class="flex flex-wrap gap-2">
                                    <span class="px-3 py-1 bg-accent/10 text-accent text-[10px] font-bold rounded border border-accent/20 group-hover:bg-accent group-hover:text-black transition-all uppercase">VER RECURSOS</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="w-8 h-8 bg-accent rounded-full glow-dot z-20 order-1 md:order-2"></div>
                    <div class="md:w-5/12 order-3"></div>
                </div>

                <!-- Step 2 -->
                <div class="relative z-10 mb-20 flex flex-col md:flex-row items-center justify-between group roadmap-step">
                    <div class="md:w-5/12 order-3 md:order-1"></div>
                    <div class="w-8 h-8 bg-accent rounded-full glow-dot z-20 order-1 md:order-2"></div>
                    <div class="md:w-5/12 mb-8 md:mb-0 order-2 md:order-3">
                        <div onclick="openModal(2)" class="glass-panel overflow-hidden rounded-2xl border border-accent/30 hover:border-accent transition-all text-right md:text-left cursor-pointer">
                            <div class="h-32 overflow-hidden">
                                <img src="img/roadmap_logic.png" alt="Javascript Logic" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                            </div>
                            <div class="p-6">
                                <h3 class="text-xl font-bold text-accent mb-2">02. LÓGICA & JAVASCRIPT</h3>
                                <p class="text-stone-400 text-sm mb-4">Aprende a pensar como programador. Variables, funciones y manipulación del DOM.</p>
                                <div class="flex flex-wrap gap-2 justify-end md:justify-start">
                                    <span class="px-3 py-1 bg-accent/10 text-accent text-[10px] font-bold rounded border border-accent/20 group-hover:bg-accent group-hover:text-black transition-all uppercase">VER RECURSOS</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Step 3 (Highlight: IA) -->
                <div class="relative z-10 mb-20 flex flex-col md:flex-row items-center justify-between group roadmap-step">
                    <div class="md:w-5/12 mb-8 md:mb-0 order-2 md:order-1">
                        <div onclick="openModal(3)" class="glass-panel overflow-hidden rounded-2xl border-2 border-accent shadow-[0_0_30px_rgba(255,140,0,0.2)] cursor-pointer">
                            <div class="h-32 overflow-hidden">
                                <img src="img/project_isp.png" alt="AI Co-coding" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                            </div>
                            <div class="p-6">
                                <div class="inline-block px-2 py-1 bg-accent text-black text-[10px] font-black rounded mb-2">RECOMENDADO</div>
                                <h3 class="text-2xl font-bold text-accent mb-2">03. CO-PROGRAMACIÓN IA</h3>
                                <p class="text-stone-300 text-sm mb-4">Prompt Engineering aplicado al código. Deja que la IA multiplique tu velocidad x10.</p>
                                <div class="flex flex-wrap gap-2">
                                    <span class="px-4 py-2 bg-accent text-black text-xs font-bold rounded shadow-lg group-hover:scale-105 transition-transform uppercase">EXPLORAR PASO</span>
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
                <div class="relative z-10 mb-20 flex flex-col md:flex-row items-center justify-between group roadmap-step">
                    <div class="md:w-5/12 order-3 md:order-1"></div>
                    <div class="w-8 h-8 bg-accent rounded-full glow-dot z-20 order-1 md:order-2"></div>
                    <div class="md:w-5/12 mb-8 md:mb-0 order-2 md:order-3">
                        <div onclick="openModal(4)" class="glass-panel overflow-hidden rounded-2xl border border-accent/30 hover:border-accent transition-all cursor-pointer">
                            <div class="h-32 overflow-hidden">
                                <img src="img/project_retail.png" alt="Backend and Data" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                            </div>
                            <div class="p-6">
                                <h3 class="text-xl font-bold text-accent mb-2">04. BACKEND & DATOS</h3>
                                <p class="text-stone-400 text-sm mb-4">Sistemas CRUD, PHP y bases de datos relacionales para manejar información real.</p>
                                <div class="flex flex-wrap gap-2">
                                    <span class="px-3 py-1 bg-white/5 text-stone-300 text-[10px] font-bold rounded border border-white/10 group-hover:bg-accent group-hover:text-black transition-all uppercase">RECURSOS</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Step 5 -->
                <div class="relative z-10 flex flex-col md:flex-row items-center justify-between group roadmap-step">
                    <div class="md:w-5/12 mb-8 md:mb-0 order-2 md:order-1">
                        <div onclick="openModal(5)" class="glass-panel overflow-hidden rounded-2xl border border-accent/30 hover:border-accent transition-all cursor-pointer">
                            <div class="h-32 overflow-hidden">
                                <img src="img/project_food.png" alt="Professional Launch" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                            </div>
                            <div class="p-6">
                                <h3 class="text-xl font-bold text-accent mb-2">05. LANZAMIENTO PRO</h3>
                                <p class="text-stone-400 text-sm mb-4">Marca personal y monetización. De desarrollador a dueño de producto SaaS.</p>
                                <div class="flex flex-wrap gap-2">
                                    <span class="px-3 py-1 bg-white/5 text-stone-300 text-[10px] font-bold rounded border border-white/10 group-hover:bg-accent group-hover:text-black transition-all uppercase">ESTRATEGIA</span>
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

    <!-- Interactive Resource Modal -->
    <div id="resource-modal" class="fixed inset-0 flex items-center justify-center pointer-events-none p-4">
        <div class="modal-overlay absolute inset-0 cursor-pointer" onclick="closeModal()"></div>
        <div class="modal-content-glass max-w-2xl w-full rounded-2xl overflow-hidden relative pointer-events-auto">
            <div class="modal-inner rounded-2xl overflow-hidden">
                <!-- Modal Header -->
                <div class="p-6 border-b border-white/10 flex justify-between items-center bg-accent/5">
                    <div class="flex items-center gap-4">
                        <div id="modal-icon" class="w-12 h-12 bg-accent/20 rounded-xl flex items-center justify-center text-accent">
                            <!-- Dynamic Icon -->
                        </div>
                        <div>
                            <h2 id="modal-title" class="text-2xl font-black italic text-white uppercase tracking-tighter">PASO 01</h2>
                            <p id="modal-subtitle" class="text-accent text-xs font-mono uppercase">Mini Ruta de Recursos</p>
                        </div>
                    </div>
                    <button onclick="closeModal()" class="text-stone-500 hover:text-white transition-colors">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <!-- Modal Body -->
                <div class="p-8 max-h-[70vh] overflow-y-auto">
                    <p id="modal-description" class="text-stone-300 font-mono text-sm mb-8 leading-relaxed">
                        <!-- Dynamic Description -->
                    </p>

                    <div class="grid gap-6">
                        <!-- PDF Downloads -->
                        <div>
                            <h4 class="text-white text-[10px] font-black uppercase tracking-widest mb-4 flex items-center gap-2">
                                <span class="w-2 h-2 bg-accent rounded-full"></span> MATERIAL DE ESTUDIO (PDF)
                            </h4>
                            <div id="pdf-list" class="grid gap-3">
                                <!-- Dynamic Content -->
                            </div>
                        </div>

                        <!-- Video / Courses -->
                        <div>
                            <h4 class="text-white text-[10px] font-black uppercase tracking-widest mb-4 flex items-center gap-2">
                                <span class="w-2 h-2 bg-accent rounded-full"></span> VIDEOS & CURSOS
                            </h4>
                            <div id="video-list" class="grid gap-3">
                                <!-- Dynamic Content -->
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="p-6 bg-white/5 border-t border-white/10 text-center">
                    <p class="text-stone-500 text-[10px] font-mono tracking-tighter uppercase">Recursos actualizados semanalmente // Salvatechnology</p>
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
                title: "FUNDAMENTOS MODERNOS",
                desc: "Domina la base de la web. Aquí aprenderás a estructurar sitios con HTML5 semántico y a darles vida con CSS3 avanzado y Flexbox/Grid.",
                icon: '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 21l3-1 3 1-.75-4M12 3v17m0-17l-3 3m3-3l3 3"></path></svg>',
                pdfs: [
                    { name: "Guía de Etiquetas Semánticas", link: "#" },
                    { name: "Cheat Sheet: CSS Grid & Flexbox", link: "#" }
                ],
                videos: [
                    { name: "Masterclass: HTML/CSS desde Cero", link: "#", type: "VIDEO" },
                    { name: "Curso: Maquetación Futurista", link: "#", type: "CURSO" }
                ]
            },
            2: {
                title: "LÓGICA & JAVASCRIPT",
                desc: "Entra en el mundo de la programación real. Domina la manipulación del DOM, eventos y la lógica asíncrona para crear apps interactivas.",
                icon: '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path></svg>',
                pdfs: [
                    { name: "JavaScript Moderno ES6+", link: "#" },
                    { name: "Algoritmos para Principiantes", link: "#" }
                ],
                videos: [
                    { name: "Lógica de Programación con JS", link: "#", type: "VIDEO" },
                    { name: "Workshop: Apps con JS Vanilla", link: "#", type: "WORKSHOP" }
                ]
            },
            3: {
                title: "CO-PROGRAMACIÓN IA",
                desc: "Aprende a usar la IA como tu Senior Developer. Domina herramientas como Cursor, Claude y ChatGPT para multiplicar tu productividad.",
                icon: '<svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2L4.5 20.29l.71.71L12 18l6.79 3 .71-.71L12 2z"/></svg>',
                pdfs: [
                    { name: "Prompt Book: Coding with Claude", link: "#" }
                ],
                videos: [
                    { name: "La Guía Maestra (Recurso Central)", link: "guia.php", type: "GUÍA" },
                    { name: "Deep Dive: Cursor vs VS Code", link: "#", type: "VIDEO" }
                ]
            },
            4: {
                title: "BACKEND & DATOS",
                desc: "Construye sistemas que perduran. Aprende a manejar servidores, APIs, PHP y SQL para crear aplicaciones dinámicas con bases de datos.",
                icon: '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.58 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.58 4 8 4s8-1.79 8-4M4 7c0-2.21 3.58-4 8-4s8 1.79 8 4m0 5c0 2.21-3.58 4-8 4s-8-1.79-8-4"></path></svg>',
                pdfs: [
                    { name: "Diseño de Bases de Datos", link: "#" }
                ],
                videos: [
                    { name: "Curso Express: PHP & MySQL", link: "#", type: "CURSO" }
                ]
            },
            5: {
                title: "LANZAMIENTO PRO",
                desc: "Transforma tu conocimiento en dinero. Domina tu marca personal, crea un portfolio magnético y lanza tus propios productos SaaS.",
                icon: '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>',
                pdfs: [
                    { name: "Portfolio Checklist", link: "#" },
                    { name: "Guía de Monetización", link: "#" }
                ],
                videos: [
                    { name: "Cómo conseguir clientes IT", link: "#", type: "MASTERCLASS" }
                ]
            }
        };

        function openModal(stepId) {
            const data = roadmapData[stepId];
            if (!data) return;

            document.getElementById('modal-icon').innerHTML = data.icon;
            document.getElementById('modal-title').innerText = `0${stepId}. ${data.title}`;
            document.getElementById('modal-description').innerText = data.desc;

            const pdfList = document.getElementById('pdf-list');
            pdfList.innerHTML = data.pdfs.map(pdf => `
                <a href="${pdf.link}" target="_blank" class="flex items-center justify-between p-3 bg-white/5 border border-white/10 rounded-lg hover:border-accent hover:bg-accent/5 transition-all group">
                    <span class="text-stone-300 text-xs font-mono group-hover:text-white">${pdf.name}</span>
                    <svg class="w-4 h-4 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                </a>
            `).join('');

            const videoList = document.getElementById('video-list');
            videoList.innerHTML = data.videos.map(video => `
                <a href="${video.link}" class="flex items-center justify-between p-3 bg-white/5 border border-white/10 rounded-lg hover:border-accent hover:bg-accent/5 transition-all group">
                    <div class="flex items-center gap-2">
                        <span class="text-[8px] px-1 bg-accent/20 text-accent font-black rounded uppercase">${video.type}</span>
                        <span class="text-stone-300 text-xs font-mono group-hover:text-white">${video.name}</span>
                    </div>
                    <svg class="w-4 h-4 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </a>
            `).join('');

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
