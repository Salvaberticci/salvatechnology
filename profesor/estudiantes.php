<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../helpers/config_sistema.php';

if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_rol'] !== 'profesor') {
    header('Location: ' . BASE_URL . 'academia');
    exit;
}

$search = $_GET['search'] ?? '';

$sql = "SELECT u.*, 
    (SELECT COUNT(*) FROM inscripciones WHERE usuario_id = u.id AND estado = 'activa') as cursos_activos,
    (SELECT COUNT(*) FROM entregas e JOIN actividades a ON e.actividad_id = a.id JOIN lecciones l ON a.leccion_id = l.id JOIN cursos c ON l.curso_id = c.id WHERE e.usuario_id = u.id AND e.estado = 'pendiente') as entregas_pendientes
    FROM usuarios u WHERE u.rol = 'estudiante'";
$params = [];

if (!empty($search)) {
    $sql .= " AND (u.nombre LIKE ? OR u.email LIKE ? OR u.pais LIKE ?)";
    $params = ["%$search%", "%$search%", "%$search%"];
}
$sql .= " ORDER BY u.creado_en DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$estudiantes = $stmt->fetchAll();

$suscripcionesActivas = $pdo->prepare("SELECT COUNT(*) FROM usuarios WHERE plan = 'suscripcion' AND (suscripcion_expira IS NULL OR suscripcion_expira >= CURDATE()) AND rol = 'estudiante'");
$suscripcionesActivas->execute();
$totalSuscripciones = $suscripcionesActivas->fetchColumn();

