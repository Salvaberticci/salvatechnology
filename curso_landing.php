<?php
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/config/app.php';

if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_rol'] !== 'estudiante') {
    header('Location: academia');
    exit;
}

$usuarioId = $_SESSION['usuario_id'];
$plan = $_SESSION['usuario_plan'];
$cursoId = (int)($_GET['id'] ?? 0);

$stmt = $pdo->prepare("SELECT * FROM cursos WHERE id = ? AND activo = 1");
$stmt->execute([$cursoId]);
$curso = $stmt->fetch();

if (!$curso) {
    header('Location: ' . BASE_URL . 'cursos');
    exit;
}

$stmt = $pdo->prepare("SELECT id, titulo, orden FROM lecciones WHERE curso_id = ? ORDER BY orden ASC, id ASC");
$stmt->execute([$cursoId]);
$lecciones = $stmt->fetchAll();
$totalLecciones = count($lecciones);

$modulos = [];
foreach ($lecciones as $l) {
    if (preg_match('/^Clase\s+(\d+)(?![\d.])/', $l['titulo'], $m)) {
        $modulos[$m[1]] = $l['titulo'];
    }
}
$totalModulos = count($modulos);

$stmt = $pdo->prepare("SELECT id FROM inscripciones WHERE usuario_id = ? AND curso_id = ? AND estado = 'activa'");
$stmt->execute([$usuarioId, $cursoId]);
$yaInscrito = (bool)$stmt->fetch();

$stmt = $pdo->prepare("SELECT nombre FROM usuarios WHERE id = ?");
$stmt->execute([$curso['profesor_id']]);
$profesor = $stmt->fetch();
$profesorNombre = $profesor['nombre'] ?? 'Salvatore Berticci';
$profesorInicial = strtoupper(substr($profesorNombre, 0, 1));
$profesorExperiencia = 'Más de 6 años de experiencia como desarrollador de software, actualmente impulsado con Inteligencia Artificial, y alrededor de 3 años formando programadores.';

$esPremium = (float)$curso['precio'] > 0;
$tienePlan = $plan === 'suscripcion';

$cursoTituloLower = mb_strtolower($curso['titulo']);
if (strpos($cursoTituloLower, 'píldora') !== false || strpos($cursoTituloLower, 'pildora') !== false) {
    $objetivos = [
        'Aprender conceptos prácticos en sesiones cortas y directas, sin rodeos.',
        'Mantenerte al día con las últimas herramientas, tecnologías y tendencias del desarrollo de software.',
        'Dominar técnicas de ventas y estrategias comerciales para vender tus servicios como desarrollador.',
        'Reforzar fundamentos de programación, arquitectura y bases de datos en cada píldora.',
        'Aplicar Inteligencia Artificial para acelerar tu flujo de trabajo y tu aprendizaje.',
        'Adaptar tu aprendizaje a tu ritmo: cada clase es independiente y puedes verla cuando quieras.',
    ];
} else {
    $objetivos = [
        'Llevarte de los fundamentos a la construcción completa de software real potenciado por IA: dominarás arquitectura, backend, frontend, proyectos desktop y mobile, hasta vender y mantener tus propios sistemas como un ingeniero profesional.',
    ];
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($curso['titulo']); ?> | Salvatechnology Academy</title>
    <base href="<?= BASE_URL ?>">
    <link rel="icon" type="image/png" href="img/logo.png">
    <link rel="stylesheet" href="css/dashboard.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: { 'accent': '#ff8c00', 'dark-bg': '#0a0a0a' }
                }
            }
        }
    </script>
    <style>
        .landing-hero {
            background:
                radial-gradient(ellipse at 20% 20%, rgba(255,140,0,0.08), transparent 50%),
                radial-gradient(ellipse at 80% 0%, rgba(255,140,0,0.05), transparent 40%);
        }
        .module-item {
            transition: all 0.3s;
            cursor: default;
        }
        .module-item:hover {
            transform: translateX(4px);
            border-color: rgba(255,140,0,0.4);
        }
    </style>
</head>
<body class="dashboard-body">
    <?php require 'partials/matrix-rain.php'; ?>
    <div class="scanlines"></div>

    <div class="dashboard-layout">
        <aside class="dash-sidebar">
            <div class="logo-side">
                <a href="<?= BASE_URL ?>"><img src="img/logo.png" alt="Salva Technology"></a>
            </div>
            <div class="user-badge">
                <div class="avatar"><?php echo strtoupper(substr($_SESSION['usuario_nombre'], 0, 1)); ?></div>
                <div class="name"><?php echo htmlspecialchars($_SESSION['usuario_nombre']); ?></div>
                <span class="plan-badge plan-<?php echo $plan; ?>"><?php echo $plan === 'suscripcion' ? 'SUSCRIPCIÓN' : 'GRATUITO'; ?></span>
            </div>
            <nav class="dash-nav">
                <a href="dashboard"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>Dashboard</a>
                <a href="cursos" class="active"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>Explorar Cursos</a>
                <a href="planes"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>Planes</a>
                <a href="logout"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>Cerrar Sesión</a>
            </nav>
        </aside>

        <main class="dash-main">
            <div class="dash-header">
                <h1>Detalle <span>del Curso</span></h1>
                <div class="header-actions">
                    <span class="text-stone-600 text-xs font-mono"><?php echo date('d.m.Y'); ?></span>
                </div>
            </div>

            <!-- HERO -->
            <div class="landing-hero relative overflow-hidden rounded-2xl border border-white/10 mb-8">
                <div class="grid md:grid-cols-2 gap-6 p-6 md:p-10 items-center">
                    <div class="space-y-5">
                        <div class="flex flex-wrap gap-2">
                            <?php if ($curso['categoria']): ?>
                            <span class="px-3 py-1 rounded-full bg-accent/15 border border-accent/30 text-accent text-[10px] font-mono font-bold uppercase tracking-wider"><?php echo htmlspecialchars($curso['categoria']); ?></span>
                            <?php endif; ?>
                            <span class="px-3 py-1 rounded-full bg-white/5 border border-white/10 text-stone-400 text-[10px] font-mono font-bold uppercase tracking-wider"><?php echo $esPremium ? 'PREMIUM' : 'GRATUITO'; ?></span>
                            <?php if ($curso['duracion']): ?>
                            <span class="px-3 py-1 rounded-full bg-white/5 border border-white/10 text-stone-400 text-[10px] font-mono font-bold uppercase tracking-wider">⏱ <?php echo htmlspecialchars($curso['duracion']); ?></span>
                            <?php endif; ?>
                        </div>
                        <h1 class="font-['Orbitron'] text-white text-2xl md:text-4xl font-black leading-tight uppercase tracking-tight">
                            <?php echo htmlspecialchars($curso['titulo']); ?>
                        </h1>
                        <p class="text-stone-400 text-sm font-mono leading-relaxed">
                            <?php echo htmlspecialchars($curso['descripcion'] ?? ''); ?>
                        </p>
                        <div class="flex items-center gap-4 text-xs font-mono text-stone-400">
                            <span><strong class="text-accent text-lg font-['Orbitron']"><?php echo $totalModulos; ?></strong> módulos</span>
                            <span class="text-stone-700">|</span>
                            <span><strong class="text-accent text-lg font-['Orbitron']"><?php echo $totalLecciones; ?></strong> lecciones</span>
                            <span class="text-stone-700">|</span>
                            <span class="px-3 py-1 rounded-full bg-accent/15 border border-accent/40 text-accent font-bold uppercase tracking-wider"><?php echo $esPremium ? 'Adquirir con suscripción' : 'Gratis'; ?></span>
                        </div>
                    </div>
                    <div class="relative">
                        <?php if ($curso['imagen']): ?>
                        <img src="<?php echo htmlspecialchars($curso['imagen']); ?>" alt="<?php echo htmlspecialchars($curso['titulo']); ?>" class="w-full rounded-xl border border-accent/30 shadow-[0_0_40px_rgba(255,140,0,0.15)]">
                        <?php else: ?>
                        <div class="aspect-video rounded-xl bg-black/40 border border-white/10 flex items-center justify-center text-stone-600 font-['Orbitron'] text-xs">SIN PORTADA</div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- PROFESOR -->
            <div class="bg-white/5 border border-white/10 rounded-2xl p-6 mb-8">
                <div class="flex items-center gap-5 flex-wrap">
                    <div class="w-20 h-20 rounded-2xl bg-accent/20 border border-accent/40 flex items-center justify-center font-['Orbitron'] text-3xl font-black text-accent shadow-[0_0_30px_rgba(255,140,0,0.2)]">
                        <?php echo $profesorInicial; ?>
                    </div>
                    <div class="flex-1 min-w-[200px]">
                        <h3 class="font-['Orbitron'] text-white text-lg font-bold"><?php echo htmlspecialchars($profesorNombre); ?></h3>
                        <p class="text-accent text-xs font-mono uppercase tracking-wider mb-1">Fundador & Profesor Principal</p>
                        <p class="text-stone-400 text-xs font-mono leading-relaxed">
                            Arquitecto de software, especialista en la metodología ADD (Arquitectura Dirigida por IA).
                            <strong class="text-white"><?php echo $profesorExperiencia; ?></strong>
                        </p>
                    </div>
                    <div class="hidden md:block text-right font-mono text-[10px] text-stone-500">
                        <p>"El software no se construye con código,<br>se construye con arquitectura."</p>
                    </div>
                </div>
            </div>

            <!-- OBJETIVO -->
            <div class="bg-white/5 border border-accent/20 rounded-2xl p-6 mb-8">
                <h3 class="font-['Orbitron'] text-white text-sm font-bold mb-3"><span class="text-accent">🎯</span> <?php echo $totalModulos <= 1 ? 'Objetivos del Curso' : 'Objetivo del Curso'; ?></h3>
                <?php if (count($objetivos) > 1): ?>
                <ul class="space-y-3">
                    <?php foreach ($objetivos as $obj): ?>
                    <li class="flex items-start gap-3 text-stone-300 text-sm font-mono leading-relaxed">
                        <span class="text-accent text-base mt-0.5">▸</span>
                        <span><?php echo $obj; ?></span>
                    </li>
                    <?php endforeach; ?>
                </ul>
                <?php else: ?>
                <p class="text-stone-300 text-sm font-mono leading-relaxed">
                    <?php echo $objetivos[0]; ?>
                </p>
                <?php endif; ?>
            </div>

            <!-- CONTENIDO / MODULOS -->
            <div class="bg-white/5 border border-white/10 rounded-2xl p-6 mb-8">
                <h3 class="font-['Orbitron'] text-white text-sm font-bold mb-2"><span class="text-accent">📚</span> Contenido del Curso</h3>
                <p class="text-stone-500 text-xs font-mono mb-5"><?php echo $totalModulos; ?> módulos · <?php echo $totalLecciones; ?> lecciones con video, e-books interactivos, diapositivas y actividades prácticas.</p>
                <div class="grid md:grid-cols-2 gap-3">
                    <?php foreach ($modulos as $num => $titulo): ?>
                    <div class="module-item bg-black/30 border border-white/10 rounded-xl p-4">
                        <div class="flex items-start gap-3">
                            <span class="w-8 h-8 shrink-0 rounded-lg bg-accent/15 border border-accent/30 text-accent flex items-center justify-center font-['Orbitron'] text-xs font-black"><?php echo $num; ?></span>
                            <div>
                                <h4 class="text-white text-xs font-bold font-mono leading-snug"><?php echo htmlspecialchars($titulo); ?></h4>
                                <span class="text-stone-600 text-[10px] font-mono"><?php
                                    $c = 0;
                                    foreach ($lecciones as $l) {
                                        if (preg_match('/^Clase\s+' . preg_quote($num, '/') . '\.\d+/', $l['titulo'])) $c++;
                                    }
                                    echo $c > 0 ? $c . ' lecciones internas' : 'Lección principal';
                                ?></span>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- CTA -->
            <div class="bg-white/5 border border-accent/30 rounded-2xl p-8 text-center mb-10" style="box-shadow:0 0 40px rgba(255,140,0,0.08)">
                <?php if ($yaInscrito): ?>
                <h3 class="font-['Orbitron'] text-white text-lg font-bold mb-2">¡Ya estás inscrito!</h3>
                <p class="text-stone-400 text-xs font-mono mb-5">Continúa tu aprendizaje donde lo dejaste.</p>
                <a href="curso/<?php echo $cursoId; ?>" class="btn-continuar" style="display:inline-flex;padding:0.8rem 2.5rem;">▶ IR AL CURSO</a>
                <?php elseif ($esPremium && !$tienePlan): ?>
                <h3 class="font-['Orbitron'] text-white text-lg font-bold mb-2">🔒 Curso Premium</h3>
                <p class="text-stone-400 text-xs font-mono mb-1">Este curso requiere una suscripción activa para acceder.</p>
                <p class="text-stone-500 text-[11px] font-mono mb-5">Elige un plan y desbloquea todos los cursos sin límites.</p>
                <a href="planes" class="btn-continuar" style="display:inline-flex;padding:0.8rem 2.5rem;">💳 ADQUIRIR PLAN</a>
                <?php else: ?>
                <h3 class="font-['Orbitron'] text-white text-lg font-bold mb-2">¿Listo para empezar?</h3>
                <p class="text-stone-400 text-xs font-mono mb-5">Inscríbete y accede al curso completo ahora mismo.</p>
                <a href="inscribir/<?php echo $cursoId; ?>" class="btn-continuar" style="display:inline-flex;padding:0.8rem 2.5rem;">🚀 INSCRIBIRME AL CURSO</a>
                <?php endif; ?>
            </div>
        </main>
        <?php require 'partials/chatbot.php'; ?>
    </div>
    <script src="js/matrix-rain.js"></script>
</body>
</html>
