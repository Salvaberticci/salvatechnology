<?php
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/config/app.php';

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

$config = require __DIR__ . '/config/pagos_config.php';
$planes = $config['planes'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Explorar Cursos | Salvatechnology Academy</title>
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
</head>
<body class="dashboard-body">
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
                            <div class="price" style="color:#ff8c00;font-size:0.65rem;">🔒 REQUIERE SUSCRIPCIÓN</div>
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
                            <a href="planes" class="btn-explorar" style="width:100%;justify-content:center">🔓 ADQUIRIR PLAN</a>
                        <?php elseif ($curso['precio'] > 0 && $plan === 'suscripcion'): ?>
                            <a href="inscribir/<?php echo $curso['id']; ?>" class="btn-continuar" style="width:100%;justify-content:center">INSCRIBIRME</a>
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

            <?php if ($plan !== 'suscripcion'): ?>
            <div class="mt-12 mb-6 text-center">
                <h2 class="font-['Orbitron'] text-white text-lg font-bold mb-2">🚀 Desbloquea Todos los Cursos</h2>
                <p class="text-stone-400 text-sm font-mono">Elige un plan de suscripción y accede a todos los cursos premium sin límites</p>
            </div>
            <div class="grid md:grid-cols-4 gap-5 mb-10">
                <?php foreach ($planes as $meses => $planData): ?>
                <?php $esRecomendado = ($meses === 6); ?>
                <div class="bg-[var(--panel-bg)] border <?php echo $esRecomendado ? 'border-accent shadow-[0_0_30px_rgba(255,140,0,0.15)]' : 'border-[var(--border-color)]'; ?> rounded-xl p-6 flex flex-col relative">
                    <?php if ($esRecomendado): ?>
                    <span class="absolute -top-3 left-1/2 -translate-x-1/2 bg-accent text-black text-[10px] font-bold px-3 py-1 rounded-full uppercase tracking-wider font-mono">Recomendado</span>
                    <?php endif; ?>
                    <div class="text-center mb-4">
                        <h3 class="font-['Orbitron'] text-white text-lg font-bold"><?php echo $planData['label']; ?></h3>
                        <div class="mt-3">
                            <span class="font-['Orbitron'] text-accent text-3xl font-black">$<?php echo $planData['precio']; ?></span>
                        </div>
                        <?php if ($planData['ahorro'] > 0): ?>
                        <span class="inline-block mt-2 bg-green-500/15 text-green-400 text-[10px] font-bold px-2 py-1 rounded-full font-mono">Ahorras $<?php echo $planData['ahorro']; ?></span>
                        <?php endif; ?>
                    </div>
                    <div class="text-stone-400 text-xs text-center font-mono flex-grow mb-6 leading-relaxed">
                        <?php echo htmlspecialchars($planData['desc']); ?>
                    </div>
                    <a href="pasarela_pago?plan=<?php echo $meses; ?>" class="btn-continuar" style="width:100%;justify-content:center;padding:0.7rem;">ELEGIR PLAN</a>
                </div>
                <?php endforeach; ?>
            </div>
            <div class="text-center mb-10 p-6 bg-white/5 rounded-xl border border-white/10">
                <h3 class="font-['Orbitron'] text-white text-sm font-bold mb-3">🎯 Beneficios de la Suscripción</h3>
                <div class="grid md:grid-cols-3 gap-4 text-left text-xs font-mono">
                    <div class="bg-black/30 rounded-lg p-4">
                        <span class="text-accent text-base block mb-1">📚</span>
                        <span class="text-white font-bold">Acceso Ilimitado</span>
                        <p class="text-stone-500 mt-1">Todos los cursos premium, todas las lecciones, sin restricciones.</p>
                    </div>
                    <div class="bg-black/30 rounded-lg p-4">
                        <span class="text-accent text-base block mb-1">🎓</span>
                        <span class="text-white font-bold">Clases en Vivo</span>
                        <p class="text-stone-500 mt-1">Sesiones semanales en vivo con el profesor y la comunidad.</p>
                    </div>
                    <div class="bg-black/30 rounded-lg p-4">
                        <span class="text-accent text-base block mb-1">💬</span>
                        <span class="text-white font-bold">Grupo VIP</span>
                        <p class="text-stone-500 mt-1">Acceso al grupo privado de Discord con soporte prioritario.</p>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </main>
        <?php require 'partials/chatbot.php'; ?>
    </div>
</body>
</html>
