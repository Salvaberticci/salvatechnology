<?php
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/config/app.php';

if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_rol'] !== 'estudiante') {
    header('Location: ' . BASE_URL . 'academia');
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

$stmt = $pdo->prepare("SELECT * FROM pagos WHERE usuario_id = ? AND tipo = 'suscripcion' AND estado = 'pendiente' LIMIT 1");
$stmt->execute([$usuarioId]);
$pagoPendiente = $stmt->fetch();

$diasRestantes = 0;
$planLabel = '';
$suscripcionExpira = $_SESSION['suscripcion_expira'] ?? null;

if (!$suscripcionExpira && $plan === 'suscripcion') {
    $stmt = $pdo->prepare("SELECT suscripcion_expira FROM usuarios WHERE id = ?");
    $stmt->execute([$usuarioId]);
    $suscripcionExpira = $stmt->fetchColumn();
}

if ($plan === 'suscripcion' && $suscripcionExpira) {
    $expira = $suscripcionExpira;
    $diasRestantes = max(0, floor((strtotime($expira) - time()) / 86400));
    $planLabel = '';
    $stmt = $pdo->prepare("SELECT monto FROM pagos WHERE usuario_id = ? AND tipo = 'suscripcion' AND estado = 'completado' ORDER BY fecha_pago DESC LIMIT 1");
    $stmt->execute([$usuarioId]);
    $ultPago = $stmt->fetchColumn();
    if ($ultPago) {
        $mapaMeses = [40 => '1 Mes', 110 => '3 Meses', 190 => '6 Meses', 380 => '1 Año'];
        $planLabel = $mapaMeses[(int)$ultPago] ?? '';
    }
}

