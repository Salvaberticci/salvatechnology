<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../config/app.php';

if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_rol'] !== 'profesor') {
    header('Location: ' . BASE_URL . 'academia');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion']) && $_POST['accion'] === 'calificar') {
    $entregaId = (int)$_POST['entrega_id'];
    $calificacion = floatval($_POST['calificacion'] ?? 0);
    $estado = $_POST['estado'] ?? 'pendiente';
    $comentario = trim($_POST['comentario_profesor'] ?? '');

    $stmt = $pdo->prepare("UPDATE entregas SET estado = ?, calificacion = ?, comentario_profesor = ?, fecha_revision = NOW() WHERE id = ?");
    $stmt->execute([$estado, $calificacion, $comentario, $entregaId]);

    header('Location: ' . BASE_URL . 'profesor/entregas');
    exit;
}

$filtro = $_GET['filtro'] ?? 'pendientes';
$estudianteId = (int)($_GET['estudiante_id'] ?? 0);

$sql = "SELECT e.*, a.titulo as actividad_titulo, a.tipo as actividad_tipo, l.titulo as leccion_titulo, c.titulo as curso_titulo, u.nombre as estudiante_nombre, u.email as estudiante_email
    FROM entregas e
    JOIN actividades a ON e.actividad_id = a.id
    JOIN lecciones l ON a.leccion_id = l.id
    JOIN cursos c ON l.curso_id = c.id
    JOIN usuarios u ON e.usuario_id = u.id
    WHERE c.profesor_id = ?";
$params = [$_SESSION['usuario_id']];

if ($filtro === 'pendientes') {
    $sql .= " AND e.estado = 'pendiente'";
} elseif ($filtro === 'calificadas') {
    $sql .= " AND e.estado != 'pendiente'";
}

if ($estudianteId > 0) {
    $sql .= " AND e.usuario_id = ?";
    $params[] = $estudianteId;
}

