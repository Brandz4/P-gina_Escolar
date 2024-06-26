<?php 
    session_start();
    // Extraer el expediente de la URL
    $id = $_GET['expediente'];
    
    if (!$_SESSION['login']) {
        header('Location: /somosUAQ/portales/portalAlumno/index.php');
        exit;
    }
    // Importa la conexion de la DB
    require '../../../includes/config/database.php';
    $db = conectarDB();
    //Ecribir el Query (la consulta)
    $query = "SELECT * FROM ALUMNOS WHERE EXPEDIENTE_ALUMNO = ".$id;
    // Consultar la DB
    $resultado = mysqli_query($db,$query);
    // extraer los datos
    $usuario = mysqli_fetch_assoc($resultado);

    $queryCarrera = "SELECT C.CLAVE_CARRERA, C.NOMBRE, A.SEMESTRE FROM CARRERAS AS C
    JOIN ALUMNOS AS A ON C.ID_CARRERA = A.ID_CARRERA
    WHERE A.EXPEDIENTE_ALUMNO = ".$id;
    // Consultar la DB
    $resultadoCarrera = mysqli_query($db,$queryCarrera);
    // extraer los datos
    $usuarioCarrera = mysqli_fetch_assoc($resultadoCarrera);

    // Consulta para obtener las calificaciones
    $consulta_calificaciones = "SELECT C.CALIFICACION
                                FROM CALIFICACIONES AS C
                                JOIN MATERIAS AS M ON C.CLAVE_MATERIA = M.CLAVE_MATERIA
                                WHERE C.EXPEDIENTE_ALUMNO = $id";
    $resultadoCalificaciones = mysqli_query($db, $consulta_calificaciones);

    // Inicializar la variable para el promedio
    $totalCalificaciones = 0;
    $cantidadCalificaciones = 0;

    // Sumar todas las calificaciones
    while ($usuarioCalificaciones = mysqli_fetch_assoc($resultadoCalificaciones)) {
        $totalCalificaciones += $usuarioCalificaciones['CALIFICACION'];
        $cantidadCalificaciones++;
    }

    // Calcular el promedio
    if ($cantidadCalificaciones > 0) {
        $promedio = $totalCalificaciones / $cantidadCalificaciones;
    } else {
        $promedio = 0; // Si no hay calificaciones, el promedio es cero
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil Alumno</title>

    <link rel="stylesheet" href="../../../build/css/app.css">
</head>
<body>
    <header class="header-principales header-azul">
            <div class="datos">
                <img src="../../../img/escudoBN.png" alt="Escudo UAQ" class="logo-footer imagen-escudo">
            </div>
            <div class="datos">
                <h3>Correo:</h3>
                <p><?php echo $usuario['CORREO_ALUMNO']; ?></p>
            </div>
            <div class="datos">
                <h3>Expediente:</h3>
                <p><?php echo $usuario['EXPEDIENTE_ALUMNO']; ?></p>
            </div>
            <div class="datos">
                <h3>Nombre:</h3>
                <p><?php echo $usuario['NOMBRE_ALUMNO']; ?></p>
            </div>
            <div class="datos">
                <h3>Carrera:</h3>
                <p><?php echo $usuario['ID_CARRERA']; ?></p>
            </div>
            <div class="imagen-usuario datos">
                <img src="../../../imagenes/<?php echo $usuario['FOTO_ALUMNO'];?>" alt="Imagen Usuario" width="120px" height="120px">
            </div>
    </header>
    <div class="barra-lateral">
        <nav class="nav-principales">
            <a 
                href="calificaciones.php?expediente=<?php echo $usuario['EXPEDIENTE_ALUMNO']; ?>" 
                class="nav-en-principal">
                Calificaciones
            </a>
            <a 
                href="constancias.php?expediente=<?php echo $usuario['EXPEDIENTE_ALUMNO']; ?>" 
                class="nav-en-principal">
                Constancias
            </a>
            <a 
                href="kardex.php?expediente=<?php echo $usuario['EXPEDIENTE_ALUMNO']; ?>" 
                class="nav-en-principal">
                Kardex
            </a>
            <a 
                href="principalAlumno.php?expediente=<?php echo $usuario['EXPEDIENTE_ALUMNO']; ?>" 
                class="nav-en-principal">
                Incio
            </a>
    </div>
    <div class="contenedor-principales">
        <div class="titulo">
            <h1>Constancias</h1>
        </div>

        <div class="constancia">
            <div class="constancia-header">
                <!-- Considerar agregar logo de la institución -->
                <h1>CONSTANCIA DE ESTUDIOS</h1>
                <img src="../../../img/escudoUAQ.jpeg" alt="Escudo UAQ" class="logo-footer imagen-escudo">
                <p>UNIVERSIDAD AUTÓNOMA DE QUERÉTARO</p>
                <p>FACULTAD DE INFORMÁTICA</p>
            </div>
            <div class="estado-academico">
                <p><strong>Nombre del Alumno:</strong> <?php echo $usuario['NOMBRE_ALUMNO']; ?></p>
                <p><strong>Número de Expediente:</strong> <?php echo $usuario['EXPEDIENTE_ALUMNO']; ?></p>
                <p><strong>Carrera:</strong> <?php echo $usuarioCarrera['NOMBRE']; ?></p>
                <p><strong>Plan de Estudios:</strong> <?php echo $usuarioCarrera['CLAVE_CARRERA']; ?></p>
                <div class="constancia-terminos">
                    <p><strong>Constancia que se extiende a solicitud del interesado, para los fines legales que requiera.</strong></p>
                    <p>El departamento de <strong>Servicios Escolares</strong> de la <strong>Universidad Autónoma de Querétaro</strong>
                    hace constar que el alumno <?php echo $usuario['NOMBRE_ALUMNO']; ?> es estudiante activo de la universidad, con <strong>Clave 
                    institucional 250002</strong> y con <strong>Clave ante la SEP 25OAO1115G</strong>.</p>
                    <p>Se hace constar que el alumno mencionado cursa actualmente la Licenciatura en <?php echo $usuarioCarrera['NOMBRE']; ?> en esta institución,
                    estando cursando el <?php echo $usuarioCarrera['SEMESTRE']; ?> semestre en el periodo académico correspondiente 
                    del <strong>15 de enero de 2024 al 14 de junio del 2024</strong>.</p>
                </div>
                <p><strong>Periodo de Estudios:</strong> Semestre Enero 2024 - Junio 2024</p>
                <p><strong>Promedio General:</strong> <?php echo $promedio; ?></p>
                <p><strong>Número de Semestres Aprobados:</strong> <?php echo $usuarioCarrera['SEMESTRE'] - 1; ?></p>
                <p>El presente documento se expide a petición del interesado, para los usos que convengan.</p>
                <p>Querétaro, Qro., <?php echo date('d/m/Y'); ?>.</p>
                <!-- Considerar agregar imagen de QR, sello o firma -->
            </div>
            <div class="constancia-footer">
                <a href="#" class="boton constancia-boton">Descargar Constancia</a>
            </div>
            </div>
            <br>
    </div>
<?php include '../../footer.php'; ?>