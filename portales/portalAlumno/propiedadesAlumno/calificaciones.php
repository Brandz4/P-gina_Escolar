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
            <h1>Calificaciones</h1>
        </div><br>
        <div>
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
                            <td><?php echo number_format($total_calificaciones / $contador_calificaciones, 2); ?></td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div><br><br><br><br>
    </div>
<?php include '../../footer.php'; ?>