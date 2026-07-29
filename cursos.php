<?php
require_once __DIR__ . '/config/db.php';

if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_rol'] !== 'estudiante') {
    header('Location: academia');
    exit;
}

$usuarioId = $_SESSION['usuario_id'];
$plan = $_SESSION['usuario_plan'];

$categoria = $_GET['categoria'] ?? '';

$sql = "SELECT c.*,
    (SELECT COUNT(*) FROM lecciones WHERE curso_id = c.id) as total_lecciones,
    (SELECT id FROM inscripciones WHERE usuario_id = ? AND curso_id = c.id AND estado = 'activa') as ya_inscrito
    FROM cursos c WHERE c.activo = 1";
$params = [$usuarioId];

if (!empty($categoria)) {
    $sql .= " AND c.categoria = ?";
    $params[] = $categoria;
}
$sql .= " ORDER BY c.creado_en DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$cursos = $stmt->fetchAll();

$stmt = $pdo->prepare("SELECT DISTINCT categoria FROM cursos WHERE activo = 1 AND categoria IS NOT NULL ORDER BY categoria");
$stmt->execute();
$categorias = $stmt->fetchAll(PDO::FETCH_COLUMN);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Explorar Cursos | Salvatechnology Academy</title>
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
                <span class="plan-badge plan-<?php echo $plan; ?>"><?php echo $plan === 'suscripcion' ? 'SUSCRIPCIÓN' : 'GRATUITO'; ?></span>
            </div>
            <nav class="dash-nav">
                <a href="dashboard">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>Dashboard
                </a>
                <a href="cursos" class="active">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>Explorar Cursos
                </a>
                <a href="planes">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>Planes</a>
                <a href="logout">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>Cerrar Sesión
                </a>
            </nav>
        </aside>

        <main class="dash-main">
            <div class="dash-header">
                <h1>Explorar <span>Cursos</span></h1>
                <div class="header-actions">
                    <span class="text-stone-600 text-xs font-mono"><?php echo date('d.m.Y'); ?></span>
                </div>
            </div>

            <?php if (!empty($categorias)): ?>
            <div class="flex gap-2 mb-6 flex-wrap">
                <a href="cursos" class="px-4 py-2 rounded-full text-xs font-mono font-bold uppercase tracking-wider transition-all <?php echo empty($categoria) ? 'bg-accent text-black' : 'bg-white/5 text-stone-400 hover:text-white border border-white/10'; ?>">Todos</a>
                <?php foreach ($categorias as $cat): ?>
                <a href="cursos?categoria=<?php echo urlencode($cat); ?>" class="px-4 py-2 rounded-full text-xs font-mono font-bold uppercase tracking-wider transition-all <?php echo $categoria === $cat ? 'bg-accent text-black' : 'bg-white/5 text-stone-400 hover:text-white border border-white/10'; ?>"><?php echo htmlspecialchars($cat); ?></a>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>

            <?php if (count($cursos) > 0): ?>
            <div class="course-grid">
                <?php foreach ($cursos as $curso): ?>
                <div class="catalog-card">
                    <div class="card-img">
                        <?php if ($curso['imagen']): ?>
                            <img src="<?php echo htmlspecialchars($curso['imagen']); ?>" alt="<?php echo htmlspecialchars($curso['titulo']); ?>">
                        <?php else: ?>
                            <div class="placeholder-icon">
                                <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="card-body">
                        <h3><?php echo htmlspecialchars($curso['titulo']); ?></h3>
                        <?php if ($curso['precio'] > 0): ?>
                            <div class="price">$<?php echo number_format($curso['precio'], 2); ?></div>
                        <?php else: ?>
                            <div class="price free">GRATUITO</div>
                        <?php endif; ?>
                        <div class="desc"><?php echo htmlspecialchars(substr($curso['descripcion'] ?? '', 0, 120)) . (strlen($curso['descripcion'] ?? '') > 120 ? '...' : ''); ?></div>
                        <div class="text-stone-600 text-[10px] font-mono mb-3">
                            <?php echo $curso['total_lecciones']; ?> lecciones
                            <?php if ($curso['duracion']): ?> · <?php echo htmlspecialchars($curso['duracion']); ?><?php endif; ?>
                            <?php if ($curso['categoria']): ?> · <?php echo htmlspecialchars($curso['categoria']); ?><?php endif; ?>
                        </div>
                        <?php if ($curso['ya_inscrito']): ?>
                            <a href="curso/<?php echo $curso['id']; ?>" class="btn-continuar" style="width:100%;justify-content:center">IR AL CURSO</a>
                        <?php elseif ($curso['precio'] > 0 && $plan !== 'suscripcion'): ?>
                            <a href="pasarela_pago?curso=<?php echo $curso['id']; ?>" class="btn-explorar" style="width:100%;justify-content:center">COMPRAR $<?php echo number_format($curso['precio'], 2); ?></a>
                        <?php else: ?>
                            <a href="inscribir/<?php echo $curso['id']; ?>" class="btn-continuar" style="width:100%;justify-content:center">INSCRIBIRME GRATIS</a>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <?php else: ?>
            <div class="empty-state">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <h3>No hay cursos disponibles</h3>
                <p>Próximamente estaremos agregando contenido</p>
            </div>
            <?php endif; ?>
        </main>
    </div>
</body>
</html>
