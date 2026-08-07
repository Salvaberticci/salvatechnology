<?php
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/config/app.php';
require_once __DIR__ . '/helpers/correo.php';

if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_rol'] !== 'estudiante') {
    header('Location: academia');
    exit;
}

$usuarioId = $_SESSION['usuario_id'];
$actividadId = (int)($_POST['actividad_id'] ?? 0);
$cursoId = (int)($_POST['curso_id'] ?? 0);

if (!$actividadId || !$cursoId) {
    die("Datos inválidos");
}

$stmt = $pdo->prepare("SELECT a.* FROM actividades a JOIN lecciones l ON a.leccion_id = l.id WHERE a.id = ? AND l.curso_id = ?");
$stmt->execute([$actividadId, $cursoId]);
$actividad = $stmt->fetch();

if (!$actividad) {
    die("Actividad no encontrada");
}

$archivoUrl = null;
$respuestaTexto = null;
$linkUrl = null;

if ($actividad['tipo'] === 'subir_archivo' && isset($_FILES['archivo']) && $_FILES['archivo']['error'] === UPLOAD_ERR_OK) {
    $ext = pathinfo($_FILES['archivo']['name'], PATHINFO_EXTENSION);
    $nombreUnico = uniqid('entrega_') . '.' . $ext;
    $ruta = __DIR__ . '/uploads/actividades/' . $nombreUnico;
    if (move_uploaded_file($_FILES['archivo']['tmp_name'], $ruta)) {
        $archivoUrl = 'uploads/actividades/' . $nombreUnico;
    }
} elseif ($actividad['tipo'] === 'link' && !empty($_POST['link_url'])) {
    $linkUrl = filter_var($_POST['link_url'], FILTER_VALIDATE_URL);
    if (!$linkUrl) {
        die("URL inválida");
    }
} elseif ($actividad['tipo'] === 'responder_texto' && !empty($_POST['respuesta_texto'])) {
    $respuestaTexto = trim($_POST['respuesta_texto']);
}

$stmt = $pdo->prepare("SELECT id FROM entregas WHERE actividad_id = ? AND usuario_id = ?");
$stmt->execute([$actividadId, $usuarioId]);
$entregaExistente = $stmt->fetch();

if ($entregaExistente) {
    $stmt = $pdo->prepare("UPDATE entregas SET archivo_url = COALESCE(?, archivo_url), respuesta_texto = COALESCE(?, respuesta_texto), link_url = COALESCE(?, link_url), estado = 'pendiente', fecha_entrega = CURRENT_TIMESTAMP WHERE id = ?");
    $stmt->execute([$archivoUrl, $respuestaTexto, $linkUrl, $entregaExistente['id']]);
} else {
    $stmt = $pdo->prepare("INSERT INTO entregas (actividad_id, usuario_id, archivo_url, respuesta_texto, link_url, estado) VALUES (?, ?, ?, ?, ?, 'pendiente')");
    $stmt->execute([$actividadId, $usuarioId, $archivoUrl, $respuestaTexto, $linkUrl]);
}

// Notificar al coordinador por correo
$stmt = $pdo->prepare("SELECT * FROM usuarios WHERE id = ?");
$stmt->execute([$usuarioId]);
$usuario = $stmt->fetch();

$stmt = $pdo->prepare("SELECT l.titulo AS leccion_titulo FROM lecciones l WHERE l.id = ?");
$stmt->execute([$actividad['leccion_id']]);
$leccionInfo = $stmt->fetch();

$detalles = [
    ['Estudiante', $usuario['nombre'] ?? '—'],
    ['Email', $usuario['email'] ?? '—'],
    ['Actividad', $actividad['titulo']],
    ['Lección', $leccionInfo['leccion_titulo'] ?? '—'],
    ['Tipo de entrega', strtoupper($actividad['tipo'])],
    ['Vinculada desde', $linkUrl ?? '—'],
    ['Archivo', $archivoUrl ? BASE_URL . $archivoUrl : '—'],
];
if ($respuestaTexto !== null) {
    $detalles[] = ['Respuesta', mb_strimwidth($respuestaTexto, 0, 300, '…')];
}

$resNotif = notificarAdmin(
    'NUEVA ENTREGA DE ACTIVIDAD',
    'Actividad pendiente de revisión',
    $detalles,
    'Revisa y califica la entrega del estudiante desde el panel de profesor.'
);
if (!$resNotif['ok']) {
    error_log('[Notificación entrega] falló: ' . ($resNotif['error'] ?? 'desconocido'));
}

header('Location: ' . BASE_URL . 'curso/' . $cursoId . '/leccion/' . $actividad['leccion_id']);
exit;
