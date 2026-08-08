<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../helpers/config_sistema.php';

if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_rol'] !== 'profesor') {
    header('Location: ' . BASE_URL . 'academia');
    exit;
}

$totalEstudiantes = $pdo->query("SELECT COUNT(*) FROM usuarios WHERE rol = 'estudiante'")->fetchColumn();
$totalCursos = $pdo->query("SELECT COUNT(*) FROM cursos")->fetchColumn();
$totalEntregasPendientes = $pdo->query("SELECT COUNT(*) FROM entregas WHERE estado = 'pendiente'")->fetchColumn();
$suscripcionesActivas = $pdo->query("SELECT COUNT(*) FROM usuarios WHERE rol = 'estudiante' AND plan = 'suscripcion' AND (suscripcion_expira IS NULL OR suscripcion_expira >= CURDATE())")->fetchColumn();
$inscripcionesHoy = $pdo->query("SELECT COUNT(*) FROM inscripciones WHERE DATE(fecha_inscripcion) = CURDATE()")->fetchColumn();
$pagosPendientes = $pdo->query("SELECT COUNT(*) FROM pagos WHERE estado = 'pendiente'")->fetchColumn();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Profesor | Salvatechnology</title>
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
            <div class="logo-side">
                <a href="./"><img src="img/logo.png" alt="Salva Technology"></a>
            </div>
            <div class="user-badge">
                <div class="avatar" style="background:#ff4444;color:#fff;"><?php echo strtoupper(substr($_SESSION['usuario_nombre'], 0, 1)); ?></div>
                <div class="name"><?php echo htmlspecialchars($_SESSION['usuario_nombre']); ?></div>
                <span class="plan-badge" style="background:rgba(255,68,68,0.15);color:#ff4444;border-color:rgba(255,68,68,0.3);">PROFESOR</span>
            </div>
            <nav class="dash-nav">
                <a href="profesor" class="active">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>Dashboard
                </a>
                <a href="profesor/cursos">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>Cursos
                </a>
                <a href="profesor/estudiantes">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>Estudiantes
                </a>
                <a href="profesor/entregas">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>Entregas
                    <?php if ($totalEntregasPendientes > 0): ?>
                    <span class="ml-auto bg-red-500 text-white text-[9px] px-2 py-0.5 rounded-full font-bold"><?php echo $totalEntregasPendientes; ?></span>
                    <?php endif; ?>
                </a>
                <a href="profesor/pagos">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>Pagos
                    <?php
                    $pagosPendientes = $pdo->query("SELECT COUNT(*) FROM pagos WHERE estado = 'pendiente'")->fetchColumn();
                    if ($pagosPendientes > 0): ?>
                    <span class="ml-auto bg-red-500 text-white text-[9px] px-2 py-0.5 rounded-full font-bold"><?php echo $pagosPendientes; ?></span>
                    <?php endif; ?>
                </a>
                <a href="profesor/perfil">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>Mi Perfil
                </a>
                <?php if (esAdmin()): ?>
                <a href="profesor/admin">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.8 0-3 .6-3 1.5v.2c0 .5.3 1 .8 1.3.5.3 1.2.4 2 .4h.2c.8 0 1.5-.1 2-.4.5-.3.8-.8.8-1.3v-.2C15 8.6 13.8 8 12 8zm0 6c-2.5 0-4.5-1-4.5-3.5 0-2.5 2-3.8 4.5-3.8s4.5 1.3 4.5 3.8C16.5 13 14.5 14 12 14zm5 4.5c0-2.2-2.2-4-5-4s-5 1.8-5 4H17z"/></svg>Panel Admin
                </a>
                <?php endif; ?>
                <a href="logout">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>Cerrar Sesión
                </a>
            </nav>
        </aside>

        <main class="dash-main">
            <div class="dash-header">
                <h1>Panel del <span>Profesor</span></h1>
                <span class="text-stone-600 text-xs font-mono"><?php echo date('d.m.Y H:i'); ?></span>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-8">
                <div class="bg-[var(--panel-bg)] border border-[var(--border-color)] rounded-xl p-5">
                    <div class="text-accent text-3xl font-black font-['Orbitron']"><?php echo $totalEstudiantes; ?></div>
                    <div class="text-stone-500 text-xs font-mono mt-1 uppercase tracking-wider">Estudiantes</div>
                </div>
                <div class="bg-[var(--panel-bg)] border border-[var(--border-color)] rounded-xl p-5">
                    <div class="text-accent text-3xl font-black font-['Orbitron']"><?php echo $totalCursos; ?></div>
                    <div class="text-stone-500 text-xs font-mono mt-1 uppercase tracking-wider">Cursos</div>
                </div>
                <div class="bg-[var(--panel-bg)] border border-[var(--border-color)] rounded-xl p-5">
                    <div class="text-accent text-3xl font-black font-['Orbitron']"><?php echo $totalEntregasPendientes; ?></div>
                    <div class="text-stone-500 text-xs font-mono mt-1 uppercase tracking-wider">Entregas Pend.</div>
                </div>
                <div class="bg-[var(--panel-bg)] border border-[var(--border-color)] rounded-xl p-5">
                    <div class="text-accent text-3xl font-black font-['Orbitron']"><?php echo $suscripcionesActivas; ?></div>
                    <div class="text-stone-500 text-xs font-mono mt-1 uppercase tracking-wider">Suscripciones</div>
                </div>
                <div class="bg-[var(--panel-bg)] border border-[var(--border-color)] rounded-xl p-5">
                    <div class="text-accent text-3xl font-black font-['Orbitron']"><?php echo $pagosPendientes; ?></div>
                    <div class="text-stone-500 text-xs font-mono mt-1 uppercase tracking-wider">Pagos Pend.</div>
                </div>
            </div>

            <div class="grid md:grid-cols-2 gap-6">
                <div class="bg-[var(--panel-bg)] border border-[var(--border-color)] rounded-xl p-6">
                    <h3 class="font-['Orbitron'] text-white text-sm font-bold mb-4 uppercase tracking-wider">Inscripciones Hoy</h3>
                    <div class="text-4xl font-black text-accent font-['Orbitron']"><?php echo $inscripcionesHoy; ?></div>
                </div>
                <div class="bg-[var(--panel-bg)] border border-[var(--border-color)] rounded-xl p-6">
                    <h3 class="font-['Orbitron'] text-white text-sm font-bold mb-4 uppercase tracking-wider">Acceso rápido</h3>
                    <div class="flex flex-col gap-2">
                        <a href="profesor/cursos" class="text-accent hover:text-white transition-colors text-sm font-mono">→ Gestionar cursos</a>
                        <a href="profesor/entregas" class="text-accent hover:text-white transition-colors text-sm font-mono">→ Revisar entregas pendientes</a>
                        <a href="profesor/pagos" class="text-accent hover:text-white transition-colors text-sm font-mono">→ Revisar pagos pendientes</a>
                        <a href="profesor/estudiantes" class="text-accent hover:text-white transition-colors text-sm font-mono">→ Ver estudiantes</a>
                    </div>
                </div>
            </div>
        </main>
        <?php require __DIR__ . '/../partials/chatbot.php'; ?>
    </div>
</body>
</html>
