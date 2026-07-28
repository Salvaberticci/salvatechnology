<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acceso Academia | Salvatechnology</title>
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
        .auth-tab {
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .auth-tab.active {
            border-bottom: 2px solid #ff8c00;
            color: #ff8c00;
        }
        .auth-input {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 140, 0, 0.2);
            transition: all 0.3s ease;
        }
        .auth-input:focus {
            border-color: #ff8c00;
            box-shadow: 0 0 15px rgba(255, 140, 0, 0.3);
            outline: none;
            background: rgba(255, 255, 255, 0.05);
        }
        .glow-border {
            box-shadow: 0 0 30px rgba(255, 140, 0, 0.15);
        }
        @keyframes glitch-text {
            0% { transform: skewX(0deg); }
            20% { transform: skewX(2deg); }
            40% { transform: skewX(-2deg); }
            60% { transform: skewX(1deg); }
            80% { transform: skewX(-1deg); }
            100% { transform: skewX(0deg); }
        }
        .glitch-hover:hover {
            animation: glitch-text 0.3s ease;
        }
    </style>
</head>
<body class="text-white bg-black min-h-screen">
    <canvas id="bg"></canvas>

    <div id="page-content" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 relative z-10 page-fade-in">
        <header class="mb-8 flex justify-between items-center">
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
                ACCESO ACADEMIA
            </div>
        </header>

        <main class="max-w-lg mx-auto pt-12">
            <section class="text-center mb-12 overflow-hidden">
                <h1 class="futuristic-title mb-4 leading-tight text-center">
                    <div class="text-4xl md:text-5xl font-black">ACADEMIA</div>
                    <div class="text-4xl md:text-5xl font-black text-white">SALVATECHNOLOGY</div>
                </h1>
                <p class="text-stone-400 font-mono text-sm">
                    Accede a tu plataforma de aprendizaje. Cursos, lecciones y actividades prácticas.
                </p>
            </section>

            <div class="glass-panel rounded-2xl p-8 md:p-10 glow-border border border-accent/30">
                <div class="flex border-b border-white/10 mb-8">
                    <button class="auth-tab active flex-1 pb-4 text-center font-mono text-sm uppercase tracking-widest text-accent" data-tab="login">Iniciar Sesión</button>
                    <button class="auth-tab flex-1 pb-4 text-center font-mono text-sm uppercase tracking-widest text-stone-500 hover:text-stone-300" data-tab="register">Registrarse</button>
                </div>

                <div id="login-form" class="auth-form">
                    <form id="loginForm" method="POST">
                        <div class="space-y-5">
                            <div>
                                <label class="block text-xs uppercase text-stone-500 mb-2 font-mono tracking-widest">Email</label>
                                <input type="email" name="email" required class="auth-input w-full px-5 py-4 rounded-xl text-white font-mono text-sm">
                            </div>
                            <div>
                                <label class="block text-xs uppercase text-stone-500 mb-2 font-mono tracking-widest">Contraseña</label>
                                <input type="password" name="password" required class="auth-input w-full px-5 py-4 rounded-xl text-white font-mono text-sm">
                            </div>
                            <button type="submit" class="w-full py-4 bg-accent text-black font-black uppercase tracking-widest rounded-xl hover:bg-orange-600 transition-all shadow-[0_0_20px_rgba(255,140,0,0.3)] text-sm glitch-hover">
                                INGRESAR
                            </button>
                        </div>
                    </form>
                    <p id="login-error" class="text-red-500 text-xs font-mono mt-4 text-center hidden"></p>
                </div>

                <div id="register-form" class="auth-form hidden">
                    <form id="registerForm" method="POST">
                        <div class="space-y-4">
                            <div>
                                <label class="block text-xs uppercase text-stone-500 mb-2 font-mono tracking-widest">Nombre Completo</label>
                                <input type="text" name="nombre" required class="auth-input w-full px-5 py-4 rounded-xl text-white font-mono text-sm">
                            </div>
                            <div>
                                <label class="block text-xs uppercase text-stone-500 mb-2 font-mono tracking-widest">Email</label>
                                <input type="email" name="email" required class="auth-input w-full px-5 py-4 rounded-xl text-white font-mono text-sm">
                            </div>
                            <div>
                                <label class="block text-xs uppercase text-stone-500 mb-2 font-mono tracking-widest">Teléfono</label>
                                <input type="text" name="telefono" class="auth-input w-full px-5 py-4 rounded-xl text-white font-mono text-sm">
                            </div>
                            <div>
                                <label class="block text-xs uppercase text-stone-500 mb-2 font-mono tracking-widest">País</label>
                                <input type="text" name="pais" class="auth-input w-full px-5 py-4 rounded-xl text-white font-mono text-sm">
                            </div>
                            <div>
                                <label class="block text-xs uppercase text-stone-500 mb-2 font-mono tracking-widest">Contraseña</label>
                                <input type="password" name="password" required class="auth-input w-full px-5 py-4 rounded-xl text-white font-mono text-sm">
                            </div>
                            <button type="submit" class="w-full py-4 bg-accent text-black font-black uppercase tracking-widest rounded-xl hover:bg-orange-600 transition-all shadow-[0_0_20px_rgba(255,140,0,0.3)] text-sm glitch-hover">
                                CREAR CUENTA
                            </button>
                        </div>
                    </form>
                    <p id="register-error" class="text-red-500 text-xs font-mono mt-4 text-center hidden"></p>
                </div>
            </div>

            <p class="text-center mt-8 text-stone-600 text-[10px] font-mono uppercase tracking-widest">
                Al registrarte obtienes acceso gratuito. Actualiza tu plan cuando quieras.
            </p>
        </main>

        <footer class="mt-20 text-center">
            <p class="text-stone-600 text-xs font-mono tracking-tighter uppercase">© 2026 SALVATECHNOLOGY ACADEMY</p>
        </footer>
    </div>

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
        window.addEventListener('load', () => {
            const content = document.getElementById('page-content');
            setTimeout(() => content.classList.add('active'), 100);
        });

        const tabs = document.querySelectorAll('.auth-tab');
        const forms = {
            login: document.getElementById('login-form'),
            register: document.getElementById('register-form')
        };

        tabs.forEach(tab => {
            tab.addEventListener('click', () => {
                tabs.forEach(t => { t.classList.remove('active'); t.classList.add('text-stone-500'); });
                tab.classList.add('active');
                tab.classList.remove('text-stone-500');
                Object.keys(forms).forEach(key => forms[key].classList.toggle('hidden', key !== tab.dataset.tab));
            });
        });

        function showError(formId, message) {
            const el = document.getElementById(formId);
            el.textContent = message;
            el.classList.remove('hidden');
            setTimeout(() => el.classList.add('hidden'), 5000);
        }

        document.getElementById('loginForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            const form = e.target;
            const btn = form.querySelector('button[type="submit"]');
            btn.textContent = 'INGRESANDO...';
            btn.disabled = true;
            try {
                const res = await fetch('login', { method: 'POST', body: new FormData(form) });
                const data = await res.json();
                if (data.status === 'success') {
                    window.location.href = data.redirect;
                } else {
                    showError('login-error', data.message);
                }
            } catch (err) {
                showError('login-error', 'Error de conexión');
            }
            btn.textContent = 'INGRESAR';
            btn.disabled = false;
        });

        document.getElementById('registerForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            const form = e.target;
            const btn = form.querySelector('button[type="submit"]');
            btn.textContent = 'CREANDO CUENTA...';
            btn.disabled = true;
            try {
                const res = await fetch('register', { method: 'POST', body: new FormData(form) });
                const data = await res.json();
                if (data.status === 'success') {
                    window.location.href = data.redirect;
                } else {
                    showError('register-error', data.message);
                }
            } catch (err) {
                showError('register-error', 'Error de conexión');
            }
            btn.textContent = 'CREAR CUENTA';
            btn.disabled = false;
        });
    </script>
</body>
</html>
