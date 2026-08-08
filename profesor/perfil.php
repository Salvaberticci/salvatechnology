<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../helpers/config_sistema.php';

if (!isset($_SESSION['usuario_id']) || ($_SESSION['usuario_rol'] !== 'profesor' && $_SESSION['usuario_rol'] !== 'admin')) {
    header('Location: ' . BASE_URL . 'academia');
    exit;
}

$usuarioId = $_SESSION['usuario_id'];
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
                    $carpeta = __DIR__ . '/../uploads/avatars';
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
        $mensaje = 'No se realizaron cambios.';
    }
}

$stmt = $pdo->prepare('SELECT * FROM usuarios WHERE id = ?');
$stmt->execute([$usuarioId]);
$usuario = $stmt->fetch();
if (!$usuario) { die('Usuario no encontrado'); }

// Estadísticas del profesor
$totalCursos = $pdo->prepare("SELECT COUNT(*) FROM cursos WHERE profesor_id = ?");
$totalCursos->execute([$usuarioId]);
$totalCursos = $totalCursos->fetchColumn();

$totalEstudiantesCursos = $pdo->prepare("SELECT COUNT(DISTINCT i.usuario_id) FROM inscripciones i JOIN cursos c ON i.curso_id = c.id WHERE c.profesor_id = ?");
$totalEstudiantesCursos->execute([$usuarioId]);
$totalEstudiantesCursos = $totalEstudiantesCursos->fetchColumn();

$totalEntregas = $pdo->prepare("SELECT COUNT(*) FROM entregas e JOIN actividades a ON e.actividad_id = a.id JOIN lecciones l ON a.leccion_id = l.id JOIN cursos c ON l.curso_id = c.id WHERE c.profesor_id = ? AND e.estado = 'pendiente'");
$totalEntregas->execute([$usuarioId]);
$totalEntregas = $totalEntregas->fetchColumn();