// ---------- Estadísticas rápidas ----------
$stmt = $pdo->prepare("SELECT
    (SELECT COUNT(*) FROM inscripciones i JOIN cursos c ON i.curso_id = c.id WHERE i.usuario_id = ? AND i.estado = 'activa' AND c.activo = 1) AS cursos_activos,
    (SELECT COUNT(*) FROM progreso_lecciones WHERE usuario_id = ? AND completado = 1) AS clases_completadas,
    (SELECT COUNT(*) FROM entregas WHERE usuario_id = ? AND estado = 'pendiente') AS tareas_pendientes,
    (SELECT COUNT(*) FROM entregas WHERE usuario_id = ? AND estado <> 'pendiente' AND calificacion IS NOT NULL) AS entregas_calificadas");
$stmt->execute([$usuarioId, $usuarioId, $usuarioId, $usuarioId]);
$stats = $stmt->fetch();

// XP total igual que en perfil.php
$stmt = $pdo->prepare("SELECT * FROM ebook_progreso WHERE usuario_id = ?");
$stmt->execute([$usuarioId]);
$xpEbooks = 0; $quizAciertosTotal = 0;
foreach ($stmt->fetchAll() as $ep) {
    $xpEbooks += (int)$ep['xp'];
    $quizAciertosTotal += (int)$ep['quiz_aciertos'];
}
$entregasAprobadas = (int)$stats['entregas_calificadas'];
$xpTotal = $xpEbooks + (int)$stats['clases_completadas'] * 10 + $entregasAprobadas * 20 + $quizAciertosTotal * 5;
$nivel = floor($xpTotal / 100) + 1;

// ---------- Continuar donde quedaste (primera lección sin completar de cada curso) ----------
$stmt = $pdo->prepare("SELECT c.id AS curso_id, c.titulo AS curso_titulo, l.id AS leccion_id, l.titulo AS leccion_titulo, l.orden,
    pl.ultimo_acceso,
    (SELECT COUNT(*) FROM lecciones WHERE curso_id = c.id) AS total_lecciones,
    (SELECT COUNT(*) FROM progreso_lecciones pl2 JOIN lecciones l2 ON pl2.leccion_id = l2.id WHERE l2.curso_id = c.id AND pl2.usuario_id = ? AND pl2.completado = 1) AS completadas
    FROM inscripciones i
    JOIN cursos c ON i.curso_id = c.id AND i.estado = 'activa' AND c.activo = 1
    JOIN lecciones l ON l.curso_id = c.id
    LEFT JOIN progreso_lecciones pl ON pl.leccion_id = l.id AND pl.usuario_id = ?
    WHERE COALESCE(pl.completado, 0) = 0
    ORDER BY i.fecha_inscripcion DESC, l.orden ASC");
$stmt->execute([$usuarioId, $usuarioId]);
$porContinuar = [];
foreach ($stmt->fetchAll() as $pc) {
    if (!isset($porContinuar[$pc['curso_id']])) {
        $porContinuar[$pc['curso_id']] = $pc;
    }
}

// ---------- Actividades pendientes (quests/minis sin entregar o en revisión) ----------
$stmt = $pdo->prepare("SELECT a.id AS actividad_id, a.titulo AS actividad_titulo, l.id AS leccion_id, l.titulo AS leccion_titulo,
    l.orden, c.id AS curso_id, c.titulo AS curso_titulo,
    COALESCE(e.estado, 'sin_entrega') AS entrega_estado, e.fecha_entrega, e.calificacion,
    (SELECT COUNT(*) FROM lecciones WHERE curso_id = c.id AND orden <= l.orden) AS clase_actual
    FROM inscripciones i
    JOIN cursos c ON i.curso_id = c.id AND i.estado = 'activa' AND c.activo = 1
    JOIN lecciones l ON l.curso_id = c.id
    JOIN actividades a ON a.leccion_id = l.id
    LEFT JOIN entregas e ON e.actividad_id = a.id AND e.usuario_id = ?
    LEFT JOIN progreso_lecciones pl ON pl.leccion_id = l.id AND pl.usuario_id = ?
    WHERE COALESCE(pl.completado, 0) = 0
    ORDER BY c.id, l.orden, a.id
    LIMIT 6");
$stmt->execute([$usuarioId, $usuarioId]);
$actividadesPendientes = $stmt->fetchAll();

// ---------- Últimas calificaciones del profesor ----------
$stmt = $pdo->prepare("SELECT e.calificacion, e.comentario_profesor, e.fecha_revision, e.estado,
    a.titulo AS actividad_titulo, l.titulo AS leccion_titulo, c.titulo AS curso_titulo, c.id AS curso_id, l.id AS leccion_id
    FROM entregas e
    JOIN actividades a ON e.actividad_id = a.id
    JOIN lecciones l ON a.leccion_id = l.id
    JOIN cursos c ON l.curso_id = c.id
    WHERE e.usuario_id = ? AND e.estado <> 'pendiente' AND e.calificacion IS NOT NULL
    ORDER BY e.fecha_revision DESC
    LIMIT 5");
$stmt->execute([$usuarioId]);
$calificaciones = $stmt->fetchAll();

// ---------- Barra de progreso promedio ----------
$progresoPromedio = 0;
if (!empty($misCursos)) {
    $suma = 0;
    foreach ($misCursos as $pc) {
        $suma += $pc['total_lecciones'] > 0 ? ($pc['lecciones_completadas'] / $pc['total_lecciones']) * 100 : 0;
    }
    $progresoPromedio = round($suma / count($misCursos));
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | Salvatechnology Academy</title>
    <base href="<?= BASE_URL ?>">
    <link rel="icon" type="image/webp" href="img/logo.webp">
    <link rel="stylesheet" href="css/dashboard.css">
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
    </style>
</head>
<body class="dashboard-body">
    <div class="scanlines"></div>
    <?php require 'partials/matrix-rain.php'; ?>

    <div class="dashboard-layout">
        <aside class="dash-sidebar">
            <div class="logo-side">
                <a href="<?= BASE_URL ?>"><img src="img/logo.webp" alt="Salva Technology"></a>
            </div>
            <div class="user-badge">
                <?php if (!empty($_SESSION['usuario_avatar'])): ?>
                    <img src="<?= htmlspecialchars($_SESSION['usuario_avatar']) ?>" alt="Foto" class="avatar-img">
                <?php else: ?>
                    <div class="avatar"><?php echo strtoupper(substr($_SESSION['usuario_nombre'], 0, 1)); ?></div>
                <?php endif; ?>
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
                <a href="perfil">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    Mi Perfil
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

            <?php if ($pagoPendiente): ?>
            <div class="bg-yellow-500/10 border border-yellow-500/30 rounded-xl p-5 mb-6 flex items-center gap-4">
                <div class="text-3xl">⏳</div>
                <div>
                    <h3 class="font-['Orbitron'] text-yellow-400 text-sm font-bold">Tu suscripción está en revisión</h3>
                    <p class="text-stone-400 text-xs font-mono mt-1">Realizaste un pago de <strong class="text-white">$<?php echo number_format($pagoPendiente['monto'], 2); ?> USD</strong> por <?php echo htmlspecialchars($pagoPendiente['metodo_pago']); ?>. Un profesor lo revisará y activará tu plan.</p>
                </div>
            </div>
            <?php elseif ($plan === 'suscripcion'): ?>
            <div class="bg-[var(--panel-bg)] border border-[var(--border-color)] rounded-xl p-5 mb-6">
                <div class="flex items-center justify-between flex-wrap gap-3">
                    <div class="flex items-center gap-3">
                        <div class="text-3xl">🎯</div>
                        <div>
                            <h3 class="font-['Orbitron'] text-white text-sm font-bold">Plan <?php echo $planLabel ?: 'Suscripción'; ?></h3>
                            <p class="text-stone-400 text-xs font-mono mt-1">
                                <?php if ($diasRestantes > 0): ?>
                                    Tu suscripción vence el <strong class="text-white"><?php echo date('d/m/Y', strtotime($suscripcionExpira)); ?></strong>
                                    · <span class="<?php echo $diasRestantes <= 7 ? 'text-red-400' : 'text-green-400'; ?>"><?php echo $diasRestantes; ?> días restantes</span>
                                <?php elseif (!$suscripcionExpira): ?>
                                    Sin fecha de vencimiento · <span class="text-yellow-400">Consulta con tu profesor</span>
                                <?php else: ?>
                                    Tu suscripción vence hoy · <span class="text-red-400">Renueva tu plan</span>
                                <?php endif; ?>
                            </p>
                        </div>
                    </div>
                    <?php if ($diasRestantes <= 15): ?>
                    <a href="planes" class="btn-explorar" style="padding:0.5rem 1.2rem;font-size:0.6rem;">RENOVAR PLAN</a>
                    <?php endif; ?>
                </div>
            </div>
<?php endif; ?>

            <!-- KPIs -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                <div class="bg-[var(--panel-bg)] border border-[var(--border-color)] rounded-xl p-4">
                    <div class="text-[10px] uppercase tracking-widest text-stone-500 font-mono mb-2">Progreso promedio</div>
                    <div class="text-2xl font-bold font-['Orbitron'] text-accent"><?php echo $progresoPromedio; ?>%</div>
                    <div class="text-[10px] text-stone-600 font-mono mt-1"><?php echo count($misCursos); ?> curso(s) activos</div>
                </div>
                <div class="bg-[var(--panel-bg)] border border-[var(--border-color)] rounded-xl p-4">
                    <div class="text-[10px] uppercase tracking-widest text-stone-500 font-mono mb-1">Clases completadas</div>
                    <div class="text-2xl font-bold font-['Orbitron'] text-white"><?php echo (int)$stats['clases_completadas']; ?></div>
                    <div class="text-[10px] text-stone-600 font-mono mt-1">marca progreso en cada clase</div>
                </div>
                <div class="bg-[var(--panel-bg)] border border-[var(--border-color)] rounded-xl p-4">
                    <div class="text-[10px] uppercase tracking-widest text-stone-500 font-mono mb-1">Tareas pendientes</div>
                    <div class="text-2xl font-bold font-['Orbitron'] text-yellow-400"><?php echo (int)$stats['tareas_pendientes']; ?></div>
                    <div class="text-[10px] text-stone-600 font-mono mt-1">entregas en revisión</div>
                </div>
                <div class="bg-[var(--panel-bg)] border border-[var(--border-color)] rounded-xl p-4">
                    <div class="text-[10px] uppercase tracking-widest text-stone-500 font-mono mb-1">Nivel / XP</div>
                    <div class="text-2xl font-bold font-['Orbitron'] text-green-400"><?php echo $nivel; ?> <span class="text-sm text-stone-500">· <?php echo number_format($xpTotal); ?> XP</span></div>
                    <div class="mt-2"><div class="progress-bar-track" style="height:4px;"><div class="progress-bar-fill" style="width:<?php echo min(100, $xpTotal % 100); ?>%"></div></div></div>
                </div>
            </div>

            <!-- Continuar donde quedaste -->
            <?php if (!empty($porContinuar)): ?>
            <div class="mb-6">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="font-['Orbitron'] text-white text-sm font-bold"><span class="text-accent">▶</span> CONTINÚA DONDE QUEDASTE</h3>
                </div>
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                    <?php foreach ($porContinuar as $pc): ?>
                    <div class="bg-[var(--panel-bg)] border border-[var(--border-color)] rounded-xl p-5 flex items-center justify-between gap-4">
                        <div class="min-w-0">
                            <div class="text-[10px] uppercase tracking-widest text-stone-500 font-mono mb-1"><?php echo htmlspecialchars($pc['curso_titulo']); ?></div>
                            <div class="text-white text-sm font-bold truncate"><?php echo htmlspecialchars($pc['leccion_titulo']); ?></div>
                            <div class="text-[10px] text-stone-600 font-mono mt-1">Siguiente clase disponible · <?php echo (int)$pc['completadas']; ?>/<?php echo (int)$pc['total_lecciones']; ?> completadas</div>
                        </div>
                        <a href="curso/<?php echo $pc['curso_id']; ?>/leccion/<?php echo $pc['leccion_id']; ?>" class="btn-explorar shrink-0" style="padding:0.5rem 1.1rem;font-size:0.6rem;">CONTINUAR</a>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>

            <!-- Actividades pendientes por hacer -->
            <?php if (!empty($actividadesPendientes)): ?>
            <div class="mb-6">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="font-['Orbitron'] text-white text-sm font-bold"><span class="text-accent">🎯</span> ACTIVIDADES PENDIENTES POR HACER</h3>
                </div>
                <div class="bg-[var(--panel-bg)] border border-[var(--border-color)] rounded-xl divide-y divide-white/5">
                    <?php foreach ($actividadesPendientes as $ap):
                        $badgeEstado = $ap['entrega_estado'] === 'pendiente' ? 'text-yellow-400' : 'text-stone-500';
                        $etiqueta = $ap['entrega_estado'] === 'pendiente' ? 'EN REVISIÓN' : 'SIN ENTREGAR';
                    ?>
                    <div class="flex items-center justify-between gap-3 px-5 py-3">
                        <div class="min-w-0">
                            <div class="text-white text-xs font-bold truncate"><?php echo htmlspecialchars($ap['actividad_titulo']); ?></div>
                            <div class="text-[10px] text-stone-500 font-mono mt-0.5 truncate"><?php echo htmlspecialchars($ap['curso_titulo']); ?> · <?php echo htmlspecialchars($ap['leccion_titulo']); ?></div>
                        </div>
                        <div class="flex items-center gap-3 shrink-0">
                            <span class="text-[10px] font-mono <?php echo $badgeEstado; ?>"><?php echo $etiqueta; ?></span>
                            <a href="curso/<?php echo $ap['curso_id']; ?>/leccion/<?php echo $ap['leccion_id']; ?>" class="text-accent hover:text-white text-[10px] font-mono transition-colors">IR →</a>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>

            <!-- Últimas calificaciones -->
            <?php if (!empty($calificaciones)): ?>
            <div class="mb-6">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="font-['Orbitron'] text-white text-sm font-bold"><span class="text-accent">🏆</span> ÚLTIMAS CALIFICACIONES</h3>
                    <a href="perfil" class="text-[10px] font-mono text-stone-500 hover:text-accent transition-colors">VER TODO EL PROGRESO →</a>
                </div>
                <div class="bg-[var(--panel-bg)] border border-[var(--border-color)] rounded-xl divide-y divide-white/5">
                    <?php foreach ($calificaciones as $cal): ?>
                    <div class="px-5 py-3">
                        <div class="flex items-center justify-between gap-3">
                            <div class="min-w-0">
                                <div class="text-white text-sm font-bold truncate"><?php echo htmlspecialchars($cal['actividad_titulo']); ?></div>
                                <div class="text-[10px] text-stone-500 font-mono mt-0.5"><?php echo htmlspecialchars($cal['curso_titulo']); ?> · <?php echo date('d/m/Y', strtotime($cal['fecha_revision'])); ?></div>
                            </div>
                            <div class="text-lg font-bold font-['Orbitron'] text-green-400 shrink-0"><?php echo (float)$cal['calificacion']; ?></div>
                        </div>
                        <?php if (!empty($cal['comentario_profesor'])): ?>
                        <div class="text-xs text-stone-400 font-mono mt-2 bg-white/5 border border-white/10 rounded-lg px-3 py-2">
                            💬 <?php echo htmlspecialchars($cal['comentario_profesor']); ?>
                        </div>
                        <?php endif; ?>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>

            <div class="flex items-center gap-3 mb-3">
                <h3 class="font-['Orbitron'] text-white text-sm font-bold"><span class="text-accent">📚</span> TUS CURSOS</h3>
                <?php if (count($misCursos) > 0): ?>
                <span class="text-[10px] text-stone-600 font-mono">progreso general promedio: <?php echo $progresoPromedio; ?>%</span>
                <?php endif; ?>
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
        <?php require 'partials/chatbot.php'; ?>
    </div>

    <script src="js/matrix-rain.js"></script>
</body>
</html>