$sql .= " ORDER BY e.fecha_entrega DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$entregas = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Entregas | Profesor</title>
    <base href="<?= BASE_URL ?>">
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
                <a href="profesor/estudiantes"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857"/></svg>Estudiantes</a>
                <a href="profesor/entregas" class="active"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>Entregas</a>
                <a href="profesor/pagos"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>Pagos</a>
                <a href="logout"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>Cerrar</a>
            </nav>
        </aside>

        <main class="dash-main">
            <div class="dash-header">
                <h1>Revisar <span>Entregas</span></h1>
                <div class="flex gap-2">
                    <a href="profesor/entregas?filtro=pendientes" class="btn-explorar" style="padding:0.4rem 1rem;font-size:0.6rem;">PENDIENTES</a>
                    <a href="profesor/entregas?filtro=calificadas" class="btn-explorar" style="padding:0.4rem 1rem;font-size:0.6rem;">CALIFICADAS</a>
                    <a href="profesor/entregas" class="btn-explorar" style="padding:0.4rem 1rem;font-size:0.6rem;">TODAS</a>
                </div>
            </div>

            <?php if (count($entregas) > 0): ?>
            <div class="course-feed">
                <?php foreach ($entregas as $entrega): ?>
                <div class="course-card">
                    <div class="card-top">
                        <div>
                            <div class="card-title" style="font-size:0.9rem;">
                                <?php echo htmlspecialchars($entrega['actividad_titulo']); ?>
                            </div>
                            <div class="card-meta">
                                <span class="text-stone-400 text-xs font-mono"><?php echo htmlspecialchars($entrega['estudiante_nombre']); ?></span>
                                <span class="text-stone-600 text-xs font-mono">Curso: <?php echo htmlspecialchars($entrega['curso_titulo']); ?></span>
                                <span class="text-stone-600 text-xs font-mono">Lección: <?php echo htmlspecialchars($entrega['leccion_titulo']); ?></span>
                                <span class="activity-status status-<?php echo $entrega['estado']; ?>"><?php echo strtoupper($entrega['estado']); ?></span>
                            </div>
                        </div>
                    </div>

                    <div class="bg-black/30 rounded-lg p-4 mt-2 text-xs text-stone-400">
                        <p class="mb-2">Entregado: <?php echo date('d/m/Y H:i', strtotime($entrega['fecha_entrega'])); ?></p>
                        <?php if ($entrega['archivo_url']): ?>
                        <p>Archivo: <a href="../<?php echo htmlspecialchars($entrega['archivo_url']); ?>" class="text-accent underline" target="_blank">Descargar / Ver</a></p>
                        <?php endif; ?>
                        <?php if ($entrega['respuesta_texto']): ?>
                        <p class="mt-1">Respuesta: <span class="text-white"><?php echo nl2br(htmlspecialchars($entrega['respuesta_texto'])); ?></span></p>
                        <?php endif; ?>
                        <?php if ($entrega['link_url']): ?>
                        <p class="mt-1">Link: <a href="<?php echo htmlspecialchars($entrega['link_url']); ?>" class="text-accent underline" target="_blank"><?php echo htmlspecialchars($entrega['link_url']); ?></a></p>
                        <?php endif; ?>
                    </div>

                    <?php if ($entrega['estado'] === 'pendiente'): ?>
                    <form method="POST" class="mt-4 border-t border-white/5 pt-4">
                        <input type="hidden" name="accion" value="calificar">
                        <input type="hidden" name="entrega_id" value="<?php echo $entrega['id']; ?>">
                        <div class="grid grid-cols-3 gap-3 mb-3">
                            <div>
                                <label class="text-[10px] uppercase text-stone-500 font-mono block mb-1">Calificación (0-100)</label>
                                <input type="number" name="calificacion" min="0" max="100" step="0.5" class="w-full px-3 py-2 rounded-lg text-white font-mono text-sm bg-white/5 border border-white/10 outline-none focus:border-accent">
                            </div>
                            <div>
                                <label class="text-[10px] uppercase text-stone-500 font-mono block mb-1">Estado</label>
                                <select name="estado" class="w-full px-3 py-2 rounded-lg text-white font-mono text-sm bg-white/5 border border-white/10 outline-none focus:border-accent">
                                    <option value="aprobado">APROBADO</option>
                                    <option value="rechazado">RECHAZADO</option>
                                </select>
                            </div>
                            <div class="flex items-end">
                                <button type="submit" class="btn-continuar" style="width:100%;justify-content:center;padding:0.5rem;">CALIFICAR</button>
                            </div>
                        </div>
                        <div>
                            <textarea name="comentario_profesor" rows="2" class="w-full px-3 py-2 rounded-lg text-white font-mono text-sm bg-white/5 border border-white/10 outline-none focus:border-accent" placeholder="Comentario para el estudiante (opcional)..."></textarea>
                        </div>
                    </form>
                    <?php else: ?>
                    <div class="mt-4 border-t border-white/5 pt-4 text-xs">
                        <p class="text-stone-400">Calificación: <strong class="text-white"><?php echo $entrega['calificacion']; ?>/100</strong></p>
                        <?php if ($entrega['comentario_profesor']): ?>
                        <p class="text-stone-400 mt-1">Comentario: <?php echo nl2br(htmlspecialchars($entrega['comentario_profesor'])); ?></p>
                        <?php endif; ?>
                        <p class="text-stone-600 mt-1">Revisado: <?php echo date('d/m/Y H:i', strtotime($entrega['fecha_revision'])); ?></p>
                    </div>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
            </div>
            <?php else: ?>
            <div class="empty-state">
                <svg class="w-16 h-16 text-stone-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                <h3>No hay entregas para revisar</h3>
                <p><?php echo $filtro === 'pendientes' ? 'Todas las entregas han sido calificadas' : 'Aún no hay entregas'; ?></p>
            </div>
            <?php endif; ?>
        </main>
        <?php require __DIR__ . '/../partials/chatbot.php'; ?>
    </div>
</body>
</html>
