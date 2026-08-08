<?php
require_once __DIR__ . '/../libs/phpmailer/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

/**
 * Envía un correo por SMTP (Namecheap).
 * Devuelve ['ok' => true] o ['ok' => false, 'error' => '...'].
 */
function enviarCorreo($para, $paraNombre, $asunto, $html, $texto = '', $logoPath = '') {
    $configFile = __DIR__ . '/../config/keys.local.php';
    if (!file_exists($configFile)) {
        return ['ok' => false, 'error' => 'config/keys.local.php no existe'];
    }
    require $configFile;

    if (empty($SMTP_PASS) || strpos($SMTP_PASS, 'PON_AQUI') !== false) {
        return ['ok' => false, 'error' => 'SMTP sin contraseña configurada'];
    }

    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host       = $SMTP_HOST;
        $mail->SMTPAuth   = true;
        $mail->Username   = $SMTP_USER;
        $mail->Password   = $SMTP_PASS;
        $mail->SMTPSecure = $SMTP_SECURE;
        $mail->Port       = $SMTP_PORT;
        $mail->CharSet    = 'UTF-8';
        $mail->Timeout    = 15;

        $mail->setFrom($MAIL_FROM, $MAIL_FROM_NAME);
        $mail->addAddress($para, $paraNombre);
        if ($logoPath !== '' && file_exists($logoPath)) {
            $mail->addEmbeddedImage($logoPath, 'logo_brand', 'logo.png', 'base64', 'image/png');
        }
        $mail->isHTML(true);
        $mail->Subject = $asunto;
        $mail->Body    = $html;
        $mail->AltBody = $texto !== '' ? $texto : trim(strip_tags(str_replace(['<br>', '</p>', '</td>'], "\n", $html)));

        $mail->send();
        return ['ok' => true];
    } catch (Exception $e) {
        return ['ok' => false, 'error' => $mail->ErrorInfo];
    }
}

/**
 * Plantilla HTML del correo de bienvenida — mismo sistema visual que la plataforma
 * (fondo #0a0a0a, paneles #0f0f0f, acento #ff8c00, verde matrix #00ff41, Orbitron + Roboto Mono).
 */
function correoBienvenidaHtml($nombre, $email, $appUrl, $loginUrl) {
    $dom    = preg_replace('#^https?://#', '', $appUrl);

    $html = <<<'HTML'
    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="x-apple-disable-message-reformatting">
        <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@500;700;900&family=Roboto+Mono:wght@400;500;700&display=swap" rel="stylesheet">
        <title>Bienvenido a Salvatechnology Academy</title>
    </head>
    <body style="margin:0;padding:0;background:#0a0a0a;">
        <div style="display:none;max-height:0;overflow:hidden;mso-hide:all;">Tu cuenta fue creada con éxito. Bienvenido a Salvatechnology Academy.</div>

        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#0a0a0a;">
            <tr>
                <td align="center" style="padding:40px 16px;">
                    <table role="presentation" width="600" cellpadding="0" cellspacing="0" style="max-width:600px;width:100%;background:#0f0f0f;border:1px solid rgba(255,140,0,0.15);border-radius:16px;overflow:hidden;">

                        <!-- TOP LINE -->
                        <tr>
                            <td style="background:linear-gradient(90deg,#ff8c00 0%,#ffb55a 50%,#ff8c00 100%);height:3px;font-size:0;line-height:0;">&nbsp;</td>
                        </tr>

                        <!-- HEADER + LOGO -->
                        <tr>
                            <td align="center" style="background:#0a0a0a;padding:36px 32px 26px 32px;">
                                <img src="cid:logo_brand" alt="Salva Technology" width="190" style="display:block;max-width:190px;width:100%;height:auto;border:0;">
                                <div style="margin:24px 0 0 0;font-family:'Orbitron',Arial,sans-serif;font-size:13px;color:#ff8c00;letter-spacing:5px;font-weight:700;text-transform:uppercase;">Academia · Bienvenida</div>
                                <div style="width:64px;height:2px;background:#ff8c00;margin:16px auto 0 auto;border-radius:2px;"></div>
                            </td>
                        </tr>

                        <!-- CUERPO -->
                        <tr>
                            <td style="background:#0f0f0f;padding:32px 40px;">
                                <p style="margin:0 0 18px 0;font-family:'Orbitron',Arial,sans-serif;font-size:22px;line-height:1.3;color:#ffffff;font-weight:700;">¡Bienvenido,<br><span style="color:#ff8c00;">{NOMBRE}</span>!</p>
                                <p style="margin:0 0 22px 0;font-family:'Roboto Mono',monospace;font-size:13px;line-height:1.8;color:#e0e0e0;">
                                    Tu cuenta en <strong style="color:#ff8c00;">SALVA TECHNOLOGY ACADEMY</strong> fue creada con éxito.
                                    Ya tienes acceso a los cursos, e-books interactivos y al sistema de XP y logros.
                                    Empieza tu camino como <strong style="color:#00ff41;">AI-Driven Developer</strong>.
                                </p>

                                <!-- MÓDULOS -->
                                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin:0 0 24px 0;">
                                    <tr>
                                        <td width="33.33%" style="padding:0 8px 8px 0;">
                                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#0a0a0a;border:1px solid rgba(255,140,0,0.15);border-radius:12px;">
                                                <tr><td align="center" style="padding:18px 12px 8px 12px;font-family:'Orbitron',Arial,sans-serif;font-size:16px;color:#ff8c00;font-weight:700;">01</td></tr>
                                                <tr><td align="center" style="padding:0 10px 4px 10px;font-family:'Orbitron',Arial,sans-serif;font-size:11px;font-weight:700;color:#ffffff;">CURSOS</td></tr>
                                                <tr><td align="center" style="padding:0 12px 18px 12px;font-family:'Roboto Mono',monospace;font-size:10px;color:#9a9a9a;line-height:1.6;">método ADD · práctica real</td></tr>
                                            </table>
                                        </td>
                                        <td width="33.33%" style="padding:0 4px 8px 4px;">
                                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#0a0a0a;border:1px solid rgba(255,140,0,0.15);border-radius:12px;">
                                                <tr><td align="center" style="padding:18px 12px 8px 12px;font-family:'Orbitron',Arial,sans-serif;font-size:16px;color:#ff8c00;font-weight:700;">02</td></tr>
                                                <tr><td align="center" style="padding:0 10px 4px 10px;font-family:'Orbitron',Arial,sans-serif;font-size:11px;font-weight:700;color:#ffffff;">E-BOOKS</td></tr>
                                                <tr><td align="center" style="padding:0 12px 18px 12px;font-family:'Roboto Mono',monospace;font-size:10px;color:#9a9a9a;line-height:1.6;">quizzes + XP + desafíos</td></tr>
                                            </table>
                                        </td>
                                        <td width="33.33%" style="padding:0 0 8px 8px;">
                                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#0a0a0a;border:1px solid rgba(255,140,0,0.15);border-radius:12px;">
                                                <tr><td align="center" style="padding:18px 12px 8px 12px;font-family:'Orbitron',Arial,sans-serif;font-size:16px;color:#ff8c00;font-weight:700;">03</td></tr>
                                                <tr><td align="center" style="padding:0 10px 4px 10px;font-family:'Orbitron',Arial,sans-serif;font-size:11px;font-weight:700;color:#ffffff;">XP + LOGROS</td></tr>
                                                <tr><td align="center" style="padding:0 12px 18px 12px;font-family:'Roboto Mono',monospace;font-size:10px;color:#9a9a9a;line-height:1.6;">metas verificadas</td></tr>
                                            </table>
                                        </td>
                                    </tr>
                                </table>

                                <!-- IDENTIDAD -->
                                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#0a0a0a;border:1px solid rgba(255,140,0,0.15);border-radius:12px;margin:0 0 26px 0;">
                                    <tr>
                                        <td style="padding:14px 18px;font-family:'Roboto Mono',monospace;font-size:11px;color:#ff8c00;text-transform:uppercase;letter-spacing:1px;">Tu cuenta</td>
                                        <td align="right" style="padding:14px 18px;font-family:'Roboto Mono',monospace;font-size:12px;color:#ffffff;">{EMAIL}</td>
                                    </tr>
                                </table>

                                <!-- CTA -->
                                <table role="presentation" width="100%" cellpadding="0" cellspacing="0">
                                    <tr>
                                        <td align="center" style="padding:6px 0 4px 0;">
                                            <a href="{LOGIN}" style="display:inline-block;background:#ff8c00;color:#0a0a0a;text-decoration:none;font-family:'Orbitron',Arial,sans-serif;font-size:13px;font-weight:700;letter-spacing:2px;padding:16px 40px;border-radius:10px;box-shadow:0 0 24px rgba(255,140,0,0.35);">INGRESAR A MI CUENTA</a>
                                        </td>
                                    </tr>
                                </table>
                                <p style="margin:14px 0 0 0;text-align:center;font-family:'Roboto Mono',monospace;font-size:10px;color:#777777;">{DOM} / academia</p>
                            </td>
                        </tr>

                        <!-- FOOTER -->
                        <tr>
                            <td style="background:#0a0a0a;border-top:1px solid rgba(255,140,0,0.15);padding:24px 40px;text-align:center;font-family:'Roboto Mono',monospace;font-size:10px;color:#777777;line-height:1.9;">
                                © {YEAR} <span style="color:#ff8c00;">SALVA TECHNOLOGY</span> · Academia de Ingeniería de Software<br>
                                Si no creaste esta cuenta, ignora este mensaje.
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </body>
    </html>
HTML;

    return str_replace(
        ['{NOMBRE}', '{EMAIL}', '{DOM}', '{LOGIN}', '{YEAR}'],
        [
            htmlspecialchars($nombre),
            htmlspecialchars($email),
            htmlspecialchars($dom),
            htmlspecialchars($loginUrl),
            date('Y'),
        ],
        $html
    );
}

/**
 * Plantilla HTML de notificación interna para el equipo SalvaTechnology.
 * Mismo sistema visual #0a0a0a / #ff8c00 / Orbitron + Roboto Mono.
 * $lineas = [[label, valor], ...]
 */
function correoNotificacionHtml($titulo, $subtitulo, array $lineas, $mensaje = '') {
    $filas = '';
    foreach ($lineas as [$label, $valor]) {
        $filas .= '<tr>'
            . '<td style="padding:7px 0;font-family:\'Roboto Mono\',monospace;font-size:11px;color:#777777;white-space:nowrap;">' . htmlspecialchars($label) . '</td>'
            . '<td style="padding:7px 0 7px 14px;font-family:\'Roboto Mono\',monospace;font-size:12px;color:#ffffff;word-break:break-all;">' . htmlspecialchars($valor) . '</td>'
            . '</tr>';
    }

    $html = <<<'HTML'
    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@500;700;900&family=Roboto+Mono:wght@400;500;700&display=swap" rel="stylesheet">
        <title>{TITULO}</title>
    </head>
    <body style="margin:0;padding:0;background:#0a0a0a;">
        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#0a0a0a;">
            <tr>
                <td align="center" style="padding:36px 16px;">
                    <table role="presentation" width="600" cellpadding="0" cellspacing="0" style="max-width:600px;width:100%;background:#0f0f0f;border:1px solid rgba(255,140,0,0.15);border-radius:16px;overflow:hidden;">
                        <tr>
                            <td style="background:linear-gradient(90deg,#ff8c00 0%,#ffb55a 50%,#ff8c00 100%);height:3px;font-size:0;line-height:0;">&nbsp;</td>
                        </tr>
                        <tr>
                            <td style="background:#0a0a0a;padding:28px 32px 18px 32px;border-bottom:1px solid rgba(255,140,0,0.12);">
                                <div style="font-family:'Roboto Mono',monospace;font-size:10px;color:#ff8c00;letter-spacing:3px;text-transform:uppercase;">&gt; SALVA·TECHNOLOGY · NOTIFICACIÓN INTERNA</div>
                                <div style="margin:12px 0 0 0;font-family:'Orbitron',Arial,sans-serif;font-size:20px;font-weight:900;color:#ffffff;">{TITULO}</div>
                                <div style="margin:6px 0 0 0;font-family:'Roboto Mono',monospace;font-size:11px;color:#9a9a9a;">{SUBTITULO}</div>
                            </td>
                        </tr>
                        <tr>
                            <td style="padding:24px 32px;">
                                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#0a0a0a;border:1px solid rgba(255,140,0,0.15);border-radius:12px;">
                                    {FILAS}
                                </table>
                                {MENSAJE}
                            </td>
                        </tr>
                        <tr>
                            <td style="background:#0a0a0a;border-top:1px solid rgba(255,140,0,0.12);padding:18px 32px;text-align:center;font-family:'Roboto Mono',monospace;font-size:10px;color:#777777;line-height:1.9;">
                                Generado automáticamente por la plataforma · {YEAR}
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </body>
    </html>
HTML;

    $mensajeHtml = $mensaje !== ''
        ? '<p style="margin:16px 0 0 0;padding:12px 14px;background:#0a0a0a;border:1px solid rgba(255,140,0,0.15);border-radius:10px;font-family:\'Roboto Mono\',monospace;font-size:11px;color:#e0e0e0;line-height:1.7;">' . htmlspecialchars($mensaje) . '</p>'
        : '';

    return str_replace(
        ['{TITULO}', '{SUBTITULO}', '{FILAS}', '{MENSAJE}', '{YEAR}'],
        [htmlspecialchars($titulo), htmlspecialchars($subtitulo), $filas, $mensajeHtml, date('Y')],
        $html
    );
}

/**
 * Envía una notificación al coordinador de la academia.
 * $paraDeDefecto: correo por defecto si no se define $NOTIFICAR_EMAIL en keys.local.php.
 */
function notificarAdmin($titulo, $subtitulo, array $lineas, $mensaje = '', $para = 'salvatoreberticci19@gmail.com') {
    $configFile = __DIR__ . '/../config/keys.local.php';
    if (file_exists($configFile)) {
        require $configFile;
        if (!empty($NOTIFICAR_EMAIL)) { $para = $NOTIFICAR_EMAIL; }
    }
    if (empty($para)) return ['ok' => false, 'error' => 'Sin destinatario de notificaciones'];
    $asunto = '[' . $subtitulo . '] ' . $titulo;
    return enviarCorreo($para, 'Administrador SalvaTech', $asunto, correoNotificacionHtml($titulo, $subtitulo, $lineas, $mensaje));
}

/**
 * Plantilla HTML del correo al ESTUDIANTE cuando el profesor revisa/califica su actividad.
 * Mismo sistema visual #0a0a0a / #ff8c00 / Orbitron + Roboto Mono.
 * $estado: 'aprobado' | 'rechazado'
 */
function correoActividadCalificadaHtml($estudianteNombre, $profesorNombre, $actividadTitulo, $leccionTitulo, $cursoTitulo, $calificacion, $estado, $comentario = '', $verUrl = '') {
    $estadoUpper = strtoupper($estado);
    $esAprobado  = $estado === 'aprobado';
    $colorEstado = $esAprobado ? '#00ff41' : '#ff4444';
    $iconoEstado = $esAprobado ? '&#10003;' : '&#10007;';
    $calFormateada = is_numeric($calificacion) ? rtrim(rtrim(number_format((float)$calificacion, 1, '.', ''), '0'), '.') : '—';

    $comentarioHtml = $comentario !== ''
        ? '<tr><td style="padding:14px 18px 18px 18px;font-family:\'Roboto Mono\',monospace;font-size:12px;color:#e0e0e0;line-height:1.8;background:#0a0a0a;border:1px solid rgba(255,140,0,0.15);border-radius:0 0 12px 12px;"><strong style="color:#ff8c00;">Comentario del profesor:</strong><br>' . nl2br(htmlspecialchars($comentario)) . '</td></tr>'
        : '';

    $cta = $verUrl !== ''
        ? '<table role="presentation" width="100%" cellpadding="0" cellspacing="0"><tr><td align="center" style="padding:6px 0 4px 0;"><a href="' . htmlspecialchars($verUrl) . '" style="display:inline-block;background:#ff8c00;color:#0a0a0a;text-decoration:none;font-family:\'Orbitron\',Arial,sans-serif;font-size:12px;font-weight:700;letter-spacing:2px;padding:14px 32px;border-radius:10px;box-shadow:0 0 24px rgba(255,140,0,0.35);">VER MI ACTIVIDAD</a></td></tr></table>'
        : '';

    $html = <<<'HTML'
    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="x-apple-disable-message-reformatting">
        <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@500;700;900&family=Roboto+Mono:wght@400;500;700&display=swap" rel="stylesheet">
        <title>Tu actividad fue revisada</title>
    </head>
    <body style="margin:0;padding:0;background:#0a0a0a;">
        <div style="display:none;max-height:0;overflow:hidden;mso-hide:all;">El profesor {PROFESOR} revisó tu actividad: {CALIFICACION}/100.</div>

        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#0a0a0a;">
            <tr>
                <td align="center" style="padding:40px 16px;">
                    <table role="presentation" width="600" cellpadding="0" cellspacing="0" style="max-width:600px;width:100%;background:#0f0f0f;border:1px solid rgba(255,140,0,0.15);border-radius:16px;overflow:hidden;">

                        <!-- TOP LINE -->
                        <tr>
                            <td style="background:linear-gradient(90deg,#ff8c00 0%,#ffb55a 50%,#ff8c00 100%);height:3px;font-size:0;line-height:0;">&nbsp;</td>
                        </tr>

                        <!-- HEADER -->
                        <tr>
                            <td align="center" style="background:#0a0a0a;padding:32px 32px 20px 32px;">
                                <div style="font-family:'Orbitron',Arial,sans-serif;font-size:12px;color:#ff8c00;letter-spacing:5px;font-weight:700;text-transform:uppercase;">Academia · Revisión de Actividad</div>
                                <div style="margin:16px 0 0 0;font-family:'Orbitron',Arial,sans-serif;font-size:24px;color:#ffffff;font-weight:900;">¡Tu actividad fue revisada!</div>
                                <div style="width:64px;height:2px;background:#ff8c00;margin:16px auto 0 auto;border-radius:2px;"></div>
                            </td>
                        </tr>

                        <!-- CUERPO -->
                        <tr>
                            <td style="background:#0f0f0f;padding:24px 40px 32px 40px;">
                                <p style="margin:0 0 20px 0;font-family:'Orbitron',Arial,sans-serif;font-size:17px;line-height:1.4;color:#ffffff;font-weight:700;">Hola, <span style="color:#ff8c00;">{ESTUDIANTE}</span>.</p>
                                <p style="margin:0 0 24px 0;font-family:'Roboto Mono',monospace;font-size:12px;line-height:1.8;color:#e0e0e0;">
                                    El profesor <strong style="color:#00ff41;">{PROFESOR}</strong> revisó tu entrega de la actividad <strong style="color:#ffffff;">"{ACTIVIDAD}"</strong>.
                                </p>

                                <!-- NOTA -->
                                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#0a0a0a;border:1px solid {COLOR_ESTADO};border-radius:12px;margin:0 0 16px 0;">
                                    <tr>
                                        <td align="center" style="padding:22px 18px 6px 18px;font-family:'Orbitron',Arial,sans-serif;font-size:10px;color:#777777;letter-spacing:3px;text-transform:uppercase;">Calificación</td>
                                    </tr>
                                    <tr>
                                        <td align="center" style="padding:4px 18px 8px 18px;font-family:'Orbitron',Arial,sans-serif;font-size:42px;font-weight:900;color:{COLOR_ESTADO};">{ICONO} {CALIFICACION}<span style="font-size:18px;color:#777777;">/100</span></td>
                                    </tr>
                                    <tr>
                                        <td align="center" style="padding:0 18px 20px 18px;font-family:'Orbitron',Arial,sans-serif;font-size:12px;font-weight:700;color:{COLOR_ESTADO};letter-spacing:3px;">{ESTADO}</td>
                                    </tr>
                                </table>

                                <!-- DETALLE -->
                                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#0a0a0a;border:1px solid rgba(255,140,0,0.15);border-radius:12px;margin:0 0 24px 0;">
                                    <tr>
                                        <td style="padding:14px 18px;font-family:'Roboto Mono',monospace;font-size:11px;color:#777777;white-space:nowrap;border-bottom:1px solid rgba(255,140,0,0.08);">ACTIVIDAD</td>
                                        <td style="padding:14px 18px;font-family:'Roboto Mono',monospace;font-size:12px;color:#ffffff;word-break:break-word;border-bottom:1px solid rgba(255,140,0,0.08);">{ACTIVIDAD}</td>
                                    </tr>
                                    <tr>
                                        <td style="padding:14px 18px;font-family:'Roboto Mono',monospace;font-size:11px;color:#777777;white-space:nowrap;border-bottom:1px solid rgba(255,140,0,0.08);">LECCIÓN</td>
                                        <td style="padding:14px 18px;font-family:'Roboto Mono',monospace;font-size:12px;color:#ffffff;word-break:break-word;border-bottom:1px solid rgba(255,140,0,0.08);">{LECCION}</td>
                                    </tr>
                                    <tr>
                                        <td style="padding:14px 18px;font-family:'Roboto Mono',monospace;font-size:11px;color:#777777;white-space:nowrap;border-bottom:1px solid rgba(255,140,0,0.08);">CURSO</td>
                                        <td style="padding:14px 18px;font-family:'Roboto Mono',monospace;font-size:12px;color:#ffffff;word-break:break-word;border-bottom:1px solid rgba(255,140,0,0.08);">{CURSO}</td>
                                    </tr>
                                    <tr>
                                        <td style="padding:14px 18px;font-family:'Roboto Mono',monospace;font-size:11px;color:#777777;white-space:nowrap;">REVISADO POR</td>
                                        <td style="padding:14px 18px;font-family:'Roboto Mono',monospace;font-size:12px;color:#00ff41;word-break:break-word;">{PROFESOR}</td>
                                    </tr>
                                </table>
                                {COMENTARIO}

                                <!-- CTA -->
                                {CTA}

                                <p style="margin:16px 0 0 0;text-align:center;font-family:'Roboto Mono',monospace;font-size:10px;color:#777777;">Sigue así: cada actividad te acerca a dominar la ingeniería de software.</p>
                            </td>
                        </tr>

                        <!-- FOOTER -->
                        <tr>
                            <td style="background:#0a0a0a;border-top:1px solid rgba(255,140,0,0.15);padding:24px 40px;text-align:center;font-family:'Roboto Mono',monospace;font-size:10px;color:#777777;line-height:1.9;">
                                © {YEAR} <span style="color:#ff8c00;">SALVA TECHNOLOGY</span> · Academia de Ingeniería de Software<br>
                                Si no entregaste una actividad, ignora este mensaje.
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </body>
    </html>
HTML;

    return str_replace(
        ['{ESTUDIANTE}', '{PROFESOR}', '{ACTIVIDAD}', '{LECCION}', '{CURSO}', '{CALIFICACION}', '{ESTADO}', '{COLOR_ESTADO}', '{ICONO}', '{COMENTARIO}', '{CTA}', '{YEAR}'],
        [
            htmlspecialchars($estudianteNombre),
            htmlspecialchars($profesorNombre),
            htmlspecialchars($actividadTitulo),
            htmlspecialchars($leccionTitulo),
            htmlspecialchars($cursoTitulo),
            $calFormateada,
            $estadoUpper,
            $colorEstado,
            $iconoEstado,
            $comentarioHtml,
            $cta,
            date('Y'),
        ],
        $html
    );
}

/**
 * Envía al estudiante el correo de actividad calificada por el profesor.
 */
function notificarEstudianteActividadCalificada($estudianteEmail, $estudianteNombre, $profesorNombre, $actividadTitulo, $leccionTitulo, $cursoTitulo, $calificacion, $estado, $comentario = '', $verUrl = '') {
    if (empty($estudianteEmail)) return ['ok' => false, 'error' => 'Sin email del estudiante'];
    $asunto = 'Tu actividad fue revisada: ' . $actividadTitulo . ' (' . $calificacion . '/100)';
    return enviarCorreo($estudianteEmail, $estudianteNombre, $asunto, correoActividadCalificadaHtml($estudianteNombre, $profesorNombre, $actividadTitulo, $leccionTitulo, $cursoTitulo, $calificacion, $estado, $comentario, $verUrl));
}