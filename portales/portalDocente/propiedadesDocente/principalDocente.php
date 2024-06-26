<?php  
     session_start();
     // Extraer el expediente de la URL
     $id = $_GET['expediente'];
     
     if (!$_SESSION['login']) {
        header('Location: /somosUAQ/portales/portalDocente/index.php');
         exit;
     }
    // Importa la conexion de la DB
    require '../../../includes/config/database.php';
    $db = conectarDB();
    //Ecribir el Query (la consulta)
    $query = "SELECT * FROM DOCENTES WHERE EXPEDIENTE_DOCENTE = ".$id;
    // Consultar la DB
    $resultado = mysqli_query($db,$query);
    // Extraer los datos en forma de arreglo asociativo al nombre de la columna. 
    $usuario = mysqli_fetch_assoc($resultado);

     // Primera consulta para obtener los horarios del docente
    $consulta_horarios = "SELECT HORARIO_DOCENTE FROM DOCENTES WHERE EXPEDIENTE_DOCENTE = $id";
    $resultado_horarios = mysqli_query($db, $consulta_horarios);
    $resultado_horarios = mysqli_fetch_assoc($resultado_horarios);
    
    // Extraer el primer y último valor de la consulta
    $horarios_lista = $resultado_horarios['HORARIO_DOCENTE'];
    $horarios_array = explode(',', $horarios_lista);
    $primer_horario = reset($horarios_array); // Obtiene el primer valor del array
    $segundo_horario = $horarios_array[1]; // Obtiene el segundo valor del array
    $ultimo_horario = end($horarios_array);   // Obtiene el último valor del array

    // Segunda consulta para obtener los detalles del horario, ordenada por hora
    $consulta_horario = "SELECT C.NUM_GRUPO, M.NOMBRE_MATERIA, C.SALON, C.HORARIO 
    FROM CLASES AS C 
    JOIN MATERIAS AS M ON C.CLAVE_MATERIA = M.CLAVE_MATERIA
    WHERE C.ID_CLASE IN ($primer_horario, $segundo_horario, $ultimo_horario)
    ORDER BY FIELD(C.ID_CLASE, $primer_horario, $segundo_horario, $ultimo_horario)";

    $resultado_detalle_horario = mysqli_query($db, $consulta_horario);
    $horarios = array(); // Inicializa un array para almacenar todos los resultados

    // Itera sobre cada fila de resultados
    while ($fila = mysqli_fetch_assoc($resultado_detalle_horario)) {
        $horarios[] = $fila; // Agrega la fila al array de resultados
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil Docente</title>

    <link rel="stylesheet" href="../../../build/css/app.css">
</head>
<body>
    <header class="header-principales header-azul">
            <div class="datos">
                <img src="../../../img/escudoBN.png" alt="Escudo UAQ" class="logo-footer imagen-escudo">
            </div>
            <div class="datos">
                <h3>Correo:</h3>
                <p><?php echo $usuario['CORREO_DOCENTE']; ?></p>
            </div>
            <div class="datos">
                <h3>Expediente:</h3>
                <p><?php echo $usuario['EXPEDIENTE_DOCENTE']; ?></p>
            </div>
            <div class="datos">
                <h3>Nombre:</h3>
                <p><?php echo $usuario['NOMBRE_DOCENTE']; ?></p>
            </div>
            <div class="datos">
                <h3>Laboral:</h3>
                <p><?php echo $usuario['TIPO_EMPLEO']; ?></p>
            </div>
            <div class="imagen-usuario datos">
            <img src="../../../imagenes/<?php echo $usuario['FOTO_DOCENTE'];?>" alt="Imagen Usuario" width="120px" height="120px">
            </div>
    </header>
    <div class="barra-lateral">
        <nav class="nav-principales">
            <a 
                href="calificaciones.php?expediente=<?php echo $usuario['EXPEDIENTE_DOCENTE']; ?>" 
                class="nav-en-principal">
                Calificaciones
            </a>
            <a 
                href="principalDocente.php?expediente=<?php echo $usuario['EXPEDIENTE_DOCENTE']; ?>" 
                class="nav-en-principal">
                Incio
            </a>
        </nav>
    </div>
    <div class="contenedor-principales">
        <div class="titulo">
            <h1>Horario</h1>
        </div>
        <br>
            <table class="tabla-horario contenedor">
                <thead>
                    <tr>
                        <th>Lunes y Miércoles</th>
                        <th>Martes y Jueves</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        // Organizar los horarios por día de la semana
                        $lunes_miercoles = array();
                        $martes_jueves = array();

                        foreach ($horarios as $horario) {
                            if (strpos($horario['HORARIO'], 'Lunes') !== false || strpos($horario['HORARIO'], 'Miércoles') !== false) {
                                $lunes_miercoles[] = $horario;
                            } elseif (strpos($horario['HORARIO'], 'Martes') !== false || strpos($horario['HORARIO'], 'Jueves') !== false) {
                                $martes_jueves[] = $horario;
                            }
                        }

                        // Obtener el máximo número de filas entre los horarios de lunes y miércoles y martes y jueves
                        $max_filas = max(count($lunes_miercoles), count($martes_jueves));

                        // Iterar sobre cada fila
                        for ($i = 0; $i < $max_filas; $i++) {
                            echo "<tr>";

                            // Columna para lunes y miércoles
                            echo "<td>";
                            if (isset($lunes_miercoles[$i])) {
                                echo "<strong>Materia:</strong> " . $lunes_miercoles[$i]['NOMBRE_MATERIA'] . "<br>";
                                echo "<strong>Grupo:</strong> " . $lunes_miercoles[$i]['NUM_GRUPO'] . "<br>";
                                echo "<strong>Salón:</strong> " . $lunes_miercoles[$i]['SALON'] . "<br>";
                                $horario_parts = explode(',', $lunes_miercoles[$i]['HORARIO']);
                                echo "<strong>Horario:</strong> " . $horario_parts[1];
                            }
                            echo "</td>";

                            // Columna para martes y jueves
                            echo "<td>";
                            if (isset($martes_jueves[$i])) {
                                echo "<strong>Materia:</strong> " . $martes_jueves[$i]['NOMBRE_MATERIA'] . "<br>";
                                echo "<strong>Grupo:</strong> " . $martes_jueves[$i]['NUM_GRUPO'] . "<br>";
                                echo "<strong>Salón:</strong> " . $martes_jueves[$i]['SALON'] . "<br>";
                                $horario_parts = explode(',', $martes_jueves[$i]['HORARIO']);
                                echo "<strong>Horario:</strong> " . $horario_parts[1];
                            }
                            echo "</td>";

                            echo "</tr>";
                        }
                    ?>     
                </tbody>
            </table>
            <br><br><br><br><br><br>
    </div>
<?php include '../../footer.php'; ?>