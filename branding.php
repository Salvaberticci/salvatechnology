<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Marca Personal | Salvatechnology</title>
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
                    <a href="/salvatechnology/">
                        <img src="img/logo.png" alt="Salva Technology Logo" class="h-12 hover:scale-105 transition-transform">
                    </a>
                </div>
                <a href="/salvatechnology/academia" class="text-accent hover:text-white transition-colors flex items-center gap-2 font-mono text-xs group">
                    <svg class="w-4 h-4 transform group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                    VOLVER A ACADEMIA
                </a>
            </div>
            <div class="font-mono text-accent text-sm tracking-widest hidden md:block">
                BRANDING 
            </div>
        </header>

        <main class="max-w-4xl mx-auto">
            <section class="text-center mb-24">
                <h1 class="text-4xl md:text-6xl font-black italic futuristic-title mb-6 leading-tight">
                    MARCA <span class="text-white">PERSONAL</span>
                </h1>
                <p class="text-lg text-stone-400 font-mono">Construye autoridad y domina tu <span class="text-accent">nicho</span>.</p>
            </section>

            <!-- Roadmap Container -->
            <div class="relative">
                <div class="absolute left-4 md:left-1/2 transform md:-translate-x-1/2 w-1 h-full roadmap-line z-0"></div>

                <!-- Step 1 -->
                <div class="relative z-10 mb-20 flex flex-col md:flex-row items-center justify-between group roadmap-step">
                    <div class="md:w-5/12 mb-8 md:mb-0 order-2 md:order-1">
                        <div onclick="openModal(1)" class="glass-panel overflow-hidden rounded-2xl border border-accent/30 hover:border-accent transition-all cursor-pointer">
                            <div class="h-32 overflow-hidden">
                                <img src="img/project_retail.png" alt="Identidad" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                            </div>
                            <div class="p-6">
                                <h3 class="text-xl font-bold text-accent mb-2">01. CONCEPTO E IDENTIDAD</h3>
                                <p class="text-stone-400 text-sm mb-4">Define tu propuesta de valor única y tu estética visual de alto impacto.</p>
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
                                <img src="img/roadmap_logic.png" alt="Contenido" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                            </div>
                            <div class="p-6">
                                <h3 class="text-xl font-bold text-accent mb-2">02. ESTRATEGIA DE CONTENIDO</h3>
                                <p class="text-stone-400 text-sm mb-4">Escala tu mensaje. Pilares de contenido y sistema de publicación multicanal.</p>
                                <div class="flex flex-wrap gap-2 justify-end md:justify-start">
                                    <span class="px-3 py-1 bg-accent/10 text-accent text-[10px] font-bold rounded border border-accent/20 group-hover:bg-accent group-hover:text-black transition-all uppercase">VER RECURSOS</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Step 3 -->
                <div class="relative z-10 mb-20 flex flex-col md:flex-row items-center justify-between group roadmap-step">
                    <div class="md:w-5/12 mb-8 md:mb-0 order-2 md:order-1">
                        <div onclick="openModal(3)" class="glass-panel overflow-hidden rounded-2xl border-2 border-accent shadow-[0_0_30px_rgba(255,140,0,0.2)] cursor-pointer">
                            <div class="h-32 overflow-hidden">
                                <img src="img/project_isp.png" alt="Autoridad" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                            </div>
                            <div class="p-6">
                                <h3 class="text-2xl font-bold text-accent mb-2">03. AUTORIDAD & COMUNIDAD</h3>
                                <p class="text-stone-300 text-sm mb-4">Posiciónate como experto. Cómo atraer y retener una audiencia de élite.</p>
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
                                <img src="img/project_food.png" alt="Embudos" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                            </div>
                            <div class="p-6">
                                <h3 class="text-xl font-bold text-accent mb-2">04. EMBUDOS DE CONVERSIÓN</h3>
                                <p class="text-stone-400 text-sm mb-4">De seguidores a clientes. Automatización de ventas y captación de leads.</p>
                                <div class="flex flex-wrap gap-2 justify-end md:justify-start">
                                    <span class="px-3 py-1 bg-accent/10 text-accent text-[10px] font-bold rounded border border-accent/20 group-hover:bg-accent group-hover:text-black transition-all uppercase">RECURSOS</span>
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
                                <img src="img/roadmap_frontend.png" alt="Escalabilidad" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                            </div>
                            <div class="p-6">
                                <h3 class="text-xl font-bold text-accent mb-2">05. ESCALABILIDAD & OFERTA</h3>
                                <p class="text-stone-400 text-sm mb-4">Lanza tu High-Ticket o SaaS. Optimiza tus márgenes y escala tu impacto global.</p>
                                <div class="flex flex-wrap gap-2">
                                    <span class="px-3 py-1 bg-accent/10 text-accent text-[10px] font-bold rounded border border-accent/20 group-hover:bg-accent group-hover:text-black transition-all uppercase">ESTRATEGIA</span>
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
            <p class="text-stone-600 text-xs font-mono tracking-tighter uppercase">© 2026 SALVATECHNOLOGY ACADEMY // BRANDING PROFESIONAL</p>
        </footer>
    </div>

    <!-- Interactive Resource Modal -->
    <div id="resource-modal" class="fixed inset-0 flex items-center justify-center pointer-events-none p-4">
        <div class="modal-overlay absolute inset-0 cursor-pointer" onclick="closeModal()"></div>
        <div class="modal-content-glass max-w-2xl w-full rounded-2xl overflow-hidden relative pointer-events-auto">
            <div class="modal-inner rounded-2xl overflow-hidden">
                <div class="p-6 border-b border-white/10 flex justify-between items-center bg-accent/5">
                    <div class="flex items-center gap-4">
                        <div id="modal-icon" class="w-12 h-12 bg-accent/20 rounded-xl flex items-center justify-center text-accent"></div>
                        <div>
                            <h2 id="modal-title" class="text-2xl font-black italic text-white uppercase tracking-tighter"></h2>
                            <p id="modal-subtitle" class="text-accent text-xs font-mono uppercase">Mini Ruta de Recursos</p>
                        </div>
                    </div>
                    <button onclick="closeModal()" class="text-stone-500 hover:text-white transition-colors">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <div class="p-8 max-h-[70vh] overflow-y-auto">
                    <p id="modal-description" class="text-stone-300 font-mono text-sm mb-8 leading-relaxed"></p>

                    <div class="grid gap-6">
                        <div>
                            <h4 class="text-white text-[10px] font-black uppercase tracking-widest mb-4 flex items-center gap-2">
                                <span class="w-2 h-2 bg-accent rounded-full"></span> MATERIAL DE APOYO (PDF)
                            </h4>
                            <div id="pdf-list" class="grid gap-3"></div>
                        </div>
                        <div>
                            <h4 class="text-white text-[10px] font-black uppercase tracking-widest mb-4 flex items-center gap-2">
                                <span class="w-2 h-2 bg-accent rounded-full"></span> ESTRATEGIA & VIDEOS
                            </h4>
                            <div id="video-list" class="grid gap-3"></div>
                        </div>
                    </div>
                </div>

                <div class="p-6 bg-white/5 border-t border-white/10 text-center">
                    <p class="text-stone-500 text-[10px] font-mono tracking-tighter uppercase">Branding & Autoridad // Salvatechnology</p>
                </div>
            </div>
        </div>
    </div>

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
                title: "CONCEPTO E IDENTIDAD",
                desc: "Crea las bases de tu marca. Define tu 'Unique Selling Proposition' y diseña una estética que proyecte profesionalismo y vanguardia.",
                icon: '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"></path></svg>',
                pdfs: [{ name: "Workshop: Arquetipos de Marca", link: "#" }, { name: "Manual de Estética Visual", link: "#" }],
                videos: [{ name: "Cómo definir tu nicho IT", link: "#", type: "VIDEO" }]
            },
            2: {
                title: "ESTRATEGIA DE CONTENIDO",
                desc: "No publiques por publicar. Aprende a crear un sistema de contenido que trabaje para ti, atrayendo prospectos cualificados día tras día.",
                icon: '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10l4 4v10a2 2 0 01-2 2z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 3v5h5"></path></svg>',
                pdfs: [{ name: "Calendario de Contenidos 2026", link: "#" }],
                videos: [{ name: "Metodología Content Matrix", link: "#", type: "VIDEO" }, { name: "IA para crear contenido rápido", link: "#", type: "WORKSHOP" }]
            },
            3: {
                title: "AUTORIDAD & COMUNIDAD",
                desc: "Conviértete en el referente. Estrategias de networking y construcción de una comunidad fiel que confíe en tu criterio técnico.",
                icon: '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>',
                pdfs: [{ name: "Scripts de Networking Pro", link: "#" }],
                videos: [{ name: "Webinar: Autoridad en LinkedIn", link: "#", type: "WEBINAR" }]
            },
            4: {
                title: "EMBUDOS DE CONVERSIÓN",
                desc: "Convierte curiosos en clientes. Diseña la experiencia de usuario que guía a tu audiencia desde el descubrimiento hasta la compra.",
                icon: '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>',
                pdfs: [{ name: "Blueprint: Embudo de Servicios", link: "#" }],
                videos: [{ name: "Masterclass: Sales Triggers", link: "#", type: "VIDEO" }]
            },
            5: {
                title: "ESCALABILIDAD & OFERTA",
                desc: "Optimiza tu modelo de negocio. Crea ofertas irresistibles de alto valor y utiliza sistemas para escalar tus ingresos sin sacrificar tu tiempo.",
                icon: '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>',
                pdfs: [{ name: "Guía: Ofertas High-Ticket", link: "#" }],
                videos: [{ name: "Escalando tu Agencia/SaaS", link: "#", type: "VIDEO" }]
            }
        };

        function openModal(stepId) {
            const data = roadmapData[stepId];
            if (!data) return;
            document.getElementById('modal-icon').innerHTML = data.icon;
            document.getElementById('modal-title').innerText = `0${stepId}. ${data.title}`;
            document.getElementById('modal-description').innerText = data.desc;
            document.getElementById('pdf-list').innerHTML = data.pdfs.map(pdf => `<a href="${pdf.link}" class="flex items-center justify-between p-3 bg-white/5 border border-white/10 rounded-lg hover:border-accent hover:bg-accent/5 transition-all group"><span class="text-stone-300 text-xs font-mono group-hover:text-white">${pdf.name}</span><svg class="w-4 h-4 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg></a>`).join('');
            document.getElementById('video-list').innerHTML = data.videos.map(video => `<a href="${video.link}" class="flex items-center justify-between p-3 bg-white/5 border border-white/10 rounded-lg hover:border-accent hover:bg-accent/5 transition-all group"><div class="flex items-center gap-2"><span class="text-[8px] px-1 bg-accent/20 text-accent font-black rounded uppercase">${video.type}</span><span class="text-stone-300 text-xs font-mono group-hover:text-white">${video.name}</span></div><svg class="w-4 h-4 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg></a>`).join('');
            const modal = document.getElementById('resource-modal');
            modal.style.display = 'flex';
            gsap.to(modal, { opacity: 1, duration: 0.4 });
            gsap.from(".modal-content-glass", { y: 20, scale: 0.95, duration: 0.4, ease: "back.out(1.7)" });
        }

        function closeModal() {
            const modal = document.getElementById('resource-modal');
            gsap.to(modal, { opacity: 0, duration: 0.3, onComplete: () => { modal.style.display = 'none'; } });
        }

        gsap.utils.toArray('.roadmap-step').forEach((step) => {
            gsap.from(step, { scrollTrigger: { trigger: step, start: "top 85%", toggleActions: "play none none reverse" }, y: 50, opacity: 0, duration: 0.8 });
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