$arrCursos = $pdo->prepare("SELECT c.id, c.titulo,
    (SELECT COUNT(*) FROM lecciones WHERE curso_id = c.id) AS total_lecciones,
    (SELECT COUNT(*) FROM inscripciones WHERE curso_id = c.id AND estado = 'activa') AS estudiantes_activos
    FROM cursos c WHERE c.profesor_id = ? ORDER BY c.id DESC");
$arrCursos->execute([$usuarioId]);
$arrCursos = $arrCursos->fetchAll();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Perfil | Profesor</title>
    <base href="<?= BASE_URL ?>">
    <link rel="icon" type="image/webp" href="img/logo.webp">
    <link rel="stylesheet" href="css/dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>tailwind.config={theme:{extend:{colors:{'accent':'#ff8c00','dark-bg':'#0a0a0a'}}}}</script>
</head>
<body class="dashboard-body">
    <div class="scanlines"></div>
    <div class="dashboard-layout">
        <aside class="dash-sidebar">
            <div class="logo-side"><a href="./"><img src="img/logo.webp" alt="Salva"></a></div>
            <div class="user-badge">
                <?php if ($usuario['avatar']): ?>
                    <img src="<?= htmlspecialchars($usuario['avatar']) ?>" alt="Foto" class="avatar-img">
                <?php else: ?>
                    <div class="avatar" style="background:#ff4444;color:#fff;"><?php echo strtoupper(substr($usuario['nombre'], 0, 1)); ?></div>
                <?php endif; ?>
                <div class="name"><?php echo htmlspecialchars($usuario['nombre']); ?></div>
                <span class="plan-badge" style="background:rgba(255,68,68,0.15);color:#ff4444;border-color:rgba(255,68,68,0.3);">PROFESOR</span>
            </div>
            <nav class="dash-nav">
                <a href="profesor"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6z"/></svg>Dashboard</a>
                <a href="profesor/cursos"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253"/></svg>Cursos</a>
                <a href="profesor/estudiantes"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857"/></svg>Estudiantes</a>
                <a href="profesor/entregas"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>Entregas</a>
                <a href="profesor/pagos"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>Pagos</a>
                <a href="profesor/perfil" class="active"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>Mi Perfil</a>
                <a href="profesor/admin.php"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.8 0-3 .6-3 1.5v.2c0 .5.3 1 .8 1.3.5.3 1.2.4 2 .4h.4c.8 0 1.5-.1 2-.4.5-.3.8-.8.8-1.3v-.2C15 8.6 13.8 8 12 8zm0 6c-2.5 0-4.5-1-4.5-3.5 0-2.5 2-3.8 4.5-3.8s4.5 1.3 4.5 3.8C16.5 13 14.5 14 12 14zm5 4.5c0-2.2-2.2-4-5-4s-5 1.8-5 4H17z"/></svg>Admin</a>
                <a href="logout"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>Cerrar</a>
            </nav>
        </aside>

        <main class="dash-main">
            <div class="dash-header">
                <h1>Mi <span>Perfil</span></h1>
                <span class="text-stone-600 text-xs font-mono">Profesor desde <?php echo date('d/m/Y', strtotime($usuario['creado_en'])); ?></span>
            </div>

            <?php if ($mensaje): ?>
            <div class="mb-6 px-4 py-3 rounded-xl border border-green-500/30 bg-green-500/10 text-green-300 text-sm font-mono"><?php echo htmlspecialchars($mensaje); ?></div>
            <?php endif; ?>
            <?php if ($error): ?>
            <div class="mb-6 px-4 py-3 rounded-xl border border-red-500/30 bg-red-500/10 text-red-300 text-sm font-mono"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
                <div class="bg-[var(--panel-bg)] border border-[var(--border-color)] rounded-xl p-5">
                    <div class="text-accent text-3xl font-black font-['Orbitron']"><?php echo $totalCursos; ?></div>
                    <div class="text-stone-500 text-xs font-mono mt-1 uppercase tracking-wider">Cursos creados</div>
                </div>
                <div class="bg-[var(--panel-bg)] border border-[var(--border-color)] rounded-xl p-5">
                    <div class="text-accent text-3xl font-black font-['Orbitron']"><?php echo $totalEstudiantesCursos; ?></div>
                    <div class="text-stone-500 text-xs font-mono mt-1 uppercase tracking-wider">Estudiantes en cursos</div>
                </div>
                <div class="bg-[var(--panel-bg)] border border-[var(--border-color)] rounded-xl p-5">
                    <div class="text-accent text-3xl font-black font-['Orbitron']"><?php echo $totalEntregas; ?></div>
                    <div class="text-stone-500 text-xs font-mono mt-1 uppercase tracking-wider">Entregas pendientes</div>
                </div>
                <div class="bg-[var(--panel-bg)] border border-[var(--border-color)] rounded-xl p-5">
                    <div class="text-accent text-3xl font-black font-['Orbitron']"><?php echo count($arrCursos); ?></div>
                    <div class="text-stone-500 text-xs font-mono mt-1 uppercase tracking-wider">Total cursos activos</div>
                </div>
            </div>

            <div class="grid md:grid-cols-2 gap-6">
                <div class="bg-[var(--panel-bg)] border border-[var(--border-color)] rounded-xl p-6">
                    <h3 class="font-['Orbitron'] text-white text-sm font-bold mb-4 uppercase tracking-wider">Datos personales</h3>
                    <form method="POST" class="space-y-4">
                        <input type="hidden" name="accion" value="datos">
                        <div>
                            <label class="text-[10px] uppercase text-stone-500 font-mono block mb-1">Nombre completo</label>
                            <input type="text" name="nombre" value="<?php echo htmlspecialchars($usuario['nombre']); ?>" class="w-full px-3 py-2 rounded-lg text-white font-mono text-sm bg-white/5 border border-white/10 outline-none focus:border-accent">
                        </div>
                        <div>
                            <label class="text-[10px] uppercase text-stone-500 font-mono block mb-1">Email</label>
                            <input type="email" name="email" value="<?php echo htmlspecialchars($usuario['email']); ?>" class="w-full px-3 py-2 rounded-lg text-white font-mono text-sm bg-white/5 border border-white/10 outline-none focus:border-accent">
                        </div>
                        <div>
                            <label class="text-[10px] uppercase text-stone-500 font-mono block mb-1">Teléfono</label>
                            <input type="text" name="telefono" value="<?php echo htmlspecialchars($usuario['telefono'] ?? ''); ?>" class="w-full px-3 py-2 rounded-lg text-white font-mono text-sm bg-white/5 border border-white/10 outline-none focus:border-accent">
                        </div>
                        <div>
                            <label class="text-[10px] uppercase text-stone-500 font-mono block mb-1">País</label>
                            <input type="text" name="pais" value="<?php echo htmlspecialchars($usuario['pais'] ?? ''); ?>" class="w-full px-3 py-2 rounded-lg text-white font-mono text-sm bg-white/5 border border-white/10 outline-none focus:border-accent">
                        </div>
                        <div>
                            <label class="text-[10px] uppercase text-stone-500 font-mono block mb-1">Nueva contraseña <span class="text-stone-600">(dejar vacío para no cambiar)</span></label>
                            <input type="password" name="password" class="w-full px-3 py-2 rounded-lg text-white font-mono text-sm bg-white/5 border border-white/10 outline-none focus:border-accent" placeholder="••••••••">
                        </div>
                        <button type="submit" class="btn-continuar" style="width:100%;justify-content:center;padding:0.6rem;">GUARDAR CAMBIOS</button>
                    </form>
                </div>

                <div class="bg-[var(--panel-bg)] border border-[var(--border-color)] rounded-xl p-6">
                    <h3 class="font-['Orbitron'] text-white text-sm font-bold mb-4 uppercase tracking-wider">Foto de perfil</h3>
                    <div class="flex items-center gap-4 mb-4">
                        <?php if ($usuario['avatar']): ?>
                            <img src="<?= htmlspecialchars($usuario['avatar']) ?>" alt="Foto" class="rounded-xl" style="width:96px;height:96px;object-fit:cover;border:1px solid var(--accent);">
                        <?php else: ?>
                            <div class="avatar" style="width:96px;height:96px;font-size:2rem;background:rgba(255,68,68,0.15);color:#ff4444;border:1px solid rgba(255,68,68,0.4);"><?php echo strtoupper(substr($usuario['nombre'], 0, 1)); ?></div>
                        <?php endif; ?>
                        <form method="POST" enctype="multipart/form-data" class="flex-1">
                            <input type="hidden" name="accion" value="avatar">
                            <input type="file" name="avatar" accept="image/*" class="w-full text-xs text-stone-500 mb-2">
                            <button type="submit" class="btn-continuar" style="padding:0.5rem 1rem;">SUBIR FOTO</button>
                        </form>
                    </div>

                    <h3 class="font-['Orbitron'] text-white text-sm font-bold mt-6 mb-4 uppercase tracking-wider">Mis cursos</h3>
                    <?php if ($arrCursos): ?>
                    <div class="space-y-2">
                        <?php foreach ($arrCursos as $c): ?>
                        <div style="padding:0.75rem 1rem;" class="bg-black/30 rounded-lg border border-white/5">
                            <div class="flex justify-between items-center gap-2">
                                <span class="text-sm text-white"><?php echo htmlspecialchars($c['titulo']); ?></span>
                                <span class="text-stone-500 text-xs font-mono"><?php echo (int)$c['estudiantes_activos']; ?> est. · <?php echo (int)$c['total_lecciones']; ?> cls</span>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <?php else: ?>
                    <p class="text-stone-500 text-sm">Aún no tienes cursos creados.</p>
                    <?php endif; ?>
                </div>
            </div>
        </main>
        <?php require __DIR__ . '/../partials/chatbot.php'; ?>
    </div>
</body>
</html>