<?php
header('Content-Type: application/json');

$response = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = htmlspecialchars(stripslashes(trim($_POST['name'])));
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $message = htmlspecialchars(stripslashes(trim($_POST['message'])));

    if (!empty($name) && filter_var($email, FILTER_VALIDATE_EMAIL) && !empty($message)) {
        // Aquí es donde normalmente enviarías un correo electrónico o guardarías en la base de datos.
        // Por ahora, solo enviaremos una respuesta de éxito.
        $response['status'] = 'success';
        $response['message'] = 'Tu mensaje ha sido enviado. Me pondré en contacto contigo pronto.';
    } else {
        $response['status'] = 'error';
        $response['message'] = 'Por favor, completa todos los campos del formulario correctamente.';
    }
} else {
    $response['status'] = 'error';
    $response['message'] = 'Método de solicitud no válido.';
}

echo json_encode($response);
?>
