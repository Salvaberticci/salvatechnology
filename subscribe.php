<?php
require_once __DIR__ . '/config/app.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);

    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<h1>Gracias por suscribirte!</h1>";
        echo "<p>Pronto recibiras noticias de Salva Technology.</p>";
        echo "<a href='" . BASE_URL . "'>Volver al inicio</a>";
    } else {
        echo "<h1>Error</h1>";
        echo "<p>El correo electronico proporcionado no es valido.</p>";
        echo "<a href='" . BASE_URL . "#contact'>Volver a intentarlo</a>";
    }
} else {
    header("Location: " . BASE_URL);
}
?>
