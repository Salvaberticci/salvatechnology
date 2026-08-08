<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../helpers/config_sistema.php';
require_once __DIR__ . '/../helpers/correo.php';

if (!isset($_SESSION['usuario_id'])) {
    header('Location: ' . BASE_URL . 'academia');
    exit;
}
if (!esAdmin()) {
    header('Location: ' . BASE_URL . 'profesor');
    exit;
}

$mensaje = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accion = $_POST['accion'] ?? '';

    if ($accion === 'guardar_config') {
        $campos = [
            'email_notificacion' => 'email_notificacion',
            'smtp_host'   => 'smtp_host',
            'smtp_port'   => 'smtp_port',
            'smtp_secure' => 'smtp_secure',
            'smtp_user'   => 'smtp_user',
            'smtp_pass'   => 'smtp_pass',
            'mail_from'   => 'mail_from',
            'mail_from_name' => 'mail_from_name',
            'app_url'     => 'app_url',
            'admins'      => 'admins',
            'nombre_plataforma' => 'nombre_plataforma',
        ];
        foreach ($campos as $clave) {
            $valor = $_POST[$clave] ?? '';
            guardarConfig($clave, trim($valor));
        }
        $mensaje = 'Configuración del sistema guardada correctamente.';
    }

    if ($accion === 'test_correo') {
        $res = notificarAdmin(
            'PRUEBA DE NOTIFICACIÓN',
            'Test de correo',
            [
                ['Sistema', configSistema('nombre_plataforma', 'SalvaTechnology Academy')],
                ['Desde', configSistema('mail_from', $_SESSION['usuario_email'])],
                ['Fecha', date('d/m/Y H:i')],
            ],
            'Si estás viendo este correo, el sistema de notificaciones está funcionando correctamente.'
        );
        if ($res['ok']) {
            $mensaje = 'Correo de prueba enviado con éxito.';
        } else {
            $error = 'No se pudo enviar el correo de prueba: ' . ($res['error'] ?? 'desconocido');
        }
    }
}

// KPIs
$totalEstudiantes = $pdo->query("SELECT COUNT(*) FROM usuarios WHERE rol = 'estudiante'")->fetchColumn();
$estudiantesMes = $pdo->query("SELECT COUNT(*) FROM usuarios WHERE rol = 'estudiante' AND creado_en >= DATE_FORMAT(CURDATE(), '%Y-%m-01')")->fetchColumn();
$suscripcionesActivas = $pdo->query("SELECT COUNT(*) FROM usuarios WHERE rol = 'estudiante' AND plan = 'suscripcion' AND (suscripcion_expira IS NULL OR suscripcion_expira >= CURDATE())")->fetchColumn();
$ingresosTotales = $pdo->query("SELECT COALESCE(SUM(monto),0) FROM pagos WHERE estado = 'completado'")->fetchColumn();
$pagosPendientes = $pdo->query("SELECT COUNT(*) FROM pagos WHERE estado = 'pendiente'")->fetchColumn();
$ingresosMes = $pdo->query("SELECT COALESCE(SUM(monto),0) FROM pagos WHERE estado = 'completado' AND fecha_pago >= DATE_FORMAT(CURDATE(), '%Y-%m-01')")->fetchColumn();

// Gráfica: estudiantes por mes (últimos 12 meses)
$estudiantesPorMes = [];
for ($i = 11; $i >= 0; $i--) {
    $mes = date('Y-m', strtotime("-$i months"));
    $estudiantesPorMes[$mes] = 0;
}
$stmt = $pdo->query("SELECT DATE_FORMAT(creado_en, '%Y-%m') AS mes, COUNT(*) AS total FROM usuarios WHERE rol = 'estudiante' AND creado_en >= DATE_SUB(CURDATE(), INTERVAL 12 MONTH) GROUP BY mes");
foreach ($stmt->fetchAll() as $fila) {
    if (isset($estudiantesPorMes[$fila['mes']])) { $estudiantesPorMes[$fila['mes']] = (int)$fila['total']; }
}

// Gráfica: ingresos por mes (últimos 12 meses, pagos completados)
$ingresosPorMes = [];
for ($i = 11; $i >= 0; $i--) {
    $mes = date('Y-m', strtotime("-$i months"));
    $ingresosPorMes[$mes] = 0;
}
$stmt = $pdo->query("SELECT DATE_FORMAT(fecha_pago, '%Y-%m') AS mes, SUM(monto) AS total FROM pagos WHERE estado = 'completado' AND fecha_pago >= DATE_SUB(CURDATE(), INTERVAL 12 MONTH) GROUP BY mes");
foreach ($stmt->fetchAll() as $fila) {
    if (isset($ingresosPorMes[$fila['mes']])) { $ingresosPorMes[$fila['mes']] = (float)$fila['total']; }
}

// Tipos de pago
$ingresosSuscripcion = $pdo->query("SELECT COALESCE(SUM(monto),0) FROM pagos WHERE estado = 'completado' AND tipo = 'suscripcion'")->fetchColumn();
$ingresosCursos = $pdo->query("SELECT COALESCE(SUM(monto),0) FROM pagos WHERE estado = 'completado' AND tipo = 'curso_individual'")->fetchColumn();

$labelsMeses  = array_keys($estudiantesPorMes);
$dataEstudiantes = array_values($estudiantesPorMes);
$dataIngresos = array_values($ingresosPorMes);
$labelsCorto = array_map(function ($m) {
    $nombres = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];
    return $nombres[(int)date('n', strtotime($m . '-01')) - 1];
}, $labelsMeses);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administración | Salva Technology</title>
    <base href="<?= BASE_URL ?>">
    <link rel="icon" type="image/webp" href="img/logo.webp">
    <link rel="stylesheet" href="css/dashboard.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    <script>tailwind.config={theme:{extend:{colors:{'accent':'#ff8c00','dark-bg':'#0a0a0a'}}}}</script>
</head>
<body class="dashboard-body">
    <div class="scanlines"></div>
    <div class="dashboard-layout">
        <aside class="dash-sidebar">
            <div class="logo-side"><a href="./"><img src="img/logo.webp" alt="Salva"></a></div>
            <div class="user-badge">
                <?php
                $avatarAdmin = $_SESSION['usuario_avatar'] ?? '';
                if ($avatarAdmin): ?>
                    <img src="<?= htmlspecialchars($avatarAdmin) ?>" alt="Foto" class="avatar-img">
                <?php else: ?>
                    <div class="avatar" style="background:#ff4444;color:#fff;"><?php echo strtoupper(substr($_SESSION['usuario_nombre'], 0, 1)); ?></div>
                <?php endif; ?>
                <div class="name"><?php echo htmlspecialchars($_SESSION['usuario_nombre']); ?></div>
                <span class="plan-badge" style="background:rgba(255,140,0,0.15);color:var(--accent);border-color:rgba(255,140,0,0.3);">ADMIN</span>
            </div>
            <nav class="dash-nav">
                <a href="profesor"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6z"/></svg>Dashboard</a>
                <a href="profesor/cursos"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253"/></svg>Cursos</a>
                <a href="profesor/estudiantes"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857"/></svg>Estudiantes</a>
                <a href="profesor/entregas"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>Entregas</a>
                <a href="profesor/pagos"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>Pagos</a>
                <a href="profesor/perfil"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>Mi Perfil</a>
                <a href="profesor/admin.php" class="active"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.8 0-3 .6-3 1.5v.2c0 .5.3 1 .8 1.3.5.3 1.2.4 2 .4h.4c.8 0 1.5-.1 2-.4.5-.3.8-.8.8-1.3v-.2C15 8.6 13.8 8 12 8zm0 6c-2.5 0-4.5-1-4.5-3.5 0-2.5 2-3.8 4.5-3.8s4.5 1.3 4.5 3.8C16.5 13 14.5 14 12 14zm5 4.5c0-2.2-2.2-4-5-4s-5 1.8-5 4H17z"/></svg>Admin</a>
                <a href="logout"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>Cerrar</a>
            </nav>
        </aside>

        <main class="dash-main">
            <div class="dash-header">
                <h1>Panel de <span>Admin</span></h1>
                <span class="text-stone-600 text-xs font-mono"><?php echo date('d.m.Y H:i'); ?></span>
            </div>

            <?php if ($mensaje): ?>
            <div class="mb-6 px-4 py-3 rounded-xl border border-green-500/30 bg-green-500/10 text-green-300 text-sm font-mono"><?php echo htmlspecialchars($mensaje); ?></div>
            <?php endif; ?>
            <?php if ($error): ?>
            <div class="mb-6 px-4 py-3 rounded-xl border border-red-500/30 bg-red-500/10 text-red-300 text-sm font-mono"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
                <div class="bg-[var(--panel-bg)] border border-[var(--border-color)] rounded-xl p-5">
                    <div class="text-accent text-3xl font-black font-['Orbitron']"><?php echo $totalEstudiantes; ?></div>
                    <div class="text-stone-500 text-xs font-mono mt-1 uppercase tracking-wider">Estudiantes</div>
                </div>
                <div class="bg-[var(--panel-bg)] border border-[var(--border-color)] rounded-xl p-5">
                    <div class="text-accent text-3xl font-black font-['Orbitron']"><?php echo $estudiantesMes; ?></div>
                    <div class="text-stone-500 text-xs font-mono mt-1 uppercase tracking-wider">Nuevos este mes</div>
                </div>
                <div class="bg-[var(--panel-bg)] border border-[var(--border-color)] rounded-xl p-5">
                    <div class="text-accent text-3xl font-black font-['Orbitron']"><?php echo $suscripcionesActivas; ?></div>
                    <div class="text-stone-500 text-xs font-mono mt-1 uppercase tracking-wider">Suscripciones</div>
                </div>
                <div class="bg-[var(--panel-bg)] border border-[var(--border-color)] rounded-xl p-5">
                    <div class="text-accent text-3xl font-black font-['Orbitron']">$<?php echo number_format((float)$ingresosMes, 0, ',', '.'); ?></div>
                    <div class="text-stone-500 text-xs font-mono mt-1 uppercase tracking-wider">Ingresos este mes</div>
                </div>
            </div>

            <div class="grid md:grid-cols-2 gap-6 mb-6">
                <div class="bg-[var(--panel-bg)] border border-[var(--border-color)] rounded-xl p-6">
                    <h3 class="font-['Orbitron'] text-white text-sm font-bold mb-4 uppercase tracking-wider">Estudiantes por mes</h3>
                    <canvas id="chartEstudiantes" height="200"></canvas>
                </div>
                <div class="bg-[var(--panel-bg)] border border-[var(--border-color)] rounded-xl p-6">
                    <h3 class="font-['Orbitron'] text-white text-sm font-bold mb-4 uppercase tracking-wider">Ingresos por mes (USD)</h3>
                    <canvas id="chartIngresos" height="200"></canvas>
                </div>
            </div>

            <div class="grid md:grid-cols-2 gap-6 mb-6">
                <div class="bg-[var(--panel-bg)] border border-[var(--border-color)] rounded-xl p-6">
                    <h3 class="font-['Orbitron'] text-white text-sm font-bold mb-4 uppercase tracking-wider">Ingresos totales</h3>
                    <div class="text-4xl font-black text-accent font-['Orbitron']">$<?php echo number_format((float)$ingresosTotales, 0, ',', '.'); ?></div>
                    <div class="mt-4 space-y-2 text-sm">
                        <div class="flex justify-between"><span class="text-stone-500">Suscripciones:</span><span class="text-white font-mono">$<?php echo number_format((float)$ingresosSuscripcion, 2, ',', '.'); ?></span></div>
                        <div class="flex justify-between"><span class="text-stone-500">Cursos individuales:</span><span class="text-white font-mono">$<?php echo number_format((float)$ingresosCursos, 2, ',', '.'); ?></span></div>
                        <div class="flex justify-between"><span class="text-stone-500">Pagos pendientes de revisar:</span><span class="<?php echo $pagosPendientes > 0 ? 'text-red-400' : 'text-white'; ?> font-mono"><?php echo $pagosPendientes; ?></span></div>
                    </div>
                </div>

                <div class="bg-[var(--panel-bg)] border border-[var(--border-color)] rounded-xl p-6">
                    <h3 class="font-['Orbitron'] text-white text-sm font-bold mb-4 uppercase tracking-wider">Configuración del sistema</h3>
                    <form method="POST" class="space-y-3">
                        <input type="hidden" name="accion" value="guardar_config">
                        <div>
                            <label class="text-[10px] uppercase text-stone-500 font-mono block mb-1">Correo de notificaciones (admin)</label>
                            <input type="email" name="email_notificacion" value="<?php echo htmlspecialchars(configSistema('email_notificacion')); ?>" class="w-full px-3 py-2 rounded-lg text-white font-mono text-sm bg-white/5 border border-white/10 outline-none focus:border-accent">
                        </div>
                        <div>
                            <label class="text-[10px] uppercase text-stone-500 font-mono block mb-1">URL pública de la app</label>
                            <input type="text" name="app_url" value="<?php echo htmlspecialchars(configSistema('app_url')); ?>" class="w-full px-3 py-2 rounded-lg text-white font-mono text-sm bg-white/5 border border-white/10 outline-none focus:border-accent">
                        </div>
                        <div>
                            <label class="text-[10px] uppercase text-stone-500 font-mono block mb-1">Nombre de la plataforma</label>
                            <input type="text" name="nombre_plataforma" value="<?php echo htmlspecialchars(configSistema('nombre_plataforma')); ?>" class="w-full px-3 py-2 rounded-lg text-white font-mono text-sm bg-white/5 border border-white/10 outline-none focus:border-accent">
                        </div>
                        <div>
                            <label class="text-[10px] uppercase text-stone-500 font-mono block mb-1">Emails administradores <span class="text-stone-600">(uno por línea)</span></label>
                            <textarea name="admins" rows="2" class="w-full px-3 py-2 rounded-lg text-white font-mono text-sm bg-white/5 border border-white/10 outline-none focus:border-accent"><?php echo htmlspecialchars(configSistema('admins')); ?></textarea>
                        </div>
                        <div class="border-t border-white/5 pt-3">
                            <details>
                                <summary class="text-[10px] uppercase text-stone-500 font-mono cursor-pointer select-none">SMTP del correo del sistema (avanzado)</summary>
                                <div class="grid grid-cols-2 gap-3 mt-3">
                                    <div>
                                        <label class="text-[10px] uppercase text-stone-500 font-mono block mb-1">Host</label>
                                        <input type="text" name="smtp_host" value="<?php echo htmlspecialchars(configSistema('smtp_host')); ?>" placeholder="salvatechnology.online" class="w-full px-3 py-2 rounded-lg text-white font-mono text-sm bg-white/5 border border-white/10 outline-none focus:border-accent">
                                    </div>
                                    <div>
                                        <label class="text-[10px] uppercase text-stone-500 font-mono block mb-1">Puerto</label>
                                        <input type="text" name="smtp_port" value="<?php echo htmlspecialchars(configSistema('smtp_port')); ?>" placeholder="465" class="w-full px-3 py-2 rounded-lg text-white font-mono text-sm bg-white/5 border border-white/10 outline-none focus:border-accent">
                                    </div>
                                    <div>
                                        <label class="text-[10px] uppercase text-stone-500 font-mono block mb-1">Seguridad</label>
                                        <select name="smtp_secure" class="w-full px-3 py-2 rounded-lg text-white font-mono text-sm bg-white/5 border border-white/10 outline-none focus:border-accent">
                                            <option value="ssl" <?php echo configSistema('smtp_secure') === 'ssl' ? 'selected' : ''; ?>>SSL (465)</option>
                                            <option value="tls" <?php echo configSistema('smtp_secure') === 'tls' ? 'selected' : ''; ?>>TLS (587)</option>
                                            <option value="" <?php echo configSistema('smtp_secure') === '' ? 'selected' : ''; ?>>(dejar vacío para usar keys.local)</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="text-[10px] uppercase text-stone-500 font-mono block mb-1">Usuario</label>
                                        <input type="text" name="smtp_user" value="<?php echo htmlspecialchars(configSistema('smtp_user')); ?>" class="w-full px-3 py-2 rounded-lg text-white font-mono text-sm bg-white/5 border border-white/10 outline-none focus:border-accent">
                                    </div>
                                    <div>
                                        <label class="text-[10px] uppercase text-stone-500 font-mono block mb-1">Contraseña</label>
                                        <input type="password" name="smtp_pass" value="<?php echo htmlspecialchars(configSistema('smtp_pass')); ?>" placeholder="(vacío → keys.local)" class="w-full px-3 py-2 rounded-lg text-white font-mono text-sm bg-white/5 border border-white/10 outline-none focus:border-accent">
                                    </div>
                                    <div>
                                        <label class="text-[10px] uppercase text-stone-500 font-mono block mb-1">Remitente (From)</label>
                                        <input type="text" name="mail_from" value="<?php echo htmlspecialchars(configSistema('mail_from')); ?>" placeholder="academy@..." class="w-full px-3 py-2 rounded-lg text-white font-mono text-sm bg-white/5 border border-white/10 outline-none focus:border-accent">
                                    </div>
                                    <div class="col-span-2">
                                        <label class="text-[10px] uppercase text-stone-500 font-mono block mb-1">Nombre del remitente</label>
                                        <input type="text" name="mail_from_name" value="<?php echo htmlspecialchars(configSistema('mail_from_name')); ?>" class="w-full px-3 py-2 rounded-lg text-white font-mono text-sm bg-white/5 border border-white/10 outline-none focus:border-accent">
                                    </div>
                                </div>
                            </details>
                        </div>
                        <button type="submit" class="btn-continuar" style="width:100%;justify-content:center;padding:0.6rem;">GUARDAR CONFIGURACIÓN</button>
                    </form>
                    <form method="POST" class="mt-3">
                        <input type="hidden" name="accion" value="test_correo">
                        <button type="submit" class="btn-explorar" style="width:100%;justify-content:center;padding:0.6rem;background:transparent;">ENVIAR CORREO DE PRUEBA</button>
                    </form>
                </div>
            </div>
        </main>
        <?php require __DIR__ . '/../partials/chatbot.php'; ?>
    </div>

    <script>
        const etiquetas = <?php echo json_encode($labelsCorto); ?>;
        Chart.defaults.color = '#a8a29e';
        Chart.defaults.borderColor = 'rgba(255,255,255,0.06)';
        Chart.defaults.font.family = "'Roboto Mono', monospace";

        new Chart(document.getElementById('chartEstudiantes'), {
            type: 'bar',
            data: {
                labels: etiquetas,
                datasets: [{
                    label: 'Estudiantes registrados',
                    data: <?php echo json_encode($dataEstudiantes); ?>,
                    backgroundColor: 'rgba(255,140,0,0.55)',
                    borderColor: '#ff8c00',
                    borderWidth: 1,
                    borderRadius: 4
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, ticks: { precision: 0 } },
                    x: { grid: { display: false } }
                }
            }
        });

        new Chart(document.getElementById('chartIngresos'), {
            type: 'line',
            data: {
                labels: etiquetas,
                datasets: [{
                    label: 'Ingresos (USD)',
                    data: <?php echo json_encode($dataIngresos); ?>,
                    borderColor: '#ff8c00',
                    backgroundColor: 'rgba(255,140,0,0.15)',
                    fill: true,
                    tension: 0.35,
                    pointBackgroundColor: '#ff8c00'
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true },
                    x: { grid: { display: false } }
                }
            }
        });
    </script>
</body>
</html>