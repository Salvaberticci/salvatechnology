<?php
require_once __DIR__ . '/config/db.php';

if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_rol'] !== 'estudiante') {
    header('Location: academia');
    exit;
}

$usuarioId = $_SESSION['usuario_id'];
$cursoId = (int)($_GET['id'] ?? 0);
$leccionId = (int)($_GET['leccion'] ?? 0);

$stmt = $pdo->prepare("SELECT c.*, i.tipo as inscripcion_tipo FROM cursos c JOIN inscripciones i ON i.curso_id = c.id AND i.usuario_id = ? WHERE c.id = ? AND i.estado = 'activa'");
$stmt->execute([$usuarioId, $cursoId]);
$curso = $stmt->fetch();

if (!$curso) {
    die("Curso no encontrado o no tienes acceso");
}

$stmt = $pdo->prepare("SELECT * FROM lecciones WHERE curso_id = ? ORDER BY orden ASC");
$stmt->execute([$cursoId]);
$lecciones = $stmt->fetchAll();

if (empty($lecciones)) {
    die("Este curso aún no tiene lecciones");
}

if (!$leccionId) {
    $leccionId = $lecciones[0]['id'];
}

$stmt = $pdo->prepare("SELECT * FROM lecciones WHERE id = ? AND curso_id = ?");
$stmt->execute([$leccionId, $cursoId]);
$leccionActual = $stmt->fetch();

if (!$leccionActual) {
    $leccionActual = $lecciones[0];
    $leccionId = $lecciones[0]['id'];
}

$stmt = $pdo->prepare("SELECT * FROM actividades WHERE leccion_id = ?");
$stmt->execute([$leccionId]);
$actividades = $stmt->fetchAll();

$entregas = [];
foreach ($actividades as $act) {
    $stmt = $pdo->prepare("SELECT * FROM entregas WHERE actividad_id = ? AND usuario_id = ?");
    $stmt->execute([$act['id'], $usuarioId]);
    $entrega = $stmt->fetch();
    $entregas[$act['id']] = $entrega;
}

$stmt = $pdo->prepare("SELECT COUNT(*) FROM progreso_lecciones WHERE usuario_id = ? AND leccion_id = ? AND completado = 1");
$stmt->execute([$usuarioId, $leccionId]);
$leccionCompletada = $stmt->fetchColumn() > 0;

if (!$leccionCompletada && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['completar_leccion'])) {
    $stmt = $pdo->prepare("INSERT INTO progreso_lecciones (usuario_id, leccion_id, completado) VALUES (?, ?, 1) ON DUPLICATE KEY UPDATE completado = 1");
    $stmt->execute([$usuarioId, $leccionId]);
    $leccionCompletada = true;
}

