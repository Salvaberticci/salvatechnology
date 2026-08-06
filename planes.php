<?php
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/config/app.php';

if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_rol'] !== 'estudiante') {
    header('Location: academia');
    exit;
}

$config = require __DIR__ . '/config/pagos_config.php';
$planes = $config['planes'];
$planActual = $_SESSION['usuario_plan'];

$stmt = $pdo->prepare("SELECT * FROM pagos WHERE usuario_id = ? AND tipo = 'suscripcion' AND estado = 'pendiente' LIMIT 1");
$stmt->execute([$_SESSION['usuario_id']]);
$pagoPendiente = $stmt->fetch();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Planes de Suscripción | Salvatechnology</title>
    <base href="<?= BASE_URL ?>">
    <link rel="icon" type="image/png" href="img/logo.png">
    <link rel="stylesheet" href="css/dashboard.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>tailwind.config={theme:{extend:{colors:{'accent':'#ff8c00','dark-bg':'#0a0a0a'}}}}</script>
</head>
<body class="dashboard-body">
    <?php require 'partials/matrix-rain.php'; ?>
    <div class="scanlines"></div>
    <div class="dashboard-layout">
        <aside class="dash-sidebar">
            <div class="logo-side"><a href="<?= BASE_URL ?>"><img src="img/logo.png" alt="Salva Technology"></a></div>
            <div class="user-badge">
                <?php if (!empty($_SESSION['usuario_avatar'])): ?>
                    <img src="<?= htmlspecialchars($_SESSION['usuario_avatar']) ?>" alt="Foto" class="avatar-img">
                <?php else: ?>
                    <div class="avatar"><?php echo strtoupper(substr($_SESSION['usuario_nombre'], 0, 1)); ?></div>
                <?php endif; ?>
                <div class="name"><?php echo htmlspecialchars($_SESSION['usuario_nombre']); ?></div>
                <span class="plan-badge plan-<?php echo $planActual; ?>"><?php echo $planActual === 'suscripcion' ? 'SUSCRIPCIÓN' : 'GRATUITO'; ?></span>
            </div>
            <nav class="dash-nav">
                <a href="dashboard"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>Dashboard</a>
                <a href="cursos"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253"/></svg>Explorar Cursos</a>
                <a href="planes" class="active"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>Planes</a>
                <a href="perfil"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>Mi Perfil</a>
                <a href="logout"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>Cerrar Sesión</a>
            </nav>
        </aside>

        <main class="dash-main">
            <div class="dash-header">
                <h1>Planes de <span>Suscripción</span></h1>
                <span class="text-stone-600 text-xs font-mono"><?php echo date('d.m.Y'); ?></span>
            </div>

            <div class="text-center mb-10">
                <h2 class="font-['Orbitron'] text-white text-lg font-bold mb-2">La Matriz Oficial de Planes de Pago</h2>
                <p class="text-stone-400 text-sm font-mono">Elige el plan que mejor se adapte a tu camino como AI-Driven Developer</p>
            </div>

            <?php if ($pagoPendiente): ?>
            <div class="bg-yellow-500/10 border border-yellow-500/30 rounded-xl p-6 mb-8 text-center">
                <div class="text-4xl mb-2">⏳</div>
                <h3 class="font-['Orbitron'] text-yellow-400 text-base font-bold">Tu plan está en aprobación</h3>
                <p class="text-stone-400 text-xs mt-1 font-mono">Realizaste un pago de <strong class="text-white">$<?php echo number_format($pagoPendiente['monto'], 0); ?></strong> por <strong class="text-white"><?php echo htmlspecialchars($pagoPendiente['metodo_pago']); ?></strong>. Estamos revisando tu comprobante, en breve un profesor activará tu suscripción.</p>
            </div>
            <?php elseif ($planActual === 'suscripcion'): ?>
            <div class="bg-green-500/10 border border-green-500/30 rounded-xl p-6 mb-8 text-center">
                <div class="text-4xl mb-2">✅</div>
                <h3 class="font-['Orbitron'] text-green-400 text-base font-bold">Ya tienes una suscripción activa</h3>
                <p class="text-stone-400 text-xs mt-1 font-mono">Disfruta de acceso ilimitado a todos los cursos.</p>
            </div>
            <?php endif; ?>

            <div class="grid md:grid-cols-4 gap-5">
                <?php foreach ($planes as $meses => $plan): ?>
                <?php
                $esRecomendado = ($meses === 6);
                $destacado = $planActual !== 'suscripcion' && $esRecomendado;
                ?>
                <div class="bg-[var(--panel-bg)] border <?php echo $destacado ? 'border-accent' : 'border-[var(--border-color)]'; ?> rounded-xl p-6 flex flex-col relative <?php if ($destacado) echo 'shadow-[0_0_30px_rgba(255,140,0,0.15)]'; ?>">
                    <?php if ($esRecomendado): ?>
                    <span class="absolute -top-3 left-1/2 -translate-x-1/2 bg-accent text-black text-[10px] font-bold px-3 py-1 rounded-full uppercase tracking-wider font-mono">Recomendado</span>
                    <?php endif; ?>

                    <div class="text-center mb-4">
                        <h3 class="font-['Orbitron'] text-white text-lg font-bold"><?php echo $plan['label']; ?></h3>
                        <div class="mt-3">
                            <span class="font-['Orbitron'] text-accent text-4xl font-black">$<?php echo $plan['precio']; ?></span>
                            <span class="text-stone-500 text-xs font-mono block mt-1">pago único</span>
                        </div>
                        <?php if ($plan['ahorro'] > 0): ?>
                        <span class="inline-block mt-2 bg-green-500/15 text-green-400 text-[10px] font-bold px-2 py-1 rounded-full font-mono">Ahorras $<?php echo $plan['ahorro']; ?></span>
                        <?php endif; ?>
                    </div>

                    <div class="text-stone-400 text-xs text-center font-mono flex-grow mb-6 leading-relaxed">
                        <?php echo htmlspecialchars($plan['desc']); ?>
                    </div>

                    <?php if ($planActual !== 'suscripcion'): ?>
                    <a href="pasarela_pago?plan=<?php echo $meses; ?>" class="btn-continuar" style="width:100%;justify-content:center;padding:0.7rem;">ELEGIR PLAN</a>
                    <?php else: ?>
                    <button disabled class="btn-explorar" style="width:100%;justify-content:center;opacity:0.5;cursor:not-allowed;">YA ACTIVO</button>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
            </div>

            <?php if ($planActual === 'gratuito'): ?>
            <div class="mt-8 text-center">
                <p class="text-stone-500 text-xs font-mono">Selecciona un plan y realiza el pago. Un profesor activará tu suscripción.</p>
            </div>
            <?php endif; ?>
        </main>
        <?php require 'partials/chatbot.php'; ?>
    </div>
    <script src="js/matrix-rain.js"></script>
</body>
</html>
