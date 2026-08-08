<?php
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/config/app.php';

if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_rol'] !== 'estudiante') {
    header('Location: ' . BASE_URL . 'academia');
    exit;
}

$usuarioId = $_SESSION['usuario_id'];
$plan = $_SESSION['usuario_plan'];
$mensaje = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accion = $_POST['accion'] ?? '';

    if ($accion === 'datos') {
        $nuevoNombre = trim($_POST['nombre'] ?? '');
        $nuevoEmail = trim($_POST['email'] ?? '');
        $nuevoTelefono = trim($_POST['telefono'] ?? '');
        $nuevoPais = trim($_POST['pais'] ?? '');
        $nuevaPassword = $_POST['password'] ?? '';

        if ($nuevoNombre === '' || !filter_var($nuevoEmail, FILTER_VALIDATE_EMAIL)) {
            $error = 'El nombre y un email válido son obligatorios.';
        } else {
            $stmt = $pdo->prepare('SELECT id FROM usuarios WHERE email = ? AND id != ?');
            $stmt->execute([$nuevoEmail, $usuarioId]);
            if ($stmt->fetch()) {
                $error = 'Ese email ya está registrado por otro usuario.';
            } elseif ($nuevaPassword !== '' && strlen($nuevaPassword) < 6) {
                $error = 'La contraseña debe tener al menos 6 caracteres.';
            } else {
                if ($nuevaPassword !== '') {
                    $stmt = $pdo->prepare('UPDATE usuarios SET nombre = ?, email = ?, telefono = ?, pais = ?, password = ? WHERE id = ?');
                    $stmt->execute([$nuevoNombre, $nuevoEmail, $nuevoTelefono, $nuevoPais, password_hash($nuevaPassword, PASSWORD_DEFAULT), $usuarioId]);
                } else {
                    $stmt = $pdo->prepare('UPDATE usuarios SET nombre = ?, email = ?, telefono = ?, pais = ? WHERE id = ?');
                    $stmt->execute([$nuevoNombre, $nuevoEmail, $nuevoTelefono, $nuevoPais, $usuarioId]);
                }
                $_SESSION['usuario_nombre'] = $nuevoNombre;
                $_SESSION['usuario_email'] = $nuevoEmail;
                $mensaje = 'Perfil actualizado correctamente.';
            }
        }
    }

    if ($accion === 'avatar' && isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
        $tmp = $_FILES['avatar']['tmp_name'];
        $permitidas = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
        $ext = strtolower(pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION));
        $maxBytes = 4 * 1024 * 1024;

        if (!in_array($ext, $permitidas)) {
            $error = 'Formato de imagen no permitido (usa JPG, PNG, WEBP o GIF).';
        } elseif ($_FILES['avatar']['size'] > $maxBytes) {
            $error = 'La imagen supera los 4 MB.';
        } else {
            $info = @getimagesize($tmp);
            if (!$info) {
                $error = 'No se pudo leer la imagen.';
            } else {
                $src = null;
                switch ($info[2]) {
                    case IMAGETYPE_JPEG: $src = @imagecreatefromjpeg($tmp); break;
                    case IMAGETYPE_PNG:  $src = @imagecreatefrompng($tmp); break;
                    case IMAGETYPE_GIF:  $src = @imagecreatefromgif($tmp); break;
                    case IMAGETYPE_WEBP:
                        if (function_exists('imagecreatefromwebp')) { $src = @imagecreatefromwebp($tmp); }
                        break;
                }
                if (!$src) {
                    $error = 'No se pudo procesar la imagen.';
                } else {
                    $imgW = imagesx($src);
                    $imgH = imagesy($src);
                    $ratio = min(1, 480 / $imgW, 480 / $imgH);
                    if ($ratio < 1) {
                        $nw = (int) round($imgW * $ratio);
                        $nh = (int) round($imgH * $ratio);
                        $res = imagecreatetruecolor($nw, $nh);
                        imagealphablending($res, false);
                        imagesavealpha($res, true);
                        imagecopyresampled($res, $src, 0, 0, 0, 0, $nw, $nh, $imgW, $imgH);
                        imagedestroy($src);
                        $src = $res;
                    }
                    $carpeta = __DIR__ . '/uploads/avatars';
                    if (!is_dir($carpeta)) { mkdir($carpeta, 0755, true); }

                    if (function_exists('imagewebp')) {
                        $nombre = 'avatar_' . $usuarioId . '_' . uniqid() . '.webp';
                        imagewebp($src, $carpeta . '/' . $nombre, 80);
                    } else {
                        $nombre = 'avatar_' . $usuarioId . '_' . uniqid() . '.jpg';
                        imagejpeg($src, $carpeta . '/' . $nombre, 85);
                    }
                    imagedestroy($src);

                    $stmt = $pdo->prepare('UPDATE usuarios SET avatar = ? WHERE id = ?');
                    $stmt->execute(['uploads/avatars/' . $nombre, $usuarioId]);
                    $_SESSION['usuario_avatar'] = 'uploads/avatars/' . $nombre;
                    $mensaje = 'Imagen de perfil actualizada.';
                }
            }
        }
    }

    if ($error === '' && $mensaje === '') {
        $mensaje = 'Perfil actualizado correctamente.';
    }
}