$progresoTotal = count($lecciones) > 0 ? round((array_sum(array_map(function($l) use ($usuarioId, $pdo) {
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM progreso_lecciones WHERE usuario_id = ? AND leccion_id = ? AND completado = 1");
    $stmt->execute([$usuarioId, $l['id']]);
    return $stmt->fetchColumn();
}, $lecciones)) / count($lecciones)) * 100) : 0;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($leccionActual['titulo']); ?> | <?php echo htmlspecialchars($curso['titulo']); ?></title>
    <link rel="stylesheet" href="css/dashboard.css">
    <base href="/salvatechnology/">
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
        .lesson-content { line-height: 1.8; }
        .lesson-content h1, .lesson-content h2, .lesson-content h3 { color: #fff; margin: 1.5rem 0 0.5rem; font-family: 'Orbitron', sans-serif; }
        .lesson-content p { color: #aaa; margin-bottom: 1rem; }
        .lesson-content ul, .lesson-content ol { color: #aaa; padding-left: 1.5rem; margin-bottom: 1rem; }
        .lesson-content li { margin-bottom: 0.3rem; }
        .lesson-content code { background: rgba(255,140,0,0.1); padding: 2px 6px; border-radius: 4px; color: var(--accent); font-size: 0.85em; }
        .lesson-content pre { background: #111; border: 1px solid #222; border-radius: 8px; padding: 1rem; overflow-x: auto; margin-bottom: 1rem; }
        .lesson-content pre code { background: none; padding: 0; }
        .lesson-content img { max-width: 100%; border-radius: 8px; margin: 1rem 0; }
        .lesson-content a { color: var(--accent); text-decoration: underline; }
    </style>
</head>
<body class="dashboard-body">
    <div class="scanlines"></div>
    <div class="dashboard-layout">
        <aside class="dash-sidebar">
            <div class="logo-side">
                <a href="/salvatechnology/"><img src="img/logo.png" alt="Salva Technology"></a>
            </div>
            <div class="user-badge">
                <div class="avatar"><?php echo strtoupper(substr($_SESSION['usuario_nombre'], 0, 1)); ?></div>
                <div class="name"><?php echo htmlspecialchars($_SESSION['usuario_nombre']); ?></div>
                <span class="plan-badge plan-<?php echo $_SESSION['usuario_plan']; ?>"><?php echo $_SESSION['usuario_plan'] === 'suscripcion' ? 'SUSCRIPCIÓN' : 'GRATUITO'; ?></span>
            </div>
            <nav class="dash-nav">
                <a href="dashboard">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>Dashboard
                </a>
                <a href="cursos">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>Explorar
                </a>
            </nav>
        </aside>

        <main class="dash-main" style="max-width:100%;padding-right:0;">
            <div class="dash-header">
                <div>
                    <h1 style="font-size:1rem;"><?php echo htmlspecialchars($curso['titulo']); ?></h1>
                    <div class="flex items-center gap-3 mt-2">
                        <div class="progress-bar-track" style="width:200px;height:4px;">
                            <div class="progress-bar-fill" style="width:<?php echo $progresoTotal; ?>%"></div>
                        </div>
                        <span class="text-stone-500 text-[10px] font-mono"><?php echo $progresoTotal; ?>%</span>
                    </div>
                </div>
                <a href="dashboard" class="text-stone-500 hover:text-accent transition-colors text-xs font-mono">← Volver</a>
            </div>

            <div class="course-viewer">
                <div>
                    <div class="video-container">
                        <?php if ($leccionActual['video_url']): ?>
                            <?php if (strpos($leccionActual['video_url'], 'youtube.com') !== false || strpos($leccionActual['video_url'], 'youtu.be') !== false): ?>
                                <?php
                                $videoId = '';
                                if (preg_match('/(?:youtube\.com\/(?:embed\/|watch\?v=|v\/)|youtu\.be\/)([a-zA-Z0-9_-]+)/', $leccionActual['video_url'], $matches)) {
                                    $videoId = $matches[1];
                                }
                                ?>
                                <iframe src="https://www.youtube.com/embed/<?php echo $videoId; ?>" frameborder="0" allowfullscreen></iframe>
                            <?php elseif (strpos($leccionActual['video_url'], 'bunny.net') !== false || strpos($leccionActual['video_url'], ' Bunny') !== false): ?>
                                <iframe src="<?php echo htmlspecialchars($leccionActual['video_url']); ?>" frameborder="0" allowfullscreen></iframe>
                            <?php else: ?>
                                <video controls>
                                    <source src="<?php echo htmlspecialchars($leccionActual['video_url']); ?>" type="video/mp4">
                                </video>
                            <?php endif; ?>
                        <?php else: ?>
                            <div style="display:flex;align-items:center;justify-content:center;aspect-ratio:16/9;background:#111;color:#333;font-family:'Orbitron',sans-serif;font-size:0.8rem;">
                                VIDEO NO DISPONIBLE
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="p-4 bg-[var(--panel-bg)] border border-[var(--border-color)] rounded-b-xl">
                        <h2 class="font-['Orbitron'] text-white text-lg font-bold mb-2"><?php echo htmlspecialchars($leccionActual['titulo']); ?></h2>
                        <?php if ($leccionActual['descripcion']): ?>
                        <div class="lesson-content text-sm"><?php echo nl2br(htmlspecialchars($leccionActual['descripcion'])); ?></div>
                        <?php endif; ?>

                        <form method="POST" class="mt-4">
                            <button type="submit" name="completar_leccion" value="1" class="btn-continuar <?php echo $leccionCompletada ? 'opacity-50 pointer-events-none' : ''; ?>">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <?php echo $leccionCompletada ? 'COMPLETADA' : 'MARCAR COMO COMPLETADA'; ?>
                            </button>
                        </form>
                    </div>

                    <?php if (!empty($actividades)): ?>
                    <div class="activity-section">
                        <h3>ACTIVIDAD PRÁCTICA</h3>
                        <?php foreach ($actividades as $act): $entrega = $entregas[$act['id']] ?? null; ?>
                        <div class="bg-white/5 rounded-xl p-5 mb-4 border border-white/5">
                            <div class="flex justify-between items-start mb-3">
                                <div>
                                    <h4 class="text-white font-bold text-sm"><?php echo htmlspecialchars($act['titulo']); ?></h4>
                                    <?php if ($act['descripcion']): ?>
                                    <p class="text-stone-400 text-xs mt-1"><?php echo nl2br(htmlspecialchars($act['descripcion'])); ?></p>
                                    <?php endif; ?>
                                </div>
                                <?php if ($entrega): ?>
                                <span class="activity-status status-<?php echo $entrega['estado']; ?>">
                                    <?php echo strtoupper($entrega['estado']); ?>
                                </span>
                                <?php endif; ?>
                            </div>

                            <?php if ($entrega): ?>
                                <div class="bg-black/30 rounded-lg p-3 mt-3 text-xs text-stone-400">
                                    <?php if ($entrega['archivo_url']): ?>
                                    <p>Archivo: <a href="<?php echo htmlspecialchars($entrega['archivo_url']); ?>" class="text-accent underline" target="_blank">Ver archivo</a></p>
                                    <?php endif; ?>
                                    <?php if ($entrega['respuesta_texto']): ?>
                                    <p>Respuesta: <?php echo nl2br(htmlspecialchars($entrega['respuesta_texto'])); ?></p>
                                    <?php endif; ?>
                                    <?php if ($entrega['link_url']): ?>
                                    <p>Link: <a href="<?php echo htmlspecialchars($entrega['link_url']); ?>" class="text-accent underline" target="_blank"><?php echo htmlspecialchars($entrega['link_url']); ?></a></p>
                                    <?php endif; ?>
                                    <?php if ($entrega['calificacion'] !== null): ?>
                                    <p class="mt-1 text-white">Calificación: <strong><?php echo $entrega['calificacion']; ?>/100</strong></p>
                                    <?php endif; ?>
                                    <?php if ($entrega['comentario_profesor']): ?>
                                    <p class="mt-1">Comentario: <?php echo nl2br(htmlspecialchars($entrega['comentario_profesor'])); ?></p>
                                    <?php endif; ?>
                                </div>
                            <?php else: ?>
                                <form action="entregar" method="POST" enctype="multipart/form-data" class="mt-3 space-y-3">
                                    <input type="hidden" name="actividad_id" value="<?php echo $act['id']; ?>">
                                    <input type="hidden" name="curso_id" value="<?php echo $cursoId; ?>">
                                    
                                    <?php if ($act['tipo'] === 'subir_archivo'): ?>
                                    <div>
                                        <label class="text-[10px] uppercase text-stone-500 font-mono block mb-1">Subir archivo</label>
                                        <input type="file" name="archivo" class="w-full text-xs text-stone-400 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-accent file:text-black file:font-bold file:text-xs hover:file:bg-orange-600 transition-all">
                                    </div>
                                    <?php elseif ($act['tipo'] === 'link'): ?>
                                    <div>
                                        <label class="text-[10px] uppercase text-stone-500 font-mono block mb-1">Link de tu trabajo</label>
                                        <input type="url" name="link_url" placeholder="https://..." class="w-full px-4 py-3 bg-black/50 border border-white/10 rounded-lg text-white text-sm font-mono outline-none focus:border-accent transition-colors">
                                    </div>
                                    <?php else: ?>
                                    <div>
                                        <label class="text-[10px] uppercase text-stone-500 font-mono block mb-1">Tu respuesta</label>
                                        <textarea name="respuesta_texto" rows="4" class="w-full px-4 py-3 bg-black/50 border border-white/10 rounded-lg text-white text-sm font-mono outline-none focus:border-accent transition-colors" placeholder="Escribe tu respuesta aquí..."></textarea>
                                    </div>
                                    <?php endif; ?>
                                    
                                    <button type="submit" class="btn-explorar">ENTREGAR ACTIVIDAD</button>
                                </form>
                            <?php endif; ?>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                </div>

                <div class="lesson-sidebar">
                    <h3>LECCIONES</h3>
                    <?php foreach ($lecciones as $i => $lec):
                        $stmt = $pdo->prepare("SELECT COUNT(*) FROM progreso_lecciones WHERE usuario_id = ? AND leccion_id = ? AND completado = 1");
                        $stmt->execute([$usuarioId, $lec['id']]);
                        $comp = $stmt->fetchColumn() > 0;
                        $isActive = $lec['id'] == $leccionId;
                    ?>
                    <a href="curso/<?php echo $cursoId; ?>/leccion/<?php echo $lec['id']; ?>" class="lesson-item <?php echo $isActive ? 'active' : ''; ?> <?php echo $comp ? 'completed' : ''; ?>">
                        <div class="lesson-num">
                            <?php if ($comp): ?>
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                            <?php else: echo str_pad($i + 1, 2, '0', STR_PAD_LEFT); endif; ?>
                        </div>
                        <div class="lesson-info">
                            <div class="lesson-title"><?php echo htmlspecialchars($lec['titulo']); ?></div>
                            <div class="lesson-status"><?php echo $comp ? 'Completada' : ($isActive ? 'En curso' : 'Pendiente'); ?></div>
                        </div>
                    </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
