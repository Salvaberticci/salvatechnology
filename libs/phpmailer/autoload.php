<?php
spl_autoload_register(function ($class) {
    $prefix = 'PHPMailer\\PHPMailer\\';
    $baseDir = __DIR__ . '/';
    if (strncmp($prefix, $class, strlen($prefix)) === 0) {
        $file = $baseDir . substr($class, strlen($prefix)) . '.php';
        if (file_exists($file)) {
            require $file;
        }
    }
});