$stmt = $pdo->prepare('SELECT * FROM usuarios WHERE id = ?');
$stmt->execute([$usuarioId]);
$usuario = $stmt->fetch();

if (!$usuario) {
    die('Usuario no encontrado');
}

// Plan / vencimiento
$suscripcionExpira = $_SESSION['suscripcion_expira'] ?? $usuario['suscripcion_expira'] ?? null;
$diasRestantes = 0;
$planLabel = '';
if ($plan === 'suscripcion' && $suscripcionExpira) {
    $diasRestantes = max(0, floor((strtotime($suscripcionExpira) - time()) / 86400));
    $stmt = $pdo->prepare("SELECT monto FROM pagos WHERE usuario_id = ? AND tipo = 'suscripcion' AND estado = 'completado' ORDER BY fecha_pago DESC LIMIT 1");
    $stmt->execute([$usuarioId]);
    $ultPago = $stmt->fetchColumn();
    if ($ultPago) {
        $mapaMeses = [40 => '1 Mes', 110 => '3 Meses', 190 => '6 Meses', 380 => '1 Año'];
        $planLabel = $mapaMeses[(int)$ultPago] ?? '';
    }
}

// Cursos cursando
$stmt = $pdo->prepare("SELECT c.*, i.estado as inscripcion_estado, i.tipo as inscripcion_tipo,
    (SELECT COUNT(*) FROM lecciones WHERE curso_id = c.id) as total_lecciones,
    (SELECT COUNT(*) FROM progreso_lecciones pl JOIN lecciones l ON pl.leccion_id = l.id WHERE l.curso_id = c.id AND pl.usuario_id = ? AND pl.completado = 1) as lecciones_completadas
    FROM inscripciones i JOIN cursos c ON i.curso_id = c.id
    WHERE i.usuario_id = ? AND i.estado = 'activa' AND c.activo = 1
    ORDER BY i.fecha_inscripcion DESC");
$stmt->execute([$usuarioId, $usuarioId]);
$misCursos = $stmt->fetchAll();

// Logros reales basados en datos de la plataforma
$stmt = $pdo->prepare('SELECT * FROM ebook_progreso WHERE usuario_id = ? ORDER BY actualizado_en DESC');
$stmt->execute([$usuarioId]);
$ebookProgresos = $stmt->fetchAll();

$xpEbooks = 0;
$quizAciertosTotal = 0;
foreach ($ebookProgresos as $ep) {
    $xpEbooks += (int)$ep['xp'];
    $quizAciertosTotal += (int)$ep['quiz_aciertos'];
}
$ebooksLeidos = count($ebookProgresos);

$stmt = $pdo->prepare('SELECT COUNT(*) FROM progreso_lecciones WHERE usuario_id = ? AND completado = 1');
$stmt->execute([$usuarioId]);
$clasesCompletadas = (int)$stmt->fetchColumn();

$stmt = $pdo->prepare('SELECT COUNT(*) FROM entregas WHERE usuario_id = ?');
$stmt->execute([$usuarioId]);
$entregasEnviadas = (int)$stmt->fetchColumn();

$stmt = $pdo->prepare("SELECT COUNT(*) FROM entregas WHERE usuario_id = ? AND estado = 'aprobado'");
$stmt->execute([$usuarioId]);
$entregasAprobadas = (int)$stmt->fetchColumn();

$cursoCompleto = false;
foreach ($misCursos as $curso) {
    if ((int)$curso['total_lecciones'] > 0 && (int)$curso['lecciones_completadas'] >= (int)$curso['total_lecciones']) {
        $cursoCompleto = true;
        break;
    }
}
$esSuscriptor = ($plan === 'suscripcion');

$stats = [
    'clases'         => $clasesCompletadas,
    'ebooks'         => $ebooksLeidos,
    'quizzes'        => $quizAciertosTotal,
    'entregas'       => $entregasEnviadas,
    'aprobadas'      => $entregasAprobadas,
    'suscriptor'     => $esSuscriptor,
    'curso_completo' => $cursoCompleto,
];

$catalogoLogros = [
    'primera_clase'  => ['nombre' => 'Primera Clase',             'icono' => 'fa-solid fa-flag-checkered', 'desc' => 'Completar 1 clase del programa', 'regla' => ['clave' => 'clases', 'min' => 1]],
    'clases_10'      => ['nombre' => 'Estudiante Constante',      'icono' => 'fa-solid fa-bullseye',       'desc' => 'Completar 10 clases del programa', 'regla' => ['clave' => 'clases', 'min' => 10]],
    'clases_25'      => ['nombre' => 'Maratonista',               'icono' => 'fa-solid fa-award',          'desc' => 'Completar 25 clases del programa', 'regla' => ['clave' => 'clases', 'min' => 25]],
    'primer_ebook'   => ['nombre' => 'Explorador Digital',        'icono' => 'fa-solid fa-book-open',     'desc' => 'Leer 1 e-book interactivo', 'regla' => ['clave' => 'ebooks', 'min' => 1]],
    'ebooks_3'       => ['nombre' => 'Bibliófilo Digital',        'icono' => 'fa-solid fa-book',          'desc' => 'Leer 3 e-books interactivos', 'regla' => ['clave' => 'ebooks', 'min' => 3]],
    'primer_quiz'    => ['nombre' => 'Mente Curiosa',             'icono' => 'fa-solid fa-lightbulb',     'desc' => 'Responder 1 quiz correctamente', 'regla' => ['clave' => 'quizzes', 'min' => 1]],
    'quizzes_10'     => ['nombre' => 'Maestro de Quizzes',        'icono' => 'fa-solid fa-brain',         'desc' => 'Responder 10 quizzes correctamente', 'regla' => ['clave' => 'quizzes', 'min' => 10]],
    'primer_entrega' => ['nombre' => 'Primera Entrega',           'icono' => 'fa-solid fa-paper-plane',   'desc' => 'Enviar 1 actividad a tu profesor', 'regla' => ['clave' => 'entregas', 'min' => 1]],
    'entregas_5'     => ['nombre' => 'Estudiante Comprometido',   'icono' => 'fa-solid fa-file-pen',      'desc' => 'Enviar 5 actividades a tu profesor', 'regla' => ['clave' => 'entregas', 'min' => 5]],
    'aprobado'       => ['nombre' => 'Aprobado por la Maestría',  'icono' => 'fa-solid fa-circle-check',  'desc' => 'Tener 1 entrega aprobada por un profesor', 'regla' => ['clave' => 'aprobadas', 'min' => 1]],
    'curso_completo' => ['nombre' => 'Graduado',                  'icono' => 'fa-solid fa-user-graduate', 'desc' => 'Completar el 100% de un curso', 'regla' => ['clave' => 'curso_completo', 'min' => 1]],
    'suscriptor'     => ['nombre' => 'Miembro Elite',             'icono' => 'fa-solid fa-gem',           'desc' => 'Tener una suscripción activa', 'regla' => ['clave' => 'suscriptor', 'min' => 1]],
];

$logrosGanados = [];
foreach ($catalogoLogros as $key => $l) {
    $valor = $stats[$l['regla']['clave']] ?? 0;
    if (is_bool($valor)) {
        if ($valor) { $logrosGanados[] = $key; }
    } elseif ($valor >= $l['regla']['min']) {
        $logrosGanados[] = $key;
    }
}

$xpTotal = $xpEbooks + $clasesCompletadas * 10 + $entregasAprobadas * 20 + $quizAciertosTotal * 5;
$nivelTotal = floor($xpTotal / 100) + 1;
$xpNivelActual = $xpTotal % 100;
$logroKeys = array_keys($catalogoLogros);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Perfil | Salvatechnology Academy</title>
    <base href="<?= BASE_URL ?>">
    <link rel="icon" type="image/webp" href="img/logo.webp">
    <link rel="stylesheet" href="css/dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'accent': '#ff8c00',
                        'dark-bg': '#0a0a0a',
                    }
                }
            }
        }
    </script>
</head>
<body class="dashboard-body">
    <div class="scanlines"></div>
    <?php require 'partials/matrix-rain.php'; ?>

    <div class="dashboard-layout">
        <aside class="dash-sidebar">
            <div class="logo-side">
                <a href="<?= BASE_URL ?>"><img src="img/logo.webp" alt="Salva Technology"></a>
            </div>
            <div class="user-badge">
                <?php if ($usuario['avatar']): ?>
                    <img src="<?= htmlspecialchars($usuario['avatar']) ?>" alt="Foto" class="avatar-img">
                <?php else: ?>
                    <div class="avatar"><?php echo strtoupper(substr($usuario['nombre'], 0, 1)); ?></div>
                <?php endif; ?>
                <div class="name"><?php echo htmlspecialchars($usuario['nombre']); ?></div>
                <span class="plan-badge plan-<?php echo $plan; ?>"><?php echo $plan === 'suscripcion' ? 'SUSCRIPCIÓN' : 'GRATUITO'; ?></span>
            </div>
            <nav class="dash-nav">
                <a href="dashboard">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    Dashboard
                </a>
                <a href="cursos">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                    Explorar Cursos
                </a>
                <a href="planes">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    Planes
                </a>
                <a href="perfil" class="active">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    Mi Perfil
                </a>
                <a href="logout">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    Cerrar Sesión
                </a>
            </nav>
        </aside>

        <main class="dash-main">
            <div class="dash-header">
                <h1>Mi <span>Perfil</span></h1>
                <div class="header-actions">
                    <span class="text-stone-600 text-xs font-mono"><?php echo date('d.m.Y'); ?></span>
                </div>
            </div>

            <?php if ($mensaje): ?>
            <div class="bg-green-500/10 border border-green-500/30 rounded-xl p-4 mb-5 flex items-center gap-3">
                <span class="text-2xl">✅</span>
                <p class="text-green-400 text-xs font-mono"><?php echo htmlspecialchars($mensaje); ?></p>
            </div>
            <?php endif; ?>
            <?php if ($error): ?>
            <div class="bg-red-500/10 border border-red-500/30 rounded-xl p-4 mb-5 flex items-center gap-3">
                <span class="text-2xl">⚠️</span>
                <p class="text-red-400 text-xs font-mono"><?php echo htmlspecialchars($error); ?></p>
            </div>
            <?php endif; ?>

            <!-- Tarjeta de identidad -->
            <div class="bg-[var(--panel-bg)] border border-[var(--border-color)] rounded-xl p-6 mb-6">
                <div class="flex flex-col md:flex-row items-center gap-6">
                    <div class="relative">
                        <?php if ($usuario['avatar']): ?>
                            <img src="<?= htmlspecialchars($usuario['avatar']) ?>" alt="Foto de perfil" class="w-28 h-28 rounded-2xl object-cover border-2 border-[var(--accent)]">
                        <?php else: ?>
                            <div class="w-28 h-28 rounded-2xl bg-gradient-to-br from-accent to-orange-600 flex items-center justify-center text-4xl font-bold text-black" style="font-family:'Orbitron',sans-serif;">
                                <?php echo strtoupper(substr($usuario['nombre'], 0, 1)); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="flex-1 text-center md:text-left">
                        <h2 class="font-['Orbitron'] text-white text-xl font-bold"><?php echo htmlspecialchars($usuario['nombre']); ?></h2>
                        <p class="text-stone-400 text-xs font-mono mt-1"><?php echo htmlspecialchars($usuario['email']); ?></p>
                        <div class="flex flex-wrap gap-2 mt-3 justify-center md:justify-start">
                            <span class="plan-badge plan-<?php echo $plan; ?>"><?php echo $plan === 'suscripcion' ? 'PLAN SUSCRIPCIÓN' : 'PLAN GRATUITO'; ?></span>
                            <?php if ($usuario['telefono']): ?><span class="text-stone-500 text-[10px] font-mono border border-white/10 rounded px-2 py-0.5">📱 <?= htmlspecialchars($usuario['telefono']) ?></span><?php endif; ?>
                            <?php if ($usuario['pais']): ?><span class="text-stone-500 text-[10px] font-mono border border-white/10 rounded px-2 py-0.5">🌍 <?= htmlspecialchars($usuario['pais']) ?></span><?php endif; ?>
                        </div>
                    </div>
                    <div class="bg-white/5 border border-[var(--border-color)] rounded-xl p-4 text-center min-w-[180px]">
                        <p class="text-stone-500 text-[10px] font-mono uppercase tracking-wider">Plan actual</p>
                        <p class="font-['Orbitron'] text-accent text-lg font-bold mt-1"><?php echo $planLabel ?: ($plan === 'suscripcion' ? 'Suscripción' : 'Gratuito'); ?></p>
                        <?php if ($plan === 'suscripcion' && $suscripcionExpira): ?>
                            <p class="text-stone-400 text-[10px] font-mono mt-1">Vence el <?php echo date('d/m/Y', strtotime($suscripcionExpira)); ?></p>
                            <p class="mt-2 <?php echo $diasRestantes <= 7 ? 'text-red-400' : 'text-green-400'; ?> text-base font-bold"><?php echo $diasRestantes; ?> días</p>
                            <?php if ($diasRestantes <= 15): ?>
                            <a href="planes" class="btn-explorar" style="padding:0.4rem 1rem;font-size:0.55rem;margin-top:0.5rem;display:inline-flex;">RENOVAR</a>
                            <?php endif; ?>
                        <?php else: ?>
                            <p class="text-yellow-400 text-[10px] font-mono mt-1">Suscríbete para acceso total</p>
                            <a href="planes" class="btn-explorar" style="padding:0.4rem 1rem;font-size:0.55rem;margin-top:0.5rem;display:inline-flex;">VER PLANES</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Editar perfil + Fotos -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
                <div class="lg:col-span-2 bg-[var(--panel-bg)] border border-[var(--border-color)] rounded-xl p-6">
                    <h3 class="font-['Orbitron'] text-white text-sm font-bold mb-4"><i class="fa-solid fa-user-gear mr-2" style="color:var(--accent);"></i>Editar Mis Datos</h3>
                    <form method="POST" class="space-y-4">
                        <input type="hidden" name="accion" value="datos">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="text-[10px] uppercase text-stone-500 font-mono block mb-1">Nombre completo</label>
                                <input type="text" name="nombre" value="<?= htmlspecialchars($usuario['nombre']) ?>" required class="w-full px-4 py-3 bg-black/50 border border-white/10 rounded-lg text-white text-sm font-mono outline-none focus:border-accent transition-colors">
                            </div>
                            <div>
                                <label class="text-[10px] uppercase text-stone-500 font-mono block mb-1">Email</label>
                                <input type="email" name="email" value="<?= htmlspecialchars($usuario['email']) ?>" required class="w-full px-4 py-3 bg-black/50 border border-white/10 rounded-lg text-white text-sm font-mono outline-none focus:border-accent transition-colors">
                            </div>
                            <div>
                                <label class="text-[10px] uppercase text-stone-500 font-mono block mb-1">Teléfono</label>
                                <input type="text" name="telefono" value="<?= htmlspecialchars($usuario['telefono'] ?? '') ?>" class="w-full px-4 py-3 bg-black/50 border border-white/10 rounded-lg text-white text-sm font-mono outline-none focus:border-accent transition-colors">
                            </div>
                            <div>
                                <label class="text-[10px] uppercase text-stone-500 font-mono block mb-1">País</label>
                                <input type="text" name="pais" value="<?= htmlspecialchars($usuario['pais'] ?? '') ?>" class="w-full px-4 py-3 bg-black/50 border border-white/10 rounded-lg text-white text-sm font-mono outline-none focus:border-accent transition-colors">
                            </div>
                        </div>
                        <div>
                            <label class="text-[10px] uppercase text-stone-500 font-mono block mb-1">Nueva contraseña <span class="text-stone-600 normal-case">(déjala vacía para no cambiarla)</span></label>
                            <input type="password" name="password" class="w-full px-4 py-3 bg-black/50 border border-white/10 rounded-lg text-white text-sm font-mono outline-none focus:border-accent transition-colors" autocomplete="new-password">
                        </div>
                        <button type="submit" class="btn-continuar" style="margin-top:0.25rem;">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            GUARDAR CAMBIOS
                        </button>
                    </form>
                </div>

                <div class="bg-[var(--panel-bg)] border border-[var(--border-color)] rounded-xl p-6">
                    <h3 class="font-['Orbitron'] text-white text-sm font-bold mb-4"><i class="fa-solid fa-image mr-2" style="color:var(--accent);"></i>Foto de Perfil</h3>
                    <form method="POST" enctype="multipart/form-data" class="space-y-3" id="avatarForm">
                        <input type="hidden" name="accion" value="avatar">
                        <div class="relative w-24 h-24 mx-auto mb-3">
                            <img id="avatarPreview" alt="Vista previa" class="w-24 h-24 rounded-2xl object-cover border-2 border-[var(--accent)] hidden">
                            <?php if ($usuario['avatar']): ?>
                            <img src="<?= htmlspecialchars($usuario['avatar']) ?>" alt="Avatar" id="avatarActual" class="w-24 h-24 rounded-2xl object-cover border-2 border-[var(--accent)]">
                            <?php endif; ?>
                        </div>
                        <input type="file" name="avatar" id="avatarInput" accept="image/*" class="w-full text-xs text-stone-400 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-accent file:text-black file:font-bold file:text-xs hover:file:bg-orange-600 transition-all">
                        <p class="text-stone-600 text-[10px] font-mono">JPG, PNG, WEBP o GIF · Se comprime a WebP automáticamente</p>
                        <button type="submit" class="btn-explorar" style="width:100%;">SUBIR IMAGEN</button>
                    </form>
                </div>
            </div>

            <!-- Logros y experiencia -->
            <div class="bg-[var(--panel-bg)] border border-[var(--border-color)] rounded-xl p-6 mb-6">
                <div class="flex items-center justify-between flex-wrap gap-3 mb-4">
                    <h3 class="font-['Orbitron'] text-white text-sm font-bold"><i class="fa-solid fa-trophy mr-2" style="color:var(--accent);"></i>Logros y Experiencia</h3>
                    <?php if (count($ebookProgresos) > 0): ?>
                    <span class="text-stone-500 text-[10px] font-mono"><?php echo count($ebookProgresos); ?> e-book(s) leído(s)</span>
                    <?php endif; ?>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6 text-center">
                    <div class="bg-black/50 border border-white/10 rounded-xl p-4">
                        <p class="text-3xl font-bold text-accent" id="xp-total"><?php echo number_format($xpTotal); ?></p>
                        <p class="text-stone-400 text-[10px] font-mono mt-1 uppercase">XP Total</p>
                    </div>
                    <div class="bg-black/50 border border-white/10 rounded-xl p-4">
                        <p class="text-3xl font-bold text-[#00f0ff]" id="nivel-total"><?php echo $nivelTotal; ?></p>
                        <p class="text-stone-400 text-[10px] font-mono mt-1 uppercase">Nivel</p>
                    </div>
                    <div class="bg-black/50 border border-white/10 rounded-xl p-4">
                        <p class="text-3xl font-bold text-green-400"><?php echo $quizAciertosTotal; ?></p>
                        <p class="text-stone-400 text-[10px] font-mono mt-1 uppercase">Quiz Correctos</p>
                    </div>
                    <div class="bg-black/50 border border-white/10 rounded-xl p-4">
                        <p class="text-3xl font-bold text-yellow-400"><?php echo count($logrosGanados); ?>/<?php echo count($catalogoLogros); ?></p>
                        <p class="text-stone-400 text-[10px] font-mono mt-1 uppercase">Logros</p>
                    </div>
                </div>

                <?php if ($xpTotal > 0): ?>
                <div class="mb-6">
                    <div class="flex justify-between text-[10px] font-mono mb-1">
                        <span class="text-stone-500">Progreso al nivel <?php echo $nivelTotal + 1; ?> </span>
                        <span class="text-accent"><?php echo $xpNivelActual; ?>/100 XP</span>
                    </div>
                    <div class="progress-bar-track"><div class="progress-bar-fill" style="width:<?php echo min(100, $xpNivelActual); ?>%"></div></div>
                </div>
                <?php endif; ?>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3" id="logros-grid">
                    <?php foreach ($logroKeys as $key): $l = $catalogoLogros[$key]; $ganado = in_array($key, $logrosGanados); $regla = $l['regla']; $valor = $stats[$regla['clave']] ?? 0; ?>
                    <div class="rounded-xl p-4 border transition flex items-center gap-3 <?php echo $ganado ? 'bg-yellow-500/10 border-yellow-500/30' : 'bg-black/30 border-white/5 opacity-60'; ?>">
                        <span class="text-xl <?php echo $ganado ? 'text-accent' : 'text-stone-600'; ?>"><i class="<?php echo $l['icono']; ?>"></i></span>
                        <div class="flex-1">
                            <p class="font-['Orbitron'] text-xs font-bold <?php echo $ganado ? 'text-yellow-400' : 'text-stone-500'; ?>"><?php echo $l['nombre']; ?></p>
                            <p class="font-['Fira Code'] text-[10px] <?php echo $ganado ? 'text-stone-400' : 'text-stone-600'; ?>"><?php echo $l['desc']; ?></p>
                            <?php if (!$ganado && !is_bool($valor)): ?>
                            <p class="text-[10px] font-mono text-accent mt-1">Progreso: <?php echo $valor; ?>/<?php echo $regla['min']; ?></p>
                            <?php endif; ?>
                        </div>
                        <?php if ($ganado): ?><span class="text-green-400 text-sm"><i class="fa-solid fa-lock-open"></i></span><?php else: ?><span class="text-stone-600 text-sm"><i class="fa-solid fa-lock"></i></span><?php endif; ?>
                    </div>
                    <?php endforeach; ?>
                </div>

                <?php if (count($ebookProgresos) > 0): ?>
                <div class="mt-6 border-t border-white/10 pt-4">
                    <h4 class="text-[10px] uppercase text-stone-500 font-mono mb-3">Detalle por e-book</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <?php foreach ($ebookProgresos as $ep): ?>
                        <div class="bg-black/40 border border-white/10 rounded-lg p-3">
                            <div class="flex justify-between items-center">
                                <span class="font-['Orbitron'] text-xs text-white font-bold">📕 <?php echo htmlspecialchars($ep['ebook_key']); ?></span>
                                <span class="text-[10px] font-mono text-accent">Nv. <?php echo $ep['level']; ?></span>
                            </div>
                            <div class="mt-2 flex items-center gap-2">
                                <div class="progress-bar-track flex-1"><div class="progress-bar-fill" style="width:<?php echo min(100, ($ep['xp'] % 100)); ?>%"></div></div>
                                <span class="text-[10px] font-mono text-stone-400"><?php echo number_format($ep['xp']); ?> XP</span>
                            </div>
                            <p class="text-[10px] font-mono text-stone-500 mt-1"><?php echo $ep['quiz_aciertos']; ?> quiz correctos · <?php echo count((array)json_decode($ep['logros'] ?? '[]', true)); ?> logros</p>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>

            <!-- Cursos cursando -->
            <div class="bg-[var(--panel-bg)] border border-[var(--border-color)] rounded-xl p-6 mb-6">
                <h3 class="font-['Orbitron'] text-white text-sm font-bold mb-4"><i class="fa-solid fa-graduation-cap mr-2" style="color:var(--accent);"></i>Mis Cursos en Curso</h3>
                <?php if (count($misCursos) > 0): ?>
                <div class="course-feed">
                    <?php foreach ($misCursos as $curso):
                        $progreso = $curso['total_lecciones'] > 0 ? round(($curso['lecciones_completadas'] / $curso['total_lecciones']) * 100) : 0;
                        $badgeClass = $curso['inscripcion_tipo'] === 'gratuito' ? 'badge-gratis' : ($curso['inscripcion_tipo'] === 'suscripcion' ? 'badge-suscripcion' : 'badge-pagado');
                        $badgeLabel = $curso['inscripcion_tipo'] === 'gratuito' ? 'GRATIS' : ($curso['inscripcion_tipo'] === 'suscripcion' ? 'SUSCRIPCIÓN' : 'PAGADO');
                    ?>
                    <div class="course-card">
                        <div class="card-top">
                            <div>
                                <div class="card-title"><?php echo htmlspecialchars($curso['titulo']); ?></div>
                                <div class="card-meta">
                                    <span class="badge <?php echo $badgeClass; ?>"><?php echo $badgeLabel; ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="progress-section">
                            <div class="progress-bar-track"><div class="progress-bar-fill" style="width:<?php echo $progreso; ?>%"></div></div>
                            <div class="progress-label">
                                <span>PROGRESO</span>
                                <span><?php echo $curso['lecciones_completadas']; ?>/<?php echo $curso['total_lecciones']; ?> lecciones (<?php echo $progreso; ?>%)</span>
                            </div>
                        </div>
                        <div class="card-actions">
                            <a href="curso/<?php echo $curso['id']; ?>" class="btn-continuar">▶ Continuar</a>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php else: ?>
                <div class="empty-state">
                    <p class="text-stone-500 text-sm">No estás cursando ningún curso actualmente.</p>
                    <a href="cursos" class="btn-explorar" style="display:inline-flex;margin-top:0.75rem;">EXPLORAR CURSOS</a>
                </div>
                <?php endif; ?>
            </div>
        </main>
        <?php require 'partials/chatbot.php'; ?>
    </div>

    <script src="js/matrix-rain.js"></script>
    <script>
        (function () {
            var input = document.getElementById('avatarInput');
            var preview = document.getElementById('avatarPreview');
            var actual = document.getElementById('avatarActual');
            if (!input || !preview) return;

            input.addEventListener('change', function () {
                var file = input.files && input.files[0];
                if (!file || file.type.indexOf('image/') !== 0) return;

                var url = URL.createObjectURL(file);
                var img = new Image();
                img.onload = function () {
                    try {
                        var max = 480;
                        var w = img.width, h = img.height;
                        var r = Math.min(1, max / w, max / h);
                        if (r < 1) { w = Math.round(w * r); h = Math.round(h * r); }
                        var canvas = document.createElement('canvas');
                        canvas.width = w;
                        canvas.height = h;
                        var ctx = canvas.getContext('2d');
                        ctx.clearRect(0, 0, w, h);
                        ctx.drawImage(img, 0, 0, w, h);
                        canvas.toBlob(function (blob) {
                            if (blob && window.DataTransfer) {
                                var dt = new DataTransfer();
                                dt.items.add(new File([blob], 'avatar.webp', { type: 'image/webp' }));
                                input.files = dt.files;
                            }
                            preview.src = url;
                            preview.classList.remove('hidden');
                            if (actual) actual.classList.add('hidden');
                        }, 'image/webp', 0.82);
                    } catch (e) {
                        preview.src = url;
                        preview.classList.remove('hidden');
                        if (actual) actual.classList.add('hidden');
                    }
                };
                img.src = url;
            });
        })();
    </script>
</body>
</html>