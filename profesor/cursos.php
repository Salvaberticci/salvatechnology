<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../config/app.php';

if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_rol'] !== 'profesor') {
    header('Location: ' . BASE_URL . 'academia');
    exit;
}

$profesorId = $_SESSION['usuario_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion'])) {
    if ($_POST['accion'] === 'toggle_activo') {
        $id = (int)$_POST['id'];
        $stmt = $pdo->prepare("UPDATE cursos SET activo = NOT activo WHERE id=? AND profesor_id=?");
        $stmt->execute([$id, $profesorId]);
        header('Location: ' . BASE_URL . 'profesor/cursos');
        exit;
    }
}

$stmt = $pdo->prepare("SELECT c.*, (SELECT COUNT(*) FROM lecciones WHERE curso_id = c.id) as total_lecciones, (SELECT COUNT(*) FROM inscripciones WHERE curso_id = c.id AND estado='activa') as estudiantes_inscritos FROM cursos c WHERE c.profesor_id = ? ORDER BY c.creado_en DESC");
$stmt->execute([$profesorId]);
$cursos = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestionar Cursos | Profesor</title>
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
                <a href="profesor"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>Dashboard</a>
                <a href="profesor/cursos" class="active"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>Cursos</a>
                <a href="profesor/estudiantes"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>Estudiantes</a>
                <a href="profesor/entregas"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>Entregas</a>
                <a href="profesor/pagos"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>Pagos</a>
                <a href="logout"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>Cerrar</a>
            </nav>
        </aside>

        <main class="dash-main">
            <div class="dash-header">
                <h1>Gestionar <span>Cursos</span></h1>
            </div>

            <div class="course-feed">
                <?php foreach ($cursos as $curso): ?>
                <div class="course-card">
                    <div class="card-top">
                        <div>
                            <div class="card-title"><?php echo htmlspecialchars($curso['titulo']); ?></div>
                            <div class="card-meta">
                                <span class="badge <?php echo $curso['activo'] ? 'badge-pagado' : 'badge-pendiente'; ?>"><?php echo $curso['activo'] ? 'ACTIVO' : 'INACTIVO'; ?></span>
                                <span class="text-stone-600 text-xs font-mono"><?php echo $curso['total_lecciones']; ?> lecciones</span>
                                <span class="text-stone-600 text-xs font-mono"><?php echo $curso['estudiantes_inscritos']; ?> estudiantes</span>
                                <?php if ($curso['categoria']): ?>
                                <span class="text-stone-600 text-xs font-mono"><?php echo htmlspecialchars($curso['categoria']); ?></span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <div class="card-actions">
                        <a href="curso/<?php echo $curso['id']; ?>" class="btn-continuar">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                            Ver como estudiante
                        </a>
                        <a href="profesor/lecciones/<?php echo $curso['id']; ?>" class="btn-explorar" style="font-size:0.7rem;">
                            Gestionar
                        </a>
                        <form method="POST" style="display:inline">
                            <input type="hidden" name="accion" value="toggle_activo">
                            <input type="hidden" name="id" value="<?php echo $curso['id']; ?>">
                            <button type="submit" class="btn-explorar"><?php echo $curso['activo'] ? 'DESACTIVAR' : 'ACTIVAR'; ?></button>
                        </form>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

        </main>
        <?php require __DIR__ . '/../partials/chatbot.php'; ?>
    </div>
</body>
</html>
