<?php
    require __DIR__ . '../../../vendor/autoload.php'; // Asegúrate de que esta ruta sea correcta

    use Dotenv\Dotenv;
    $dotenv = Dotenv::createImmutable(__DIR__);
    
    try {
        $dotenv->safeLoad();
    } catch (\Dotenv\Exception\InvalidPathException $e) {
        echo "Error al cargar el archivo .env: " . $e->getMessage();
    }

?>