<?php require_once __DIR__ . '/config/app.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Guía Maestra: Sistemas Web | Salvatechnology</title>
    <base href="<?= BASE_URL ?>">
    <link rel="stylesheet" href="css/style.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'accent': '#ff8c00',
                        'dark-bg': '#000000',
                        'stone-900': '#1c1917',
                        'stone-800': '#292524',
                    }
                }
            }
        }
    </script>
    <link rel="stylesheet" href="css/guia.css">
    <script>window.hideModel = true;</script>
</head>
<body class="text-white bg-black">

    <!-- Background Animation -->
    <canvas id="bg"></canvas>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Site Header -->
        <header class="mb-8">
            <div class="logo">
                <a href="<?= BASE_URL ?>">
                    <img src="img/logo.png" alt="Salva Technology Logo" class="h-12">
                </a>
            </div>
        </header>

        <!-- Guide Content -->
        <div>
            
            <!-- Navigation Inside Content -->
            <nav class="sticky top-0 z-50 glass-panel p-4 mb-4 rounded-xl flex justify-between items-center">
                <div class="flex items-center gap-6">
                    <a href="<?= BASE_URL ?>academia" class="text-accent hover:text-white transition-colors flex items-center gap-2 font-mono text-sm group">
                        <svg class="w-4 h-4 transform group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                        VOLVER
                    </a>
                    <div class="hidden md:flex space-x-6">
                        <button onclick="scrollToGuideSection('hero')" class="text-stone-300 hover:text-accent transition-colors font-mono text-sm">INICIO</button>
                        <button onclick="scrollToGuideSection('cases')" class="text-stone-300 hover:text-accent transition-colors font-mono text-sm">CASOS</button>
                        <button onclick="scrollToGuideSection('pillars')" class="text-stone-300 hover:text-accent transition-colors font-mono text-sm">PILARES</button>
                        <button onclick="scrollToGuideSection('method')" class="text-stone-300 hover:text-accent transition-colors font-mono text-sm">MÉTODO IA</button>
                        <button onclick="scrollToGuideSection('business')" class="text-stone-300 hover:text-accent transition-colors font-mono text-sm">NEGOCIO</button>
                    </div>
                </div>
                <div class="text-accent font-bold">SALVATECHNOLOGY<span class="text-white"></span></div>
            </nav>

            <main>
                <!-- Hero Section -->
                <section id="hero" class="py-12 text-center">
                    <h1 class="text-4xl md:text-6xl font-black italic futuristic-title mb-6 leading-tight">
                        Construye Sistemas Web <br/><span class="text-white">Sin Programar Desde Cero</span>
                    </h1>
                    <p class="mt-4 text-xl text-stone-300 max-w-2xl mx-auto leading-relaxed font-mono">
                        Olvida la teoría aburrida. Co-programa con <span class="text-accent">IA</span> y monetiza tu <span class="text-accent">Marca Personal</span> resolviendo problemas reales.
                    </p>
                    <div class="mt-10 flex justify-center gap-4">
                        <button onclick="scrollToGuideSection('cases')" class="px-8 py-3 bg-accent text-black font-bold rounded-full shadow-lg hover:scale-105 transition-transform">
                            RESULTADOS REALES
                        </button>
                    </div>
                </section>

                <!-- Case Studies -->
                <section id="cases" class="py-16">
                    <div class="text-center mb-12">
                        <h2 class="text-3xl font-bold futuristic-title">RESULTADOS COMPROBADOS</h2>
                    </div>

                    <div class="flex justify-center mb-8 gap-4">
                        <button class="case-tab px-6 py-2 border border-accent/30 text-stone-400 hover:text-accent nav-active" data-target="isp">ISP</button>
                        <button class="case-tab px-6 py-2 border border-accent/30 text-stone-400 hover:text-accent" data-target="retail">RETAIL</button>
                        <button class="case-tab px-6 py-2 border border-accent/30 text-stone-400 hover:text-accent" data-target="icecream">FOOD</button>
                    </div>

                    <div class="glass-panel rounded-2xl p-6 md:p-10">
                        <!-- ISP Case -->
                        <div id="isp-content" class="case-content grid md:grid-cols-2 gap-10 items-center">
                            <div>
                                <h3 class="text-2xl font-bold text-accent mb-4">Caso 1: Telecomunicaciones</h3>
                                <p class="text-stone-300 mb-4 leading-relaxed">
                                    <strong>Del Caos a la Automatización:</strong> Gestión de inventario y nómina. Antes se perdían equipos; ahora todo está centralizado.
                                </p>
                                <ul class="space-y-2 mb-6 text-stone-400 font-mono text-sm">
                                    <li>> Nómina Automatizada</li>
                                    <li>> Control de Routers/Antenas</li>
                                    <li>> Auditoría de Movimientos</li>
                                </ul>
                            </div>
                            <div class="bg-black/50 p-4 rounded-xl border border-accent/20">
                                <h4 class="text-center font-bold text-accent mb-2 text-xs">HORAS DE GESTIÓN SEMANAL</h4>
                                <div class="chart-container">
                                    <canvas id="chartISP"></canvas>
                                </div>
                            </div>
                        </div>

                        <!-- Content for other cases will be handled by JS as before -->
                        <div id="retail-content" class="case-content hidden grid md:grid-cols-2 gap-10 items-center">
                            <div>
                                <h3 class="text-2xl font-bold text-accent mb-4">Caso 2: Ecommerce</h3>
                                <p class="text-stone-300 mb-4 leading-relaxed">
                                    <strong>Fin al Stock Fantasma:</strong> Sincronización en tiempo real y actualización masiva de precios por sistema.
                                </p>
                                <ul class="space-y-2 mb-6 text-stone-400 font-mono text-sm">
                                    <li>> Sincronización Real-Time</li>
                                    <li>> Catálogo Digital</li>
                                    <li>> -80% Tiempo Operativo</li>
                                </ul>
                            </div>
                            <div class="bg-black/50 p-4 rounded-xl border border-accent/20">
                                <h4 class="text-center font-bold text-accent mb-2 text-xs">PRECISIÓN INVENTARIO (%)</h4>
                                <div class="chart-container">
                                    <canvas id="chartRetail"></canvas>
                                </div>
                            </div>
                        </div>

                        <div id="icecream-content" class="case-content hidden grid md:grid-cols-2 gap-10 items-center">
                            <div>
                                <h3 class="text-2xl font-bold text-accent mb-4">Caso 3: Trazabilidad</h3>
                                <p class="text-stone-300 mb-4 leading-relaxed">
                                    <strong>Márgenes Reales:</strong> Cálculo exacto del costo por producto y control de fugas de dinero en insumos.
                                </p>
                                <ul class="space-y-2 mb-6 text-stone-400 font-mono text-sm">
                                    <li>> Cálculo de Márgenes</li>
                                    <li>> Control de Desperdicios</li>
                                    <li>> Decisiones con Datos</li>
                                </ul>
                            </div>
                            <div class="bg-black/50 p-4 rounded-xl border border-accent/20">
                                <h4 class="text-center font-bold text-accent mb-2 text-xs">CONTROL FINANCIERO</h4>
                                <div class="chart-container">
                                    <canvas id="chartIceCream"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Pillars -->
                <section id="pillars" class="py-16">
                    <h2 class="text-3xl font-bold futuristic-title mb-12">FASE 1: LOS PILARES</h2>
                    <div class="grid md:grid-cols-3 gap-8">
                        <div class="glass-panel p-8 rounded-xl card-hover">
                            <div class="text-4xl mb-4 text-accent">01</div>
                            <h3 class="text-xl font-bold mb-2">FRONTEND</h3>
                            <p class="text-stone-400 text-sm mb-4">La cara visible</p>
                            <p class="text-stone-300">Lo que el cliente toca. Tú defines la estructura, la IA escribe el código.</p>
                        </div>
                        <div class="glass-panel p-8 rounded-xl card-hover shadow-[0_0_15px_rgba(255,140,0,0.1)]">
                            <div class="text-4xl mb-4 text-accent">02</div>
                            <h3 class="text-xl font-bold mb-2">BACKEND</h3>
                            <p class="text-stone-400 text-sm mb-4">La lógica pura</p>
                            <p class="text-stone-300">Donde ocurre la magia. Si un empleado marca, el sistema procesa.</p>
                        </div>
                        <div class="glass-panel p-8 rounded-xl card-hover">
                            <div class="text-4xl mb-4 text-accent">03</div>
                            <h3 class="text-xl font-bold mb-2">DATABASE</h3>
                            <p class="text-stone-400 text-sm mb-4">El almacén</p>
                            <p class="text-stone-300">Relaciona datos de forma inteligente. No es un simple Excel.</p>
                        </div>
                    </div>
                </section>

                <!-- AI Method -->
                <section id="method" class="py-16">
                    <div class="text-center mb-16">
                        <h2 class="text-3xl font-bold futuristic-title">FASE 2: EL MÉTODO IA</h2>
                    </div>

                    <div class="space-y-12">
                        <div class="flex flex-col md:flex-row gap-8 items-center">
                            <div class="w-16 h-16 rounded-full border-2 border-accent flex items-center justify-center text-accent text-2xl font-black shrink-0">1</div>
                            <div class="glass-panel p-6 rounded-lg flex-grow border-l-4 border-l-accent">
                                <h4 class="text-xl font-bold text-accent">DEFINICIÓN DE REQUERIMIENTOS</h4>
                                <p class="text-stone-300 mt-2">Pide a la IA que actúe como un experto. "Diseña la base de datos para este negocio..."</p>
                            </div>
                        </div>
                        <div class="flex flex-col md:flex-row gap-8 items-center">
                            <div class="w-16 h-16 rounded-full border-2 border-accent flex items-center justify-center text-accent text-2xl font-black shrink-0">2</div>
                            <div class="glass-panel p-6 rounded-lg flex-grow border-l-4 border-l-accent">
                                <h4 class="text-xl font-bold text-accent">CO-PROGRAMACIÓN</h4>
                                <p class="text-stone-300 mt-2">Itera. No solo copies. Pregunta "¿Por qué esta línea?" para aprender mientras construyes.</p>
                            </div>
                        </div>
                        <div class="flex flex-col md:flex-row gap-8 items-center">
                            <div class="w-16 h-16 rounded-full border-2 border-accent flex items-center justify-center text-accent text-2xl font-black shrink-0">3</div>
                            <div class="glass-panel p-6 rounded-lg flex-grow border-l-4 border-l-accent">
                                <h4 class="text-xl font-bold text-accent">DEPURACIÓN REAL</h4>
                                <p class="text-stone-300 mt-2">Los errores son tus maestros. Pega el bug y deja que la IA te enseñe la solución.</p>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Business Strategy -->
                <section id="business" class="py-16">
                    <div class="grid lg:grid-cols-2 gap-16">
                        <div>
                            <h2 class="text-3xl font-bold futuristic-title mb-6">FASE 3: MONETIZACIÓN</h2>
                            <p class="text-stone-400 mb-8 font-mono">El software se vende cuando soluciona problemas de DINERO.</p>
                            
                            <div class="space-y-6">
                                <div class="flex gap-4">
                                    <div class="text-accent underline font-black">MARCA</div>
                                    <p class="text-stone-300">Documenta tu proceso en redes. No vendas código, vende velocidad.</p>
                                </div>
                                <div class="flex gap-4">
                                    <div class="text-accent underline font-black">IMÁN</div>
                                    <p class="text-stone-300">Crea contenido visual: "Mira cómo este panel ahorró 10 horas".</p>
                                </div>
                                <div class="flex gap-4">
                                    <div class="text-accent underline font-black">TESTIMONIO</div>
                                    <p class="text-stone-300">Un cliente feliz es tu mejor vendedor. Video-pruebas de éxito.</p>
                                </div>
                            </div>
                        </div>

                        <div class="glass-panel p-8 rounded-2xl border-accent/40">
                            <h3 class="text-2xl font-bold text-accent mb-6">Calculadora SaaS</h3>
                            <div class="space-y-6">
                                <div>
                                    <label class="block text-xs uppercase text-stone-500 mb-2">Setup ($)</label>
                                    <input type="number" id="setupFee" value="500" class="w-full bg-black/50 border border-accent/30 rounded p-2 text-accent outline-none focus:border-accent">
                                </div>
                                <div>
                                    <label class="block text-xs uppercase text-stone-500 mb-2">Mensualidad ($)</label>
                                    <input type="number" id="monthlyFee" value="50" class="w-full bg-black/50 border border-accent/30 rounded p-2 text-accent outline-none focus:border-accent">
                                </div>
                                <div>
                                    <label class="block text-xs uppercase text-stone-500 mb-2">Clientes</label>
                                    <input type="range" id="clients" min="1" max="20" value="5" class="w-full accent-accent">
                                    <div id="clientCount" class="text-center text-accent font-bold mt-2">5 clientes</div>
                                </div>
                                <div class="pt-6 border-t border-accent/20">
                                    <div class="flex justify-between items-center">
                                        <span class="text-stone-500">INGRESO ANUAL:</span>
                                        <span id="totalRevenue" class="text-3xl font-black text-accent">$5,500</span>
                                    </div>
                                    <div class="h-32 mt-4">
                                        <canvas id="chartRevenue"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Footer / CTA -->
                <footer class="py-20 text-center">
                    <h2 class="text-3xl font-bold futuristic-title mb-8">¿LISTO PARA TU PRIMER SISTEMA?</h2>
                    <a href="https://www.instagram.com/salvatechnologyy" target="_blank" class="inline-block px-10 py-4 border-2 border-accent text-accent font-black rounded-full hover:bg-accent hover:text-black transition-all">
                        MENSAJE AL INSTAGRAM
                    </a>
                    <p class="mt-8 text-stone-600 text-xs">© 2026 SALVATECHNOLOGY ACADEMY</p>
                </footer>
            </main>
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
        // --- Navigation Scroll Logic ---
        function scrollToGuideSection(id) {
            const element = document.getElementById(id);
            if (element) {
                window.scrollTo({
                    top: element.offsetTop - 100,
                    behavior: 'smooth'
                });
            }
        }

        // --- Tabs Logic ---
        const tabs = document.querySelectorAll('.case-tab');
        const contents = document.querySelectorAll('.case-content');

        tabs.forEach(tab => {
            tab.addEventListener('click', () => {
                tabs.forEach(t => t.classList.remove('nav-active'));
                tab.classList.add('nav-active');

                contents.forEach(c => c.classList.add('hidden'));
                const targetId = tab.getAttribute('data-target') + '-content';
                document.getElementById(targetId).classList.remove('hidden');
            });
        });

        // --- Charts Implementation (Dark Mode Styled) ---
        Chart.defaults.font.family = "'Roboto Mono', monospace";
        Chart.defaults.color = '#a8a29e';
        Chart.defaults.scale.grid.color = 'rgba(255, 140, 0, 0.1)';

        // ISP
        new Chart(document.getElementById('chartISP'), {
            type: 'bar',
            data: {
                labels: ['ANTIGUO', 'SISTEMA'],
                datasets: [{
                    data: [25, 4],
                    backgroundColor: ['#444', '#ff8c00'],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: { y: { beginAtZero: true } }
            }
        });

        // Retail
        new Chart(document.getElementById('chartRetail'), {
            type: 'line',
            data: {
                labels: ['W1', 'W2', 'W3', 'W4'],
                datasets: [{
                    label: 'PRECISIÓN',
                    data: [60, 55, 62, 98],
                    borderColor: '#ff8c00',
                    backgroundColor: 'rgba(255, 140, 0, 0.2)',
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: { y: { min: 0, max: 100 } }
            }
        });

        // Ice Cream
        new Chart(document.getElementById('chartIceCream'), {
            type: 'doughnut',
            data: {
                labels: ['COSTOS', 'MARGEN'],
                datasets: [{
                    data: [70, 30],
                    backgroundColor: ['#222', '#ff8c00'],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '80%'
            }
        });

        // --- Revenue Calculator ---
        const setupIn = document.getElementById('setupFee');
        const monthlyIn = document.getElementById('monthlyFee');
        const clientsIn = document.getElementById('clients');
        const totalOut = document.getElementById('totalRevenue');
        const clientLabel = document.getElementById('clientCount');

        const ctxRev = document.getElementById('chartRevenue').getContext('2d');
        let revChart = new Chart(ctxRev, {
            type: 'bar',
            data: {
                labels: ['SETUP', 'RECURRENTE'],
                datasets: [{
                    data: [0, 0],
                    backgroundColor: ['#ff8c00', '#fff'],
                    borderRadius: 5
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } }
            }
        });

        function updateCalc() {
            const s = Number(setupIn.value);
            const m = Number(monthlyIn.value);
            const c = Number(clientsIn.value);
            const total = (s * c) + (m * 12 * c);
            totalOut.textContent = `$${total.toLocaleString()}`;
            clientLabel.textContent = `${c} ${c === 1 ? 'cliente' : 'clientes'}`;
            revChart.data.datasets[0].data = [s * c, m * 12 * c];
            revChart.update();
        }

        [setupIn, monthlyIn, clientsIn].forEach(i => i.addEventListener('input', updateCalc));
        updateCalc();

    </script>
</body>
</html>
