<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = htmlspecialchars($_POST['name']);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $message = htmlspecialchars($_POST['message']);

    if (!empty($name) && filter_var($email, FILTER_VALIDATE_EMAIL) && !empty($message)) {
        // Here you would typically send an email or save the message to a database.
        // For this example, we'll just show a success message.
        echo "<h1>Gracias por tu mensaje!</h1>";
        echo "<p>Me pondré en contacto contigo lo antes posible.</p>";
        echo "<a href='index.php'>Volver al inicio</a>";
    } else {
        echo "<h1>Error</h1>";
        echo "<p>Por favor, completa todos los campos del formulario correctamente.</p>";
        echo "<a href='index.php#contact'>Volver a intentarlo</a>";
    }
} else {
    header("Location: index.php");
}
?>
