<?php
require_once __DIR__ . '/config/db.php';

if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_rol'] !== 'estudiante') {
    header('Location: academia');
    exit;
}

$usuarioId = $_SESSION['usuario_id'];
$plan = $_SESSION['usuario_plan'];

$stmt = $pdo->prepare("SELECT c.*, i.fecha_inscripcion, i.estado as inscripcion_estado, i.tipo as inscripcion_tipo,
    (SELECT COUNT(*) FROM lecciones WHERE curso_id = c.id) as total_lecciones,
    (SELECT COUNT(*) FROM progreso_lecciones pl JOIN lecciones l ON pl.leccion_id = l.id WHERE l.curso_id = c.id AND pl.usuario_id = ? AND pl.completado = 1) as lecciones_completadas
    FROM inscripciones i JOIN cursos c ON i.curso_id = c.id
    WHERE i.usuario_id = ? AND i.estado = 'activa' AND c.activo = 1
    ORDER BY i.fecha_inscripcion DESC");
$stmt->execute([$usuarioId, $usuarioId]);
$misCursos = $stmt->fetchAll();

$stmt = $pdo->prepare("SELECT COUNT(*) FROM entregas WHERE usuario_id = ? AND estado = 'pendiente'");
$stmt->execute([$usuarioId]);
$pendientes = $stmt->fetchColumn();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | Salvatechnology Academy</title>
    <link rel="stylesheet" href="css/dashboard.css">
    <base href="/salvatechnology/">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'accent': '#ff8c00',
                        'dark-bg': '#0a0a0a',
                    }
                }
            }
        }
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <style>
        .matrix-rain { position: fixed; top: 0; left: 0; width: 100%; height: 100%; pointer-events: none; z-index: 0; opacity: 0.06; }
    </style>
</head>
<body class="dashboard-body">
    <div class="scanlines"></div>
    <canvas class="matrix-rain" id="matrixCanvas"></canvas>

    <div class="dashboard-layout">
        <aside class="dash-sidebar">
            <div class="logo-side">
                <a href="/salvatechnology/"><img src="img/logo.png" alt="Salva Technology"></a>
            </div>
            <div class="user-badge">
                <div class="avatar"><?php echo strtoupper(substr($_SESSION['usuario_nombre'], 0, 1)); ?></div>
                <div class="name"><?php echo htmlspecialchars($_SESSION['usuario_nombre']); ?></div>
                <span class="plan-badge plan-<?php echo $plan; ?>"><?php echo $plan === 'suscripcion' ? 'SUSCRIPCIÓN' : 'GRATUITO'; ?></span>
            </div>
            <nav class="dash-nav">
                <a href="dashboard" class="active">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    Dashboard
                </a>
                <a href="cursos">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                    Explorar Cursos
                </a>
                <a href="planes">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    Planes
                </a>
                <a href="logout">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    Cerrar Sesión
                </a>
            </nav>
        </aside>

        <main class="dash-main">
            <div class="dash-header">
                <h1>Panel de <span>Control</span></h1>
                <div class="header-actions">
                    <span class="text-stone-600 text-xs font-mono"><?php echo date('d.m.Y'); ?></span>
                </div>
            </div>

            <?php if (count($misCursos) > 0): ?>
            <div class="course-feed">
                <?php foreach ($misCursos as $curso):
                    $progreso = $curso['total_lecciones'] > 0 ? round(($curso['lecciones_completadas'] / $curso['total_lecciones']) * 100) : 0;
                    $badgeClass = $curso['inscripcion_tipo'] === 'gratuito' ? 'badge-gratis' : ($curso['inscripcion_tipo'] === 'suscripcion' ? 'badge-suscripcion' : 'badge-pagado');
                    $badgeLabel = $curso['inscripcion_tipo'] === 'gratuito' ? 'GRATIS' : ($curso['inscripcion_tipo'] === 'suscripcion' ? 'SUSCRIPCIÓN' : 'PAGADO');
                ?>
                <div class="course-card">
                    <div class="card-top">
                        <div>
                            <div class="card-title"><?php echo htmlspecialchars($curso['titulo']); ?></div>
                            <div class="card-meta">
                                <span class="badge <?php echo $badgeClass; ?>">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    <?php echo $badgeLabel; ?>
                                </span>
                                <span class="text-stone-600 text-xs font-mono">
                                    Inscrito: <?php echo date('d/m/Y', strtotime($curso['fecha_inscripcion'])); ?>
                                </span>
                                <?php if ($curso['inscripcion_tipo'] === 'pago' && $curso['fecha_inscripcion']): ?>
                                <span class="text-stone-600 text-xs font-mono">
                                    Pago: <?php echo date('d/m/Y', strtotime($curso['fecha_inscripcion'])); ?>
                                </span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <div class="progress-section">
                        <div class="progress-bar-track">
                            <div class="progress-bar-fill" style="width: <?php echo $progreso; ?>%"></div>
                        </div>
                        <div class="progress-label">
                            <span>PROGRESO</span>
                            <span><?php echo $curso['lecciones_completadas']; ?>/<?php echo $curso['total_lecciones']; ?> lecciones (<?php echo $progreso; ?>%)</span>
                        </div>
                    </div>
                    <div class="card-actions">
                        <a href="curso/<?php echo $curso['id']; ?>" class="btn-continuar">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Continuar
                        </a>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <?php else: ?>
            <div class="empty-state">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                <h3>No tienes cursos inscritos</h3>
                <p>Explora el catálogo y comienza tu aprendizaje</p>
                <a href="cursos" class="btn-explorar" style="display:inline-flex">EXPLORAR CURSOS</a>
            </div>
            <?php endif; ?>
        </main>
    </div>

    <script>
        const canvas = document.getElementById('matrixCanvas');
        const ctx = canvas.getContext('2d');
        canvas.width = window.innerWidth;
        canvas.height = window.innerHeight;

        const chars = 'アイウエオカキクケコサシスセソタチツテトナニヌネノハヒフヘホマミムメモヤユヨラリルレロワヲン0123456789<>/[]{}|+*@#$%';
        const fontSize = 12;
        const columns = canvas.width / fontSize;
        const drops = Array.from({ length: columns }, () => 1);

        function drawMatrix() {
            ctx.fillStyle = 'rgba(10, 10, 10, 0.05)';
            ctx.fillRect(0, 0, canvas.width, canvas.height);
            ctx.fillStyle = '#ff8c00';
            ctx.font = fontSize + 'px monospace';

            for (let i = 0; i < drops.length; i++) {
                const text = chars[Math.floor(Math.random() * chars.length)];
                ctx.fillStyle = Math.random() > 0.98 ? '#fff' : '#ff8c00';
                ctx.fillText(text, i * fontSize, drops[i] * fontSize);
                if (drops[i] * fontSize > canvas.height && Math.random() > 0.975) drops[i] = 0;
                drops[i]++;
            }
        }
        setInterval(drawMatrix, 50);

        window.addEventListener('resize', () => {
            canvas.width = window.innerWidth;
            canvas.height = window.innerHeight;
        });
    </script>
</body>
</html>