// ============================================================
// Endpoint AJAX: perfil completo del estudiante (modal)
// ============================================================
if (isset($_GET['ajax']) && $_GET['ajax'] === 'perfil') {
    header('Content-Type: application/json');
    $estId = (int)($_GET['id'] ?? 0);
    if ($estId <= 0) {
        echo json_encode(['error' => 'Estudiante inválido']);
        exit;
    }

    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE id = ? AND rol = 'estudiante'");
    $stmt->execute([$estId]);
    $est = $stmt->fetch();
    if (!$est) {
        echo json_encode(['error' => 'Estudiante no encontrado']);
        exit;
    }
    $profesorId = (int)$_SESSION['usuario_id'];
    $e = function($v) { return htmlspecialchars((string)$v); };

    // XP y nivel (misma fórmula que el perfil del estudiante)
    $stmt = $pdo->prepare("SELECT COALESCE(SUM(xp),0) AS xp, COALESCE(SUM(quiz_aciertos),0) AS quiz, COUNT(*) AS ebooks FROM ebook_progreso WHERE usuario_id = ?");
    $stmt->execute([$estId]);
    $ep = $stmt->fetch();
    $xpEbooks = (int)$ep['xp'];
    $quizAciertos = (int)$ep['quiz'];
    $ebooksLeidos = (int)$ep['ebooks'];

    $stmt = $pdo->prepare("SELECT COUNT(*) FROM progreso_lecciones WHERE usuario_id = ? AND completado = 1");
    $stmt->execute([$estId]);
    $clasesCompletadas = (int)$stmt->fetchColumn();

    $stmt = $pdo->prepare("SELECT COUNT(*) FROM entregas WHERE usuario_id = ? AND estado = 'aprobado'");
    $stmt->execute([$estId]);
    $entregasAprobadas = (int)$stmt->fetchColumn();

    $xpTotal = $xpEbooks + $clasesCompletadas * 10 + $entregasAprobadas * 20 + $quizAciertos * 5;
    $nivelTotal = floor($xpTotal / 100) + 1;
    $xpNivelActual = $xpTotal % 100;

    $stmt = $pdo->prepare("SELECT COUNT(*) FROM entregas WHERE usuario_id = ?");
    $stmt->execute([$estId]);
    $entregasEnviadas = (int)$stmt->fetchColumn();

    // Plan y último pago completado
    $planLabel = 'Gratuito';
    $ultimoPago = null;
    $stmt = $pdo->prepare("SELECT * FROM pagos WHERE usuario_id = ? AND estado = 'completado' ORDER BY fecha_pago DESC LIMIT 1");
    $stmt->execute([$estId]);
    $ultimoPago = $stmt->fetch();
    if ($est['plan'] === 'suscripcion') {
        $mapaMeses = [40 => '1 Mes', 110 => '3 Meses', 190 => '6 Meses', 380 => '1 Año'];
        $planLabel = $mapaMeses[(int)($ultimoPago['monto'] ?? 0)] ?? 'Suscripción';
    }

    // Cursos inscritos (solo de este profesor)
    $stmt = $pdo->prepare("SELECT c.id, c.titulo, c.imagen, i.tipo AS inscripcion_tipo, i.estado AS inscripcion_estado, i.fecha_inscripcion, i.fecha_pago,
        (SELECT COUNT(*) FROM progreso_lecciones pl JOIN lecciones l ON pl.leccion_id = l.id WHERE l.curso_id = c.id AND pl.usuario_id = ? AND pl.completado = 1) AS completadas,
        (SELECT COUNT(*) FROM lecciones WHERE curso_id = c.id) AS total_lecciones
        FROM inscripciones i JOIN cursos c ON i.curso_id = c.id
        WHERE i.usuario_id = ? AND c.profesor_id = ? AND i.estado = 'activa'
        ORDER BY i.fecha_inscripcion DESC");
    $stmt->execute([$estId, $estId, $profesorId]);
    $cursos = $stmt->fetchAll();

    // Entregas de actividades (solo de este profesor)
    $stmt = $pdo->prepare("SELECT e.*, a.titulo AS actividad_titulo, l.titulo AS leccion_titulo, c.titulo AS curso_titulo
        FROM entregas e
        JOIN actividades a ON e.actividad_id = a.id
        JOIN lecciones l ON a.leccion_id = l.id
        JOIN cursos c ON l.curso_id = c.id
        WHERE e.usuario_id = ? AND c.profesor_id = ?
        ORDER BY e.fecha_entrega DESC");
    $stmt->execute([$estId, $profesorId]);
    $entregas = $stmt->fetchAll();

    $avatarHtml = $est['avatar']
        ? '<img src="' . $e($est['avatar']) . '" alt="Foto" class="avatar-img" style="width:64px;height:64px;border-radius:12px;object-fit:cover;">'
        : '<div class="avatar" style="width:64px;height:64px;font-size:1.4rem;background:rgba(255,140,0,0.15);color:var(--accent);border:1px solid var(--accent);">' . strtoupper(substr($est['nombre'], 0, 1)) . '</div>';

    $planBadge = $est['plan'] === 'suscripcion'
        ? '<span class="badge badge-suscripcion">' . $e(($planLabel === 'Gratuito' || $planLabel === 'Suscripción') ? strtoupper($planLabel) : 'SUSCRIPCIÓN · ' . strtoupper($planLabel)) . '</span>'
        : '<span class="badge badge-gratis">GRATUITO</span>';

    // Bloque del plan pagado
    $planHtml = '';
    if ($est['plan'] === 'suscripcion' && $ultimoPago) {
        $planHtml .= '<div class="text-stone-400"><span class="text-accent">' . $e($planLabel) . '</span> · $' . $e(number_format((float)$ultimoPago['monto'], 2)) . '</div>';
        if (!empty($ultimoPago['metodo_pago'])) $planHtml .= '<div class="text-stone-500 text-xs">Método: ' . $e($ultimoPago['metodo_pago']) . ' · Ref: ' . $e($ultimoPago['referencia'] ?? '—') . '</div>';
    } elseif ($est['plan'] !== 'suscripcion' && $ultimoPago && $ultimoPago['tipo'] === 'curso_individual') {
        $planHtml .= '<div class="text-stone-400">Compró un curso por $' . number_format((float)$ultimoPago['monto'], 2) . ' (' . $e($ultimoPago['metodo_pago'] ?? '—') . ')</div>';
    } else {
        $planHtml .= '<div class="text-stone-400">Sin pagos completados registrados.</div>';
    }
    if ($est['suscripcion_expira']) {
        $planHtml .= '<div class="text-stone-500 text-xs mt-1">Suscripción válida hasta: ' . $e(date('d/m/Y', strtotime($est['suscripcion_expira']))) . '</div>';
    }

    // Cursos toggle HTML
    $cursosHtml = '';
    if ($cursos) {
        foreach ($cursos as $c) {
            $pct = ($c['total_lecciones'] > 0) ? round(100 * $c['completadas'] / $c['total_lecciones']) : 0;
            $tipoBadge = $c['inscripcion_tipo'] === 'suscripcion' ? '<span class="badge badge-suscripcion">SUSCRIPCIÓN</span>'
                       : ($c['inscripcion_tipo'] === 'pago' ? '<span class="badge badge-pagado">PAGADO</span>'
                       : '<span class="badge badge-gratis">GRATUITO</span>');
            $cursosHtml .= '<div style="padding:0.75rem 1rem;" class="bg-black/30 rounded-lg border border-white/5 mb-2">
                <div class="flex justify-between items-center gap-2 flex-wrap">
                    <div class="text-sm text-white font-semibold">' . $e($c['titulo']) . '</div>
                    ' . $tipoBadge . '
                </div>
                <div style="font-size:0.65rem;" class="text-stone-500 font-mono mt-1">Inscrito: ' . date('d/m/Y', strtotime($c['fecha_inscripcion'])) . ($c['fecha_pago'] ? ' · Pagado: ' . date('d/m/Y', strtotime($c['fecha_pago'])) : '') . '</div>
                <div style="height:6px;" class="bg-white/10 rounded-full mt-2 overflow-hidden"><div style="width:' . $pct . '%;height:100%;" class="bg-accent"></div></div>
                <div style="font-size:0.6rem;" class="text-stone-500 font-mono mt-1">' . (int)$c['completadas'] . '/' . (int)$c['total_lecciones'] . ' clases · ' . $pct . '%</div>
            </div>';
        }
    } else {
        $cursosHtml = '<div class="text-stone-500 text-sm">Este estudiante no tiene cursos activos.</div>';
    }

    // Entregas HTML
    $entregasHtml = '';
    if ($entregas) {
        foreach ($entregas as $en) {
            $statusClass = 'status-' . $en['estado'];
            $estadoTxt = strtoupper($en['estado']);
            $entregasHtml .= '<div style="padding:0.7rem 1rem;" class="bg-black/30 border border-white/5 rounded-lg mb-2">
                <div class="flex justify-between items-center gap-2 flex-wrap">
                    <div class="text-sm text-white">' . $e($en['actividad_titulo']) . '</div>
                    <span class="activity-status ' . $statusClass . '">' . $estadoTxt . '</span>
                </div>
                <div style="font-size:0.65rem;" class="text-stone-500 font-mono mt-1">' . $e($en['leccion_titulo']) . ' · ' . $e($en['curso_titulo']) . '</div>
                <div style="font-size:0.65rem;" class="text-stone-500 font-mono mt-1">Entregado: ' . date('d/m/Y H:i', strtotime($en['fecha_entrega'])) . ($en['fecha_revision'] ? ' · Revisado: ' . date('d/m/Y H:i', strtotime($en['fecha_revision'])) : '') . '</div>
                ' . ($en['calificacion'] !== null ? '<div style="font-size:0.75rem;" class="text-white mt-1">Calificación: <strong>' . $e($en['calificacion']) . '/100</strong></div>' : '')
                . ($en['comentario_profesor'] ? '<div style="font-size:0.75rem;" class="text-stone-300 mt-1">Comentario: ' . nl2br($e($en['comentario_profesor'])) . '</div>' : '')
                . ($en['link_url'] ? '<div style="font-size:0.7rem;" class="mt-1"><a style="color:var(--accent);text-decoration:underline;" href="' . $e($en['link_url']) . '" target="_blank" rel="noopener">Ver link de entrega</a></div>' : '')
                . '</div>';
        }
    } else {
        $entregasHtml = '<div class="text-stone-500 text-sm">Este estudiante no ha enviado entregas aún.</div>';
    }

    $html = '
    <div class="modal-overlay-perfil" id="perfil-overlay" style="position:fixed;inset:0;background:rgba(0,0,0,0.75);backdrop-filter:blur(6px);z-index:9999;display:flex;align-items:center;justify-content:center;padding:1rem;">
        <div style="background:var(--panel-bg,#0f0f12);border:1px solid rgba(255,140,0,0.25);border-radius:16px;max-width:680px;width:100%;max-height:88vh;overflow-y:auto;box-shadow:0 20px 60px rgba(0,0,0,0.7);" class="dashboard-card" role="dialog" aria-modal="true">
            <div style="padding:1.25rem 1.5rem;border-bottom:1px solid rgba(255,255,255,0.07);display:flex;justify-content:space-between;align-items:center;position:sticky;top:0;background:inherit;z-index:2;">
                <h2 style="font-size:0.95rem;letter-spacing:0.05em;color:#fff;"><span style="color:var(--accent);">PERFIL</span> DE ESTUDIANTE</h2>
                <button type="button" onclick="cerrarPerfilEstudiante()" style="background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.1);color:#fff;border-radius:8px;width:32px;height:32px;cursor:pointer;font-size:1rem;">&times;</button>
            </div>
            <div style="padding:1.5rem;">
                <div style="display:flex;gap:1rem;align-items:center;" class="mb-4">
                    ' . $avatarHtml . '
                    <div>
                        <div style="font-size:1.1rem;color:#fff;font-weight:700;">' . $e($est['nombre']) . '</div>
                        <div style="font-size:0.75rem;color:#a8a29e;font-family:monospace;">' . $e($est['email']) . '</div>
                        <div style="margin-top:0.4rem;display:flex;gap:0.5rem;flex-wrap:wrap;">' . $planBadge . '</div>
                    </div>
                </div>

                <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(150px,1fr));gap:0.75rem;" class="mb-5">
                    <div style="padding:0.7rem 0.9rem;background:rgba(255,255,255,0.03);border:1px solid rgba(255,255,255,0.07);border-radius:10px;">
                        <div style="font-size:0.6rem;color:#78716c;font-family:monospace;text-transform:uppercase;">Registrado desde</div>
                        <div style="font-size:0.8rem;color:#fff;margin-top:0.2rem;">' . date('d/m/Y', strtotime($est['creado_en'])) . '</div>
                    </div>
                    <div style="padding:0.7rem 0.9rem;background:rgba(255,255,255,0.03);border:1px solid rgba(255,255,255,0.07);border-radius:10px;">
                        <div style="font-size:0.6rem;color:#78716c;font-family:monospace;text-transform:uppercase;">País</div>
                        <div style="font-size:0.8rem;color:#fff;margin-top:0.2rem;">' . ($est['pais'] ? $e($est['pais']) : '—') . '</div>
                    </div>
                    <div style="padding:0.7rem 0.9rem;background:rgba(255,255,255,0.03);border:1px solid rgba(255,255,255,0.07);border-radius:10px;">
                        <div style="font-size:0.6rem;color:#78716c;font-family:monospace;text-transform:uppercase;">Teléfono</div>
                        <div style="font-size:0.8rem;color:#fff;margin-top:0.2rem;">' . $e($est['telefono'] ?: '—') . '</div>
                    </div>
                    <div style="padding:0.7rem 0.9rem;background:rgba(255,255,255,0.03);border:1px solid rgba(255,255,255,0.07);border-radius:10px;">
                        <div style="font-size:0.6rem;color:#78716c;font-family:monospace;text-transform:uppercase;">Entregas enviadas</div>
                        <div style="font-size:0.8rem;color:#fff;margin-top:0.2rem;">' . $entregasEnviadas . '</div>
                    </div>
                </div>

                <div style="margin-bottom:1.4rem;">
                    <div style="font-size:0.7rem;color:#a8a29e;font-family:monospace;text-transform:uppercase;letter-spacing:0.08em;margin-bottom:0.5rem;">Nivel de experiencia</div>
                    <div style="display:flex;justify-content:space-between;align-items:baseline;margin-bottom:0.35rem;">
                        <span style="color:#fff;font-size:0.85rem;">Nivel <strong style="color:var(--accent);">' . $nivelTotal . '</strong></span>
                        <span style="color:var(--accent);font-size:0.7rem;font-family:monospace;">' . $xpTotal . ' XP</span>
                    </div>
                    <div style="height:8px;background:rgba(255,255,255,0.08);border-radius:99px;overflow:hidden;">
                        <div style="width:' . $xpNivelActual . '%;height:100%;background:linear-gradient(90deg,#ff8c00,#ffd166);border-radius:99px;"></div>
                    </div>
                    <div style="font-size:0.65rem;color:#78716c;font-family:monospace;margin-top:0.3rem;">' . $clasesCompletadas . ' clases · ' . $ebooksLeidos . ' ebooks · ' . $quizAciertos . ' quizzes acertados</div>
                </div>

                <div style="margin-bottom:1.4rem;">
                    <div style="font-size:0.7rem;color:#a8a29e;font-family:monospace;text-transform:uppercase;letter-spacing:0.08em;margin-bottom:0.5rem;">Plan</div>
                    ' . $planHtml . '
                </div>

                <div style="margin-bottom:1.4rem;">
                    <div style="font-size:0.7rem;color:#a8a29e;font-family:monospace;text-transform:uppercase;letter-spacing:0.08em;margin-bottom:0.5rem;">Cursos inscritos (' . count($cursos) . ')</div>
                    ' . $cursosHtml . '
                </div>

                <div>
                    <div style="font-size:0.7rem;color:#a8a29e;font-family:monospace;text-transform:uppercase;letter-spacing:0.08em;margin-bottom:0.5rem;">Entregas de actividades (' . count($entregas) . ')</div>
                    ' . $entregasHtml . '
                </div>

                <div style="margin-top:1.5rem;display:flex;justify-content:flex-end;">
                    <a href="profesor/entregas?estudiante_id=' . $e($estId) . '" style="color:var(--accent);text-decoration:underline;font-size:0.75rem;font-family:monospace;">Revisar sus entregas →</a>
                </div>
            </div>
        </div>
    </div>';

    echo json_encode(['ok' => true, 'html' => $html]);
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estudiantes | Profesor</title>
    <base href="<?= BASE_URL ?>">
    <link rel="icon" type="image/png" href="img/logo.png">
    <link rel="stylesheet" href="css/dashboard.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>tailwind.config={theme:{extend:{colors:{'accent':'#ff8c00','dark-bg':'#0a0a0a'}}}}</script>
</head>
<body class="dashboard-body">
    <div class="scanlines"></div>
    <div class="dashboard-layout">
        <aside class="dash-sidebar">
            <div class="logo-side"><a href="./"><img src="img/logo.png" alt="Salva"></a></div>
            <div class="user-badge">
                <div class="avatar" style="background:#ff4444;color:#fff;"><?php echo strtoupper(substr($_SESSION['usuario_nombre'], 0, 1)); ?></div>
                <div class="name"><?php echo htmlspecialchars($_SESSION['usuario_nombre']); ?></div>
                <span class="plan-badge" style="background:rgba(255,68,68,0.15);color:#ff4444;border-color:rgba(255,68,68,0.3);">PROFESOR</span>
            </div>
            <nav class="dash-nav">
                <a href="profesor"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6z"/></svg>Dashboard</a>
                <a href="profesor/cursos"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253"/></svg>Cursos</a>
                <a href="profesor/estudiantes" class="active"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857"/></svg>Estudiantes</a>
                <a href="profesor/entregas"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>Entregas</a>
                <a href="profesor/pagos"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>Pagos</a>
                <a href="profesor/perfil"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>Mi Perfil</a>
                <?php if (esAdmin()): ?>
                <a href="profesor/admin"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.8 0-3 .6-3 1.5v.2c0 .5.3 1 .8 1.3.5.3 1.2.4 2 .4h.2c.8 0 1.5-.1 2-.4.5-.3.8-.8.8-1.3v-.2C15 8.6 13.8 8 12 8zm0 6c-2.5 0-4.5-1-4.5-3.5 0-2.5 2-3.8 4.5-3.8s4.5 1.3 4.5 3.8C16.5 13 14.5 14 12 14zm5 4.5c0-2.2-2.2-4-5-4s-5 1.8-5 4H17z"/></svg>Panel Admin</a>
                <?php endif; ?>
                <a href="logout"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>Cerrar</a>
            </nav>
        </aside>

        <main class="dash-main">
            <div class="dash-header">
                <h1>Gestionar <span>Estudiantes</span></h1>
                <span class="text-stone-600 text-xs font-mono"><?php echo count($estudiantes); ?> registrados · <?php echo $totalSuscripciones; ?> suscripciones</span>
            </div>

            <form method="GET" class="mb-6">
                <div class="flex gap-2">
                    <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>" placeholder="Buscar por nombre, email o país..." class="flex-1 px-4 py-3 rounded-xl text-white font-mono text-sm bg-white/5 border border-white/10 outline-none focus:border-accent">
                    <button type="submit" class="btn-continuar">BUSCAR</button>
                </div>
            </form>

            <div class="course-feed">
                <?php foreach ($estudiantes as $e): ?>
                <div class="course-card" style="padding:1rem 1.5rem;">
                    <div class="flex justify-between items-center">
                        <div>
                            <div class="card-title" style="font-size:0.9rem;"><?php echo htmlspecialchars($e['nombre']); ?></div>
                            <div class="card-meta">
                                <span class="text-stone-500 text-xs font-mono"><?php echo htmlspecialchars($e['email']); ?></span>
                                <?php if ($e['pais']): ?>
                                <span class="text-stone-600 text-xs font-mono"><?php echo htmlspecialchars($e['pais']); ?></span>
                                <?php endif; ?>
                                <span class="text-stone-600 text-xs font-mono">Reg: <?php echo date('d/m/Y', strtotime($e['creado_en'])); ?></span>
                            </div>
                            <div class="flex gap-2 mt-2">
                                <span class="badge <?php echo $e['plan'] === 'suscripcion' ? 'badge-suscripcion' : 'badge-gratis'; ?>"><?php echo strtoupper($e['plan']); ?></span>
                                <span class="badge badge-pagado"><?php echo $e['cursos_activos']; ?> cursos</span>
                                <?php if ($e['entregas_pendientes'] > 0): ?>
                                <span class="badge badge-pendiente"><?php echo $e['entregas_pendientes']; ?> entregas</span>
                                <?php endif; ?>
                                <?php if ($e['suscripcion_expira']): ?>
                                <span class="text-stone-600 text-[10px] font-mono">Expira: <?php echo date('d/m/Y', strtotime($e['suscripcion_expira'])); ?></span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="flex gap-2">
                            <button type="button" onclick="abrirPerfilEstudiante(<?php echo (int)$e['id']; ?>)" class="btn-explorar" style="padding:0.4rem 1rem;font-size:0.6rem;cursor:pointer;">VER PERFIL</button>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
                <?php if (empty($estudiantes)): ?>
                <div class="empty-state">
                    <h3>No se encontraron estudiantes</h3>
                </div>
                <?php endif; ?>
            </div>
        </main>
        <?php require __DIR__ . '/../partials/chatbot.php'; ?>
    </div>
    <div id="perfil-modal-container"></div>
    <script>
        async function abrirPerfilEstudiante(id) {
            const base = document.querySelector('base').getAttribute('href');
            const cont = document.getElementById('perfil-modal-container');
            cont.innerHTML = '<div style="position:fixed;inset:0;background:rgba(0,0,0,0.75);z-index:9998;display:flex;align-items:center;justify-content:center;"><div style="color:#fff;font-family:monospace;font-size:0.8rem;">CARGANDO PERFIL...</div></div>';
            try {
                const res = await fetch(base + 'profesor/estudiantes?ajax=perfil&id=' + id + '&t=' + Date.now());
                const data = await res.json();
                if (!data.ok) { cont.innerHTML = ''; alert(data.error || 'No se pudo cargar el perfil'); return; }
                cont.innerHTML = data.html;
                document.addEventListener('keydown', perfilEsc);
            } catch (err) {
                cont.innerHTML = '';
                alert('Error al cargar el perfil: ' + err.message);
            }
        }
        function cerrarPerfilEstudiante() {
            document.getElementById('perfil-modal-container').innerHTML = '';
            document.removeEventListener('keydown', perfilEsc);
        }
        function perfilEsc(ev) {
            if (ev.key === 'Escape') cerrarPerfilEstudiante();
        }
    </script>
</body>
</html>
