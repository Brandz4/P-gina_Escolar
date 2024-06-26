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

    $queryCarrera = "SELECT C.CLAVE_CARRERA, C.NOMBRE, A.SEMESTRE, A.ESTATUS, A.NUM_GRUPO FROM CARRERAS AS C
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

    // Consulta para obtener las calificaciones
    $consulta_calificaciones = "SELECT C.CLAVE_MATERIA, M.NOMBRE_MATERIA, C.CALIFICACION
            FROM CALIFICACIONES AS C
            JOIN MATERIAS AS M ON C.CLAVE_MATERIA = M.CLAVE_MATERIA
            WHERE C.EXPEDIENTE_ALUMNO = $id";
    $resultado_detalle_horario = mysqli_query($db, $consulta_calificaciones);
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
            <h1>Kardex Académico</h1>
        </div>

        <div class="constancia">
            <div class="constancia-header">
                <!-- Considerar agregar logo de la institución -->
                <h1>UNIVERSIDAD AUTÓNOMA DE QUERÉTARO</h1>
                <h4>Dirección Académica</h4>
                <h4>Departamento de Servicios Escolares</h4>
                <h5 class="estado-academico">Estado Académico</h5>
            </div>
            <div class="constancia-columnas">
                <div class="constancia-columna">
                    <p><strong>Alumno:</strong> <?php echo $usuario['NOMBRE_ALUMNO']; ?></p>
                    <p><strong>Expediente:</strong> <?php echo $usuario['EXPEDIENTE_ALUMNO']; ?></p>
                    <p><strong>Carrera:</strong> <?php echo $usuarioCarrera['NOMBRE']; ?></p>
                    <p><strong>Plan de Estudios:</strong> <?php echo $usuarioCarrera['CLAVE_CARRERA']; ?></p>
                </div>
                <div class="constancia-columna">
                    <p><strong>Estatus:</strong> <?php echo $usuarioCarrera['ESTATUS']; ?></p>
                    <p><strong>Facultad:</strong> Informática</p>
                    <p><strong>Grupo:</strong> <?php echo $usuarioCarrera['NUM_GRUPO']; ?></p>
                    <p><strong>Fecha de emisión:</strong> <?php echo date('d/m/Y'); ?></p>
                </div>
            </div>
            <div class="constancia-terminos">
                <p><strong>Kardex académico que se extiende a solicitud del interesado, para los fines legales que requiera.</strong></p>
                <p>El departamento de <strong>Servicios Escolares</strong> de la <strong>Universidad Autónoma de Querétaro</strong>
                    hace constar que el alumno <?php echo $usuario['NOMBRE_ALUMNO']; ?> es estudiante activo de la universidad, con <strong>Clave 
                    institucional 250002</strong> y con <strong>Clave ante la SEP 25OAO1115G</strong>. </p>
                <p>A continuación se desglosa el desempeño acádemico de <strong><?php echo $usuario['NOMBRE_ALUMNO']; ?></strong> -
                <strong><?php echo $usuario['EXPEDIENTE_ALUMNO']; ?></strong>. </p>
            </div>
            <table class="tabla-horario contenedor">
                <thead>
                    <tr>
                        <th>Clave Materia</th>
                        <th>Nombre Materia</th>
                        <th>Calificación</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        $total_calificaciones = 0;
                        $contador_calificaciones = 0;
                        while ($row = mysqli_fetch_assoc($resultado_detalle_horario)) {
                            $clave_materia = $row['CLAVE_MATERIA'];
                            $nombre_materia = $row['NOMBRE_MATERIA'];
                            $calificacion = $row['CALIFICACION'];
                            $total_calificaciones += $calificacion;
                            $contador_calificaciones++;
                    ?>
                        <tr>
                            <td><?php echo $clave_materia; ?></td>
                            <td><?php echo $nombre_materia; ?></td>
                            <td><?php echo $calificacion; ?></td>
                        </tr>
                    <?php } ?>
                    <?php if ($contador_calificaciones > 0): ?>
                        <tr>
                            <td colspan="2"><strong>Promedio</strong></td>
                            <td><?php echo $total_calificaciones / $contador_calificaciones; ?></td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
                <!-- Considerar agregar imagen de QR, sello o firma -->
            <div class="constancia-footer">
                <a href="#" class="boton constancia-boton">Descargar Kardex</a>
            </div>
        </div>
        <br>
    </div>
<?php include '../../footer.php'; ?>