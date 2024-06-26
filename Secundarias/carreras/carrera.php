<?php 
    include '../../templates/header.php';

    // Importar la conexión a la base de datos
    require '../../includes/config/database.php';
    $db = conectarDB();

    // Validar el ID
    $id = filter_var($_GET['id'], FILTER_VALIDATE_INT);

    if (!$id) {
        header('Location: ../../index.php');
        exit;
    }

    // Escribir el Query para obtener detalles de la carrera
    $query = "SELECT * FROM CARRERAS WHERE ID_CARRERA = ${id}";

    // Consultar la base de datos
    $resultado = mysqli_query($db, $query);

    if ($resultado->num_rows === 0) {
        header('Location: ../../index.php');
        exit;
    }

    $carrera = mysqli_fetch_assoc($resultado);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($carrera['NOMBRE']); ?> - SomosUAQ</title>
    <meta name="description" content="Página web principal de la UAQ">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans&family=PT+Sans:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../../build/css/app.css">
</head>
<body>
    <div class="barra">
        <a class="logo" href="../../index.php">
            <h1 class="logo__nombre no-margin centrar-texto">Somos<span class="logo__bold">UAQ</span></h1>
        </a>

        <nav class="navegacion">
            <a href="../../Convocatorias.php" class="navegacion__enlace">Convocatorias</a>
            <a href="../../Carreras.php" class="navegacion__enlace">Carreras</a>
            <a href="../../Servicios.php" class="navegacion__enlace">Servicios</a>
        </nav>
    </div>
    <main>
        <div class="contenedor contenedor-convocatorias">
            <div class="Convotatorias">
                <div class="titulo">
                    <h1><?php echo htmlspecialchars($carrera['NOMBRE']); ?></h1>
                    <?php 
                        // Definir la ruta de la imagen según la carrera
                        $imagen = '';
                        switch ($carrera['NOMBRE']) {
                            case 'Ingenieria de Software':
                                $imagen = '../../img/software.png';
                                break;
                            case 'Ingenieria de Informatica':
                                $imagen = '../../img/informatica.jpg';
                                break;
                            case 'Ingenieria de Telecomunicaciones':
                                $imagen = '../../img/telecomunicaciones.jpg';
                                break;
                            case 'Ingeniería de Ciencia de Datos':
                                $imagen = '../../img/cienciadatos.png';
                                break;
                            default:
                                $imagen = '../../img/software.png';
                                break;
                        }
                    ?>
                    <img src="<?php echo $imagen; ?>" alt="<?php echo htmlspecialchars($carrera['NOMBRE']); ?>" class="imagen-clase">
                </div>
            </div>
        </div>
    </main>
    <?php include '../../templates/footer.php'; ?>
</body>
</html>
