<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);

    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // Here you would typically save the email to a database or a file.
        // For this example, we'll just show a success message.
        echo "<h1>Gracias por suscribirte!</h1>";
        echo "<p>Pronto recibirás noticias de Salva Technology.</p>";
        echo "<a href='index.php'>Volver al inicio</a>";
    } else {
        echo "<h1>Error</h1>";
        echo "<p>El correo electrónico proporcionado no es válido.</p>";
        echo "<a href='index.php#contact'>Volver a intentarlo</a>";
    }
} else {
    header("Location: index.php");
}
?>
