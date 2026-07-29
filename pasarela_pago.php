<?php
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/config/app.php';

if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_rol'] !== 'estudiante') {
    header('Location: academia');
    exit;
}

$usuarioId = $_SESSION['usuario_id'];
$config = require __DIR__ . '/config/pagos_config.php';
$metodos = $config['metodos'];
$planId = (int)($_GET['plan'] ?? $_POST['plan_id'] ?? 0);
$cursoId = (int)($_GET['curso'] ?? $_POST['curso_compra'] ?? 0);
$esSuscripcion = ($planId > 0 && isset($config['planes'][$planId]));

if ($esSuscripcion) {
    $planData = $config['planes'][$planId];
    $titulo = 'Suscripción ' . $planData['label'];
    $precio = $planData['precio'];
    $tipoPago = 'suscripcion';
    $backLink = 'planes';

    $stmt = $pdo->prepare("SELECT * FROM pagos WHERE usuario_id = ? AND tipo = 'suscripcion' AND estado = 'pendiente'");
    $stmt->execute([$usuarioId]);
    $pagoPendiente = $stmt->fetch();

    $itemLabel = 'Plan ' . $planData['label'];
    $successMsg = 'Tu pago de suscripción ha sido enviado. Un profesor lo revisará y activará tu plan.';
} elseif ($cursoId > 0) {
    $stmt = $pdo->prepare("SELECT * FROM cursos WHERE id = ? AND activo = 1");
    $stmt->execute([$cursoId]);
    $curso = $stmt->fetch();

    if (!$curso) die("Curso no encontrado");
    if ($curso['precio'] <= 0) { header('Location: inscribir/' . $cursoId); exit; }

    $stmt = $pdo->prepare("SELECT id FROM inscripciones WHERE usuario_id = ? AND curso_id = ? AND estado = 'activa'");
    $stmt->execute([$usuarioId, $cursoId]);
    if ($stmt->fetch()) { header('Location: curso/' . $cursoId); exit; }

    $stmt = $pdo->prepare("SELECT * FROM pagos WHERE usuario_id = ? AND curso_id = ? AND estado = 'pendiente'");
    $stmt->execute([$usuarioId, $cursoId]);
    $pagoPendiente = $stmt->fetch();

    $titulo = $curso['titulo'];
    $precio = $curso['precio'];
    $tipoPago = 'curso_individual';
    $backLink = 'cursos';
    $itemLabel = $curso['titulo'];
    $successMsg = 'Tu comprobante de pago ha sido enviado. Un profesor revisará tu pago y activará tu acceso al curso.';
} else {
    header('Location: cursos');
    exit;
}

