<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../config/app.php';

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
                <a href="profesor/estudiantes" class="active"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857"/></svg>Estudiantes</a>
                <a href="profesor/entregas"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>Entregas</a>
                <a href="profesor/pagos"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>Pagos</a>
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
                            <a href="profesor/entregas?estudiante_id=<?php echo $e['id']; ?>" class="btn-explorar" style="padding:0.4rem 1rem;font-size:0.6rem;">ENTREGAS</a>
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
</body>
</html>
