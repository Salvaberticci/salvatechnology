<?php
require_once __DIR__ . '/../config/db.php';

if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_rol'] !== 'profesor') {
    header('Location: /salvatechnology/academia');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion'])) {
    $pagoId = (int)$_POST['pago_id'];
    $accion = $_POST['accion'];

    $stmt = $pdo->prepare("SELECT p.*, u.nombre as estudiante_nombre FROM pagos p JOIN usuarios u ON p.usuario_id = u.id WHERE p.id = ?");
    $stmt->execute([$pagoId]);
    $pago = $stmt->fetch();

    if ($pago) {
        if ($accion === 'aprobar') {
            $pdo->prepare("UPDATE pagos SET estado = 'completado', updated_at = NOW() WHERE id = ?")->execute([$pagoId]);

            if ($pago['tipo'] === 'suscripcion') {
                $mapaMeses = [40 => 1, 110 => 3, 190 => 6, 380 => 12];
                $meses = $mapaMeses[(int)$pago['monto']] ?? 1;
                $expira = date('Y-m-d', strtotime("+$meses months"));
                $pdo->prepare("UPDATE usuarios SET plan = 'suscripcion', suscripcion_expira = ? WHERE id = ?")
                    ->execute([$expira, $pago['usuario_id']]);
            } else {
                $existe = $pdo->prepare("SELECT id FROM inscripciones WHERE usuario_id = ? AND curso_id = ?");
                $existe->execute([$pago['usuario_id'], $pago['curso_id']]);
                if (!$existe->fetch()) {
                    $pdo->prepare("INSERT INTO inscripciones (usuario_id, curso_id, tipo, estado) VALUES (?, ?, 'pago', 'activa')")
                        ->execute([$pago['usuario_id'], $pago['curso_id']]);
                }
            }
        } elseif ($accion === 'rechazar') {
            $pdo->prepare("UPDATE pagos SET estado = 'rechazado', updated_at = NOW() WHERE id = ?")->execute([$pagoId]);
        }

        $comentario = trim($_POST['comentario_profesor'] ?? '');
        if ($comentario) {
            $pdo->prepare("UPDATE pagos SET notas_estudiante = CONCAT(COALESCE(notas_estudiante, ''), ' | Respuesta profesor: ', ?) WHERE id = ?")
                ->execute([$comentario, $pagoId]);
        }
    }

    header('Location: /salvatechnology/profesor/pagos');
    exit;
}

$filtro = $_GET['filtro'] ?? 'todos';

$sql = "SELECT p.*, u.nombre as estudiante_nombre, u.email as estudiante_email, c.titulo as curso_titulo
    FROM pagos p
    JOIN usuarios u ON p.usuario_id = u.id
    LEFT JOIN cursos c ON p.curso_id = c.id
    WHERE 1=1";
$params = [];

if ($filtro === 'pendientes') {
    $sql .= " AND p.estado = 'pendiente'";
} elseif ($filtro === 'completados') {
    $sql .= " AND p.estado = 'completado'";
} elseif ($filtro === 'rechazados') {
    $sql .= " AND p.estado = 'rechazado'";
} elseif ($filtro === 'todos') {
    // sin filtro, muestra todos
}

$sql .= " ORDER BY p.fecha_pago DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$pagos = $stmt->fetchAll();

$pendientes = $pdo->query("SELECT COUNT(*) FROM pagos WHERE estado = 'pendiente'")->fetchColumn();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pagos | Profesor</title>
    <base href="/salvatechnology/">
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
                <a href="profesor/estudiantes"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857"/></svg>Estudiantes</a>
                <a href="profesor/entregas"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>Entregas</a>
                <a href="profesor/pagos" class="active"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>Pagos
                    <?php if ($pendientes > 0): ?>
                    <span class="ml-auto bg-red-500 text-white text-[9px] px-2 py-0.5 rounded-full font-bold"><?php echo $pendientes; ?></span>
                    <?php endif; ?>
                </a>
                <a href="logout"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>Cerrar</a>
            </nav>
        </aside>

        <main class="dash-main">
            <div class="dash-header">
                <h1>Revisar <span>Pagos</span></h1>
                <div class="flex gap-2">
                    <a href="profesor/pagos?filtro=pendientes" class="btn-explorar" style="padding:0.4rem 1rem;font-size:0.6rem;">PENDIENTES</a>
                    <a href="profesor/pagos?filtro=completados" class="btn-explorar" style="padding:0.4rem 1rem;font-size:0.6rem;">APROBADOS</a>
                    <a href="profesor/pagos?filtro=rechazados" class="btn-explorar" style="padding:0.4rem 1rem;font-size:0.6rem;">RECHAZADOS</a>
                    <a href="profesor/pagos" class="btn-explorar" style="padding:0.4rem 1rem;font-size:0.6rem;">TODOS</a>
                </div>
            </div>

            <?php if (count($pagos) > 0): ?>
            <div class="course-feed">
                <?php foreach ($pagos as $pago): ?>
                <div class="course-card">
                    <div class="card-top">
                        <div>
                            <div class="card-title" style="font-size:0.9rem;">
                                <?php echo htmlspecialchars($pago['estudiante_nombre']); ?>
                                <span class="text-stone-500 text-xs font-mono ml-2"><?php echo htmlspecialchars($pago['estudiante_email']); ?></span>
                            </div>
                            <div class="card-meta">
                                <?php if ($pago['tipo'] === 'suscripcion'): ?>
                                <span class="text-stone-400 text-xs font-mono">Tipo: SUSCRIPCIÓN</span>
                                <?php else: ?>
                                <span class="text-stone-400 text-xs font-mono">Curso: <?php echo htmlspecialchars($pago['curso_titulo'] ?? 'N/A'); ?></span>
                                <?php endif; ?>
                                <span class="text-accent text-xs font-mono font-bold">$<?php echo number_format($pago['monto'], 2); ?></span>
                                <span class="text-stone-600 text-xs font-mono"><?php echo strtoupper(htmlspecialchars($pago['metodo_pago'])); ?></span>
                                <span class="activity-status status-<?php echo $pago['estado'] === 'completado' ? 'aprobado' : ($pago['estado'] === 'rechazado' ? 'rechazado' : 'pendiente'); ?>"><?php echo strtoupper($pago['estado']); ?></span>
                            </div>
                        </div>
                    </div>

                    <div class="bg-black/30 rounded-lg p-4 mt-2 text-xs text-stone-400">
                        <?php if ($pago['tipo'] === 'suscripcion'): ?>
                        <p class="mb-1">Tipo: <span class="text-accent font-bold">SUSCRIPCIÓN</span></p>
                        <?php endif; ?>
                        <p class="mb-1">Referencia: <span class="text-white font-bold"><?php echo htmlspecialchars($pago['referencia']); ?></span></p>
                        <p class="mb-1">Monto: <span class="text-white font-bold">$<?php echo number_format($pago['monto'], 2); ?></span></p>
                        <p class="mb-1">Método: <span class="text-white"><?php echo strtoupper(htmlspecialchars($pago['metodo_pago'])); ?></span></p>
                        <p class="mb-1">Fecha: <?php echo date('d/m/Y H:i', strtotime($pago['fecha_pago'])); ?></p>
                        <?php if ($pago['comprobante_url']): ?>
                        <p>Comprobante: <a href="<?php echo htmlspecialchars($pago['comprobante_url']); ?>" class="text-accent underline" target="_blank">Ver comprobante</a></p>
                        <?php endif; ?>
                        <?php if ($pago['notas_estudiante']): ?>
                        <p class="mt-1">Notas: <span class="text-white"><?php echo nl2br(htmlspecialchars($pago['notas_estudiante'])); ?></span></p>
                        <?php endif; ?>
                    </div>

                    <?php if ($pago['estado'] === 'pendiente'): ?>
                    <div class="flex gap-3 mt-4">
                        <form method="POST" class="flex-1">
                            <input type="hidden" name="accion" value="aprobar">
                            <input type="hidden" name="pago_id" value="<?php echo $pago['id']; ?>">
                            <div class="flex gap-2">
                                <input type="text" name="comentario_profesor" placeholder="Nota (opcional)" class="flex-1 px-3 py-2 rounded-lg text-white font-mono text-xs bg-white/5 border border-white/10 outline-none focus:border-accent">
                                <button type="submit" class="btn-continuar" style="padding:0.5rem 1.2rem;font-size:0.6rem;background:#00ff41;color:#000;">APROBAR</button>
                            </div>
                        </form>
                        <form method="POST">
                            <input type="hidden" name="accion" value="rechazar">
                            <input type="hidden" name="pago_id" value="<?php echo $pago['id']; ?>">
                            <button type="submit" class="btn-continuar" style="padding:0.5rem 1.2rem;font-size:0.6rem;background:#ff4444;color:#fff;">RECHAZAR</button>
                        </form>
                    </div>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
            </div>
            <?php else: ?>
            <div class="empty-state">
                <svg class="w-16 h-16 text-stone-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                <h3>No hay pagos para revisar</h3>
                <p><?php
                    if ($filtro === 'pendientes') echo 'Todos los pagos han sido procesados';
                    elseif ($filtro === 'completados') echo 'No hay pagos aprobados aún';
                    elseif ($filtro === 'rechazados') echo 'No hay pagos rechazados';
                    else echo 'Aún no hay pagos registrados en el sistema';
                ?></p>
            </div>
            <?php endif; ?>
        </main>
        <?php require __DIR__ . '/../partials/chatbot.php'; ?>
    </div>
</body>
</html>