$metodoSeleccionado = $_GET['metodo'] ?? 'pagomovil';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $metodo = $_POST['metodo_pago'] ?? '';
    $referencia = trim($_POST['referencia'] ?? '');
    $notas = trim($_POST['notas_estudiante'] ?? '');

    if (empty($metodo) || empty($referencia)) {
        $error = 'Debes seleccionar un método de pago y colocar la referencia.';
    } else {
        $comprobanteUrl = null;
        if (isset($_FILES['comprobante']) && $_FILES['comprobante']['error'] === UPLOAD_ERR_OK) {
            $ext = strtolower(pathinfo($_FILES['comprobante']['name'], PATHINFO_EXTENSION));
            $allowed = ['jpg', 'jpeg', 'png', 'gif', 'pdf'];
            if (in_array($ext, $allowed)) {
                $filename = 'comp_' . $usuarioId . '_' . ($esSuscripcion ? 'sub' . $planId : $cursoId) . '_' . time() . '.' . $ext;
                $dest = __DIR__ . '/uploads/comprobantes/' . $filename;
                if (move_uploaded_file($_FILES['comprobante']['tmp_name'], $dest)) {
                    $comprobanteUrl = 'uploads/comprobantes/' . $filename;
                }
            } else {
                $error = 'Formato de archivo no permitido. Solo JPG, PNG, GIF o PDF.';
            }
        }

        if (!isset($error)) {
            if ($pagoPendiente) {
                $stmt = $pdo->prepare("UPDATE pagos SET metodo_pago = ?, referencia = ?, notas_estudiante = ?, comprobante_url = ?, updated_at = NOW() WHERE id = ?");
                $stmt->execute([$metodo, $referencia, $notas, $comprobanteUrl, $pagoPendiente['id']]);
            } else {
                $stmt = $pdo->prepare("INSERT INTO pagos (usuario_id, curso_id, monto, tipo, metodo_pago, referencia, notas_estudiante, comprobante_url, estado) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'pendiente')");
                $stmt->execute([$usuarioId, $esSuscripcion ? null : $cursoId, $precio, $tipoPago, $metodo, $referencia, $notas, $comprobanteUrl]);
            }
            $success = true;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pago | <?php echo htmlspecialchars($titulo); ?></title>
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
            <div class="logo-side"><a href="<?= BASE_URL ?>"><img src="img/logo.png" alt="Salva Technology"></a></div>
            <div class="user-badge">
                <div class="avatar"><?php echo strtoupper(substr($_SESSION['usuario_nombre'], 0, 1)); ?></div>
                <div class="name"><?php echo htmlspecialchars($_SESSION['usuario_nombre']); ?></div>
                <span class="plan-badge plan-<?php echo $_SESSION['usuario_plan']; ?>"><?php echo $_SESSION['usuario_plan'] === 'suscripcion' ? 'SUSCRIPCIÓN' : 'GRATUITO'; ?></span>
            </div>
            <nav class="dash-nav">
                <a href="dashboard"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>Dashboard</a>
                <a href="cursos"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253"/></svg>Explorar Cursos</a>
                <a href="planes"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>Planes</a>
                <a href="logout"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>Cerrar Sesión</a>
            </nav>
        </aside>

        <main class="dash-main">
            <div class="dash-header">
                <h1>Pagar <?php echo $esSuscripcion ? '<span>Suscripción</span>' : '<span>Curso</span>'; ?></h1>
                <a href="<?php echo $backLink; ?>" class="btn-explorar" style="padding:0.4rem 1rem;font-size:0.6rem;">VOLVER</a>
            </div>

            <?php if (isset($success)): ?>
            <div class="bg-[var(--panel-bg)] border border-[var(--border-color)] rounded-xl p-8 text-center">
                <div class="text-5xl mb-4">✅</div>
                <h2 class="font-['Orbitron'] text-white text-xl font-bold mb-2">Pago Enviado</h2>
                <p class="text-stone-400 text-sm mb-1"><?php echo $successMsg; ?></p>
                <p class="text-stone-500 text-xs mb-6">Recibirás una notificación cuando sea aprobado.</p>
                <div class="flex gap-3 justify-center">
                    <a href="<?php echo $backLink; ?>" class="btn-continuar">VOLVER</a>
                    <a href="dashboard" class="btn-explorar">MI DASHBOARD</a>
                </div>
            </div>
            <?php else: ?>

            <?php if (isset($error)): ?>
            <div class="bg-red-500/10 border border-red-500/30 rounded-xl p-4 mb-6">
                <p class="text-red-400 text-sm font-mono"><?php echo htmlspecialchars($error); ?></p>
            </div>
            <?php endif; ?>

            <?php if ($pagoPendiente && !isset($_POST['metodo_pago'])): ?>
            <div class="bg-[var(--panel-bg)] border border-yellow-500/30 rounded-xl p-6 mb-6">
                <h3 class="font-['Orbitron'] text-yellow-400 text-sm font-bold mb-2">PAGO PENDIENTE</h3>
                <p class="text-stone-400 text-xs mb-2">Ya enviaste un pago. Está siendo revisado.</p>
                <p class="text-stone-500 text-xs">Método: <span class="text-white"><?php echo strtoupper(htmlspecialchars($pagoPendiente['metodo_pago'])); ?></span> · Referencia: <span class="text-white"><?php echo htmlspecialchars($pagoPendiente['referencia']); ?></span></p>
                <div class="flex gap-3 mt-4">
                    <a href="<?php echo $backLink; ?>" class="btn-explorar" style="padding:0.4rem 1rem;font-size:0.6rem;">VOLVER</a>
                </div>
            </div>
            <?php else: ?>

            <div class="grid md:grid-cols-5 gap-6">
                <div class="md:col-span-2">
                    <div class="bg-[var(--panel-bg)] border border-[var(--border-color)] rounded-xl p-6">
                        <div class="text-[10px] uppercase text-stone-500 font-mono mb-2 tracking-widest">Resumen</div>
                        <h3 class="font-['Orbitron'] text-white text-base font-bold mb-1"><?php echo htmlspecialchars($titulo); ?></h3>
                        <?php if ($esSuscripcion): ?>
                        <span class="text-stone-500 text-xs font-mono"><?php echo $planData['label']; ?> · Acceso completo a todos los cursos</span>
                        <?php endif; ?>
                        <div class="border-t border-white/10 mt-4 pt-4">
                            <div class="flex justify-between items-center">
                                <span class="text-stone-400 text-xs">Total a pagar</span>
                                <span class="font-['Orbitron'] text-accent text-xl font-black">$<?php echo number_format($precio, 2); ?></span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="md:col-span-3">
                    <div class="bg-[var(--panel-bg)] border border-[var(--border-color)] rounded-xl p-6">
                        <div class="text-[10px] uppercase text-stone-500 font-mono mb-4 tracking-widest">Selecciona método de pago</div>

                        <div class="flex gap-2 mb-6 flex-wrap">
                            <?php foreach ($metodos as $key => $met): ?>
                            <a href="pasarela_pago?<?php echo $esSuscripcion ? 'plan=' . $planId : 'curso=' . $cursoId; ?>&metodo=<?php echo $key; ?>" class="px-4 py-2 rounded-lg text-xs font-mono font-bold uppercase tracking-wider transition-all <?php echo $metodoSeleccionado === $key ? 'bg-accent text-black' : 'bg-white/5 text-stone-400 hover:text-white border border-white/10'; ?>">
                                <?php echo $met['icono'] . ' ' . $met['nombre']; ?>
                            </a>
                            <?php endforeach; ?>
                        </div>

                        <?php $met = $metodos[$metodoSeleccionado] ?? null;
                        if ($met): ?>
                        <div class="bg-black/30 rounded-lg p-4 mb-6 border border-white/5">
                            <h4 class="font-['Orbitron'] text-white text-xs font-bold mb-3"><?php echo $met['icono'] . ' ' . htmlspecialchars($met['nombre']); ?></h4>
                            <div class="space-y-1">
                                <?php foreach ($met['instrucciones'] as $instr): ?>
                                <p class="text-stone-400 text-xs font-mono"><?php echo htmlspecialchars($instr); ?></p>
                                <?php endforeach; ?>
                            </div>
                            <p class="text-accent text-xs font-mono mt-3"><?php echo htmlspecialchars($met['nota']); ?></p>
                        </div>

                        <form method="POST" enctype="multipart/form-data" action="pasarela_pago">
                            <input type="hidden" name="metodo_pago" value="<?php echo htmlspecialchars($metodoSeleccionado); ?>">
                            <?php if ($esSuscripcion): ?>
                            <input type="hidden" name="plan_id" value="<?php echo $planId; ?>">
                            <?php else: ?>
                            <input type="hidden" name="curso_compra" value="<?php echo $cursoId; ?>">
                            <?php endif; ?>

                            <div class="mb-4">
                                <label class="text-[10px] uppercase text-stone-500 font-mono block mb-1">Referencia / Código de confirmación *</label>
                                <input type="text" name="referencia" required placeholder="Ej: 123456789 o hash de transacción" class="w-full px-4 py-3 rounded-xl text-white font-mono text-sm bg-white/5 border border-white/10 outline-none focus:border-accent">
                            </div>

                            <div class="mb-4">
                                <label class="text-[10px] uppercase text-stone-500 font-mono block mb-1">Comprobante de pago (opcional)</label>
                                <input type="file" name="comprobante" accept=".jpg,.jpeg,.png,.gif,.pdf" class="w-full px-4 py-3 rounded-xl text-white font-mono text-sm bg-white/5 border border-white/10 outline-none focus:border-accent file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0 file:bg-accent file:text-black file:font-bold file:text-xs">
                                <p class="text-stone-600 text-[10px] font-mono mt-1">JPG, PNG, GIF o PDF. Máx 5MB.</p>
                            </div>

                            <div class="mb-6">
                                <label class="text-[10px] uppercase text-stone-500 font-mono block mb-1">Notas adicionales (opcional)</label>
                                <textarea name="notas_estudiante" rows="2" class="w-full px-4 py-3 rounded-xl text-white font-mono text-sm bg-white/5 border border-white/10 outline-none focus:border-accent" placeholder="Alguna información adicional sobre tu pago..."></textarea>
                            </div>

                            <button type="submit" class="btn-continuar" style="width:100%;justify-content:center;padding:0.8rem;">
                                ENVIAR COMPROBANTE DE PAGO
                            </button>
                        </form>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <?php endif; ?>
            <?php endif; ?>
        </main>
        <?php require 'partials/chatbot.php'; ?>
    </div>
</body>
</html>
