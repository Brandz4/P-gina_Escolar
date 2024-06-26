<?php 
    require __DIR__ . '../../../vendor/vendor/autoload.php'; // Asegúrate de que esta ruta sea correcta

    use Dotenv\Dotenv;
    $dotenv = Dotenv::createImmutable(__DIR__);
    
    try {
        $dotenv->safeLoad();
    } catch (\Dotenv\Exception\InvalidPathException $e) {
        echo "Error al cargar el archivo .env: " . $e->getMessage();
    }

    function conectarDB() : mysqli{
        $db = mysqli_connect( 
        $_ENV['DB_HOST'],
        $_ENV['DB_USER'],
        $_ENV['DB_PASS'],
        $_ENV['DB_NAME'],);

        if (!$db) {
            die("Error de conexión: " . mysqli_connect_error());
        }
        return $db;
    }
?>