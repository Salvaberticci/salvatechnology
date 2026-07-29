<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../config/app.php';

if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_rol'] !== 'profesor') {
    header('Location: ' . BASE_URL . 'academia');
    exit;
}

$cursoId = (int)($_GET['curso_id'] ?? 0);
$stmt = $pdo->prepare("SELECT * FROM cursos WHERE id = ? AND profesor_id = ?");
$stmt->execute([$cursoId, $_SESSION['usuario_id']]);
$curso = $stmt->fetch();

if (!$curso) {
    die("Curso no encontrado");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($_POST['accion'] === 'crear_leccion') {
        $titulo = trim($_POST['titulo']);
        $descripcion = trim($_POST['descripcion']);
        $videoUrl = trim($_POST['video_url']);
        $orden = (int)($_POST['orden'] ?? 0);
        $stmt = $pdo->prepare("INSERT INTO lecciones (curso_id, titulo, descripcion, video_url, orden) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$cursoId, $titulo, $descripcion, $videoUrl, $orden]);
        header('Location: ' . BASE_URL . 'profesor/lecciones/' . $cursoId);
        exit;
    }
    if ($_POST['accion'] === 'crear_actividad') {
        $leccionId = (int)$_POST['leccion_id'];
        $titulo = trim($_POST['titulo']);
        $descripcion = trim($_POST['descripcion']);
        $tipo = $_POST['tipo'];
        $stmt = $pdo->prepare("INSERT INTO actividades (leccion_id, titulo, descripcion, tipo) VALUES (?, ?, ?, ?)");
        $stmt->execute([$leccionId, $titulo, $descripcion, $tipo]);
        header('Location: ' . BASE_URL . 'profesor/lecciones/' . $cursoId);
        exit;
    }
    if ($_POST['accion'] === 'eliminar_leccion') {
        $stmt = $pdo->prepare("DELETE FROM lecciones WHERE id = ? AND curso_id = ?");
        $stmt->execute([(int)$_POST['id'], $cursoId]);
        header('Location: ' . BASE_URL . 'profesor/lecciones/' . $cursoId);
        exit;
    }
}

$stmt = $pdo->prepare("SELECT * FROM lecciones WHERE curso_id = ? ORDER BY orden ASC");
$stmt->execute([$cursoId]);
$lecciones = $stmt->fetchAll();

$actividades = [];
foreach ($lecciones as $l) {
    $stmt = $pdo->prepare("SELECT * FROM actividades WHERE leccion_id = ?");
    $stmt->execute([$l['id']]);
    $actividades[$l['id']] = $stmt->fetchAll();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lecciones: <?php echo htmlspecialchars($curso['titulo']); ?> | Profesor</title>
    <base href="<?= BASE_URL ?>">
    <link rel="stylesheet" href="css/dashboard.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>tailwind.config={theme:{extend:{colors:{'accent':'#ff8c00','dark-bg':'#0a0a0a'}}}}</script>
    <style>
        .leccion-card { transition: all 0.3s ease; }
        .leccion-card:hover { border-color: rgba(255,140,0,0.4); }
    </style>
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
                <a href="profesor/cursos"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>Cursos</a>
                <a href="profesor/estudiantes"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857"/></svg>Estudiantes</a>
                <a href="profesor/entregas"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>Entregas</a>
                <a href="profesor/pagos"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>Pagos</a>
            </nav>
        </aside>

        <main class="dash-main">
            <div class="dash-header">
                <div>
                    <h1><span>Lecciones:</span> <?php echo htmlspecialchars($curso['titulo']); ?></h1>
                    <a href="profesor/cursos" class="text-stone-500 hover:text-accent text-xs font-mono transition-colors">← Volver a cursos</a>
                </div>
                <button onclick="document.getElementById('modal-leccion').classList.remove('hidden')" class="btn-continuar">+ NUEVA LECCIÓN</button>
            </div>

            <?php if (count($lecciones) > 0): ?>
            <div class="course-feed">
                <?php foreach ($lecciones as $i => $leccion): ?>
                <div class="course-card leccion-card">
                    <div class="card-top">
                        <div>
                            <div class="card-title" style="font-size:0.9rem;">
                                <span class="text-accent">#<?php echo $i + 1; ?></span> <?php echo htmlspecialchars($leccion['titulo']); ?>
                            </div>
                            <div class="card-meta">
                                <?php if ($leccion['video_url']): ?>
                                <span class="badge badge-pagado">VIDEO</span>
                                <?php else: ?>
                                <span class="badge badge-pendiente">SIN VIDEO</span>
                                <?php endif; ?>
                                <span class="text-stone-600 text-xs font-mono">Orden: <?php echo $leccion['orden']; ?></span>
                            </div>
                        </div>
                        <form method="POST" onsubmit="return confirm('¿Eliminar esta lección?')">
                            <input type="hidden" name="accion" value="eliminar_leccion">
                            <input type="hidden" name="id" value="<?php echo $leccion['id']; ?>">
                            <button type="submit" class="text-red-500 hover:text-red-400 text-xs font-mono">ELIMINAR</button>
                        </form>
                    </div>

                    <div class="mt-3">
                        <div class="flex items-center justify-between mb-2">
                            <h4 class="text-stone-400 text-xs font-mono uppercase tracking-wider">Actividades</h4>
                            <button onclick="abrirModalActividad(<?php echo $leccion['id']; ?>)" class="text-accent hover:text-white text-xs font-mono transition-colors">+ Agregar actividad</button>
                        </div>
                        <?php if (!empty($actividades[$leccion['id']])): ?>
                        <div class="space-y-2">
                            <?php foreach ($actividades[$leccion['id']] as $act): ?>
                            <div class="bg-white/5 rounded-lg px-4 py-2 flex justify-between items-center border border-white/5">
                                <div>
                                    <span class="text-white text-xs font-bold"><?php echo htmlspecialchars($act['titulo']); ?></span>
                                    <span class="text-stone-600 text-[10px] ml-2 font-mono">[<?php echo $act['tipo']; ?>]</span>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                        <?php else: ?>
                        <p class="text-stone-600 text-xs font-mono">Sin actividades aún</p>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <?php else: ?>
            <div class="empty-state">
                <svg class="w-16 h-16 text-stone-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                <h3>Este curso no tiene lecciones</h3>
                <p>Crea la primera lección para empezar</p>
            </div>
            <?php endif; ?>
        </main>
        <?php require __DIR__ . '/../partials/chatbot.php'; ?>
    </div>

    <div id="modal-leccion" class="hidden fixed inset-0 flex items-center justify-center z-50 p-4" style="background:rgba(0,0,0,0.9);backdrop-filter:blur(5px);">
        <div class="bg-[#111] border border-accent/40 rounded-2xl p-8 max-w-lg w-full">
            <div class="flex justify-between items-center mb-6">
                <h2 class="font-['Orbitron'] text-white text-lg font-bold">Nueva Lección</h2>
                <button onclick="document.getElementById('modal-leccion').classList.add('hidden')" class="text-stone-500 hover:text-white text-2xl">&times;</button>
            </div>
            <form method="POST">
                <input type="hidden" name="accion" value="crear_leccion">
                <div class="space-y-4">
                    <div>
                        <label class="text-xs uppercase text-stone-500 font-mono block mb-1">Título</label>
                        <input type="text" name="titulo" required class="w-full px-4 py-3 rounded-xl text-white font-mono text-sm bg-white/5 border border-white/10 outline-none focus:border-accent">
                    </div>
                    <div>
                        <label class="text-xs uppercase text-stone-500 font-mono block mb-1">Descripción / Contenido</label>
                        <textarea name="descripcion" rows="4" class="w-full px-4 py-3 rounded-xl text-white font-mono text-sm bg-white/5 border border-white/10 outline-none focus:border-accent" placeholder="Texto de apoyo, enlaces, código..."></textarea>
                    </div>
                    <div>
                        <label class="text-xs uppercase text-stone-500 font-mono block mb-1">URL del Video (YouTube / Bunny.net)</label>
                        <input type="text" name="video_url" placeholder="https://..." class="w-full px-4 py-3 rounded-xl text-white font-mono text-sm bg-white/5 border border-white/10 outline-none focus:border-accent">
                    </div>
                    <div>
                        <label class="text-xs uppercase text-stone-500 font-mono block mb-1">Orden</label>
                        <input type="number" name="orden" value="<?php echo count($lecciones) + 1; ?>" class="w-full px-4 py-3 rounded-xl text-white font-mono text-sm bg-white/5 border border-white/10 outline-none focus:border-accent">
                    </div>
                </div>
                <button type="submit" class="w-full mt-6 py-4 bg-accent text-black font-black uppercase tracking-widest rounded-xl hover:bg-orange-600 transition-all">CREAR LECCIÓN</button>
            </form>
        </div>
    </div>

    <div id="modal-actividad" class="hidden fixed inset-0 flex items-center justify-center z-50 p-4" style="background:rgba(0,0,0,0.9);backdrop-filter:blur(5px);">
        <div class="bg-[#111] border border-accent/40 rounded-2xl p-8 max-w-lg w-full">
            <div class="flex justify-between items-center mb-6">
                <h2 class="font-['Orbitron'] text-white text-lg font-bold">Nueva Actividad</h2>
                <button onclick="document.getElementById('modal-actividad').classList.add('hidden')" class="text-stone-500 hover:text-white text-2xl">&times;</button>
            </div>
            <form method="POST">
                <input type="hidden" name="accion" value="crear_actividad">
                <input type="hidden" name="leccion_id" id="actividad-leccion-id">
                <div class="space-y-4">
                    <div>
                        <label class="text-xs uppercase text-stone-500 font-mono block mb-1">Título de la actividad</label>
                        <input type="text" name="titulo" required class="w-full px-4 py-3 rounded-xl text-white font-mono text-sm bg-white/5 border border-white/10 outline-none focus:border-accent" placeholder="ej: Crea tu primer layout">
                    </div>
                    <div>
                        <label class="text-xs uppercase text-stone-500 font-mono block mb-1">Descripción / Instrucciones</label>
                        <textarea name="descripcion" rows="3" class="w-full px-4 py-3 rounded-xl text-white font-mono text-sm bg-white/5 border border-white/10 outline-none focus:border-accent"></textarea>
                    </div>
                    <div>
                        <label class="text-xs uppercase text-stone-500 font-mono block mb-1">Tipo de entrega</label>
                        <select name="tipo" class="w-full px-4 py-3 rounded-xl text-white font-mono text-sm bg-white/5 border border-white/10 outline-none focus:border-accent">
                            <option value="responder_texto">Responder texto</option>
                            <option value="subir_archivo">Subir archivo</option>
                            <option value="link">Link / URL</option>
                        </select>
                    </div>
                </div>
                <button type="submit" class="w-full mt-6 py-4 bg-accent text-black font-black uppercase tracking-widest rounded-xl hover:bg-orange-600 transition-all">CREAR ACTIVIDAD</button>
            </form>
        </div>
    </div>

    <script>
    function abrirModalActividad(leccionId) {
        document.getElementById('actividad-leccion-id').value = leccionId;
        document.getElementById('modal-actividad').classList.remove('hidden');
    }
    </script>
</body>
</html>
