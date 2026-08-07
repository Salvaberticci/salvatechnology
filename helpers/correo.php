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