<?php
 session_start();
 // Extraer el expediente de la URL
 $id = $_GET['expediente'];
 
 if (!$_SESSION['login']) {
    header('Location: /somosUAQ/portales/portalDocente/index.php');
    exit;
 }

// Importar la conexión de la DB
require_once '../../../includes/config/database.php';
$db = conectarDB();

// Escribir la consulta para obtener los datos del docente
$query = "SELECT * FROM DOCENTES WHERE EXPEDIENTE_DOCENTE = ?";
$stmt = mysqli_prepare($db, $query);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$resultado = mysqli_stmt_get_result($stmt);
$usuario = mysqli_fetch_assoc($resultado);

// Consultar los horarios del docente
$consulta_horarios = "SELECT HORARIO_DOCENTE FROM DOCENTES WHERE EXPEDIENTE_DOCENTE = ?";
$stmt_horarios = mysqli_prepare($db, $consulta_horarios);
mysqli_stmt_bind_param($stmt_horarios, "i", $id);
mysqli_stmt_execute($stmt_horarios);
$resultado_horarios = mysqli_stmt_get_result($stmt_horarios);
$horarios_lista = mysqli_fetch_assoc($resultado_horarios)['HORARIO_DOCENTE'];
$horarios_array = explode(',', $horarios_lista);

// Obtener los grupos del maestro junto con sus IDs de clase
$array_grupos = [];
$array_idclase = []; // Array para almacenar los ID de clase asociados a cada grupo
foreach ($horarios_array as $horario) {
    $consulta_grupos = "SELECT NUM_GRUPO, ID_CLASE FROM CLASES WHERE ID_CLASE = ?";
    $stmt_grupos = mysqli_prepare($db, $consulta_grupos);
    mysqli_stmt_bind_param($stmt_grupos, "i", $horario);
    mysqli_stmt_execute($stmt_grupos);
    $resultado_grupos = mysqli_stmt_get_result($stmt_grupos);
    
    while ($grupo = mysqli_fetch_assoc($resultado_grupos)) {
        // Almacenar el número de grupo en su propio array
        $array_grupos[$grupo['NUM_GRUPO']] = $grupo['NUM_GRUPO'];

        // Almacenar el ID de clase asociado al grupo en su propio array
        $array_idclase[$grupo['NUM_GRUPO']][] = $grupo['ID_CLASE'];
    }
}

// Consulta para obtener las materias por grupo
$grupos_str = implode(',', $horarios_array);
$consulta_materias = "SELECT C.NUM_GRUPO, M.NOMBRE_MATERIA, M.CLAVE_MATERIA
                      FROM CLASES AS C 
                      JOIN MATERIAS AS M ON C.CLAVE_MATERIA = M.CLAVE_MATERIA
                      WHERE C.ID_CLASE IN ($grupos_str)
                      ORDER BY FIELD(C.ID_CLASE, $grupos_str)";
$resultado_materias = mysqli_query($db, $consulta_materias);

// Almacenar las claves de las materias en un array
$clave_materia_array = [];
while ($materia = mysqli_fetch_assoc($resultado_materias)) {
    $clave_materia_array[] = $materia['CLAVE_MATERIA'];
}

$grupos_str = implode(',', $clave_materia_array);
$consulta_alumnos = "SELECT DISTINCT(EXPEDIENTE_ALUMNO)
                     FROM CALIFICACIONES AS C 
                     WHERE CLAVE_MATERIA IN ($grupos_str)";
$resultado_alumnos = mysqli_query($db, $consulta_alumnos);

// Almacenar los expedientes de los alumnos en un array multidimensional por grupo
$alumnos_expediente = [];
while ($fila = mysqli_fetch_assoc($resultado_alumnos)) {
    $expediente_alumno = $fila['EXPEDIENTE_ALUMNO'];

    // Agregar el expediente al array de expedientes de alumnos
    $alumnos_expediente[] = $expediente_alumno;
}

// Almacenar las materias en un array multidimensional por grupo
$materias_por_grupo = [];
mysqli_data_seek($resultado_materias, 0); // Reiniciar el puntero del resultado
while ($materia = mysqli_fetch_assoc($resultado_materias)) {
    $grupo = $materia['NUM_GRUPO'];
    $materia_nombre = $materia['CLAVE_MATERIA'];

    // Verificar si el grupo ya está en el array
    if (!isset($materias_por_grupo[$grupo])) {
        $materias_por_grupo[$grupo] = []; // Inicializar un array vacío para las materias del grupo
    }

    // Agregar la materia al array del grupo correspondiente
    $materias_por_grupo[$grupo][] = $materia_nombre;
}

// Manejar la actualización de la calificación cuando se envía el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $clave_materia = filter_input(INPUT_POST, 'clave_materia', FILTER_SANITIZE_NUMBER_INT);
    $expediente_alumno = filter_input(INPUT_POST, 'expediente_alumno', FILTER_SANITIZE_NUMBER_INT);
    $calificacion = filter_input(INPUT_POST, 'calificacion', FILTER_VALIDATE_FLOAT);

    if ($clave_materia && $expediente_alumno && $calificacion !== false && $calificacion >= 0 && $calificacion <= 10) {
        $query_actualizar = "UPDATE CALIFICACIONES SET CALIFICACION = ? WHERE CLAVE_MATERIA = ? AND EXPEDIENTE_ALUMNO = ?";
        $stmt_actualizar = mysqli_prepare($db, $query_actualizar);
        mysqli_stmt_bind_param($stmt_actualizar, "dii", $calificacion, $clave_materia, $expediente_alumno);
        mysqli_stmt_execute($stmt_actualizar);
        
        if (mysqli_stmt_affected_rows($stmt_actualizar) > 0) {
            $mensajeExito = "Calificación actualizada exitosamente.";
        } else {
            $mensajeError = "Error al actualizar la calificación.";
        }
    } else {
        $mensajeError = "Datos inválidos. Asegúrese de que todos los campos estén correctamente llenados.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil Docente</title>

    <link rel="stylesheet" href="../../../build/css/app.css">
    <style>
        .boton.oculto {
            display: none;
        }
        .texto-centro {
            text-align: center;
            margin-top: 20px;
            font-size: 18px;
            color: #333;
        }
    </style>
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
            <a href="calificaciones.php?expediente=<?php echo $usuario['EXPEDIENTE_DOCENTE']; ?>" class="nav-en-principal">
                Calificaciones
            </a>
            <a href="principalDocente.php?expediente=<?php echo $usuario['EXPEDIENTE_DOCENTE']; ?>" class="nav-en-principal">
                Inicio
            </a>
        </nav>
    </div>
    <div class="contenedor-principales">
        <div class="titulo">
            <h1>Grupos</h1>
        </div><br>
        <div class="campos-crud menu-crear">
            <?php foreach ($array_grupos as $grupo): ?>
                <div class="campo-crud">
                    <button class="boton botones-crud" data-grupo="<?php echo $grupo; ?>" onclick="mostrarGrupo('<?php echo $grupo; ?>')">Grupo <?php echo $grupo; ?></button>
                </div>
            <?php endforeach; ?>
        </div>
        <div id="texto-grupo" class="texto-centro oculto "></div>
        
        <div class="contenedor contenedor-calificaciones formulario form-principales">
            <div class="seleccion-materias oculto campo">
                <label class="compo__label" for="select-materias" id="label-materias" style="display: none;">Clave Materia:</label>
                <select class="select-materias campo__field" id="select-materias" style="display: none;">
                    <!-- Las opciones de materias se llenarán dinámicamente con JavaScript -->
                </select>
            </div>
            <div class="seleccion-alumnos oculto campo">
                <label class="compo__label" for="select-alumnos" id="label-alumnos" style="display: none;">Expediente Alumno:</label>
                <select id="select-alumnos" class="select-alumnos campo__field" style="display: none;">
                    <!-- Las opciones de alumnos se llenarán dinámicamente con JavaScript -->
                </select>
            </div>
            <div class="campo">
                <label class="compo__label" for="calificacion" id="label-calificacion" style="display: none;">Calificación:</label>
                <input class="campo__field" type="number" id="calificacion" style="display: none;" name="calificacion" min="0" max="10">
            </div>
            <div class="campo">
                <button class="pass-boton" id="enviar" style="display: none;" onclick="enviarCalificacion()">Enviar</button>
            </div>
        </div>
            <div id="materias-data" style="display: none;"><?php echo json_encode($materias_por_grupo); ?></div>
            <div id="alumnos-data" style="display: none;"><?php echo json_encode($alumnos_expediente); ?></div>

            <?php if (isset($mensajeError)): ?>
                <div class="mensaje alerta error">
                    <?php echo $mensajeError; ?>
                </div>
            <?php endif; ?>
            <?php if (isset($mensajeExito)): ?>
                <div class="mensaje alerta exito">
                    <?php echo $mensajeExito; ?>
                </div>
            <?php endif; ?>
        </div>

    <script>
    function mostrarGrupo(grupo) {
        // Ocultar todos los botones
        var botones = document.querySelectorAll('.botones-crud');
        botones.forEach(function(boton) {
            boton.style.display = 'none';
        });

        // Mostrar el select de materias y el texto del grupo
        let selectMaterias = document.getElementById('select-materias');
        selectMaterias.style.display = 'block'; // Mostrar el select
        let labelMaterias = document.getElementById('label-materias');
        labelMaterias.style.display = 'block'; // Mostrar el label

        // Mostrar el select de materias y el texto del grupo
        let calificacion = document.getElementById('calificacion');
        calificacion.style.display = 'block'; // Mostrar el input de calificación
        let labelCalificacion = document.getElementById('label-calificacion');
        labelCalificacion.style.display = 'block'; // Mostrar el label
        let enviar = document.getElementById('enviar');
        enviar.style.display = 'block'; // Mostrar el botón enviar

        // Mostrar el select de alumnos y el texto del grupo
        let selectAlumnos = document.getElementById('select-alumnos');
        selectAlumnos.style.display = 'block'; // Mostrar el select
        let labelAlumnos = document.getElementById('label-alumnos');
        labelAlumnos.style.display = 'block'; // Mostrar el label

        // Obtener y mostrar las materias del grupo seleccionado
        let materiasPorGrupoMaterias = JSON.parse(document.getElementById('materias-data').textContent);
        let materiasDelGrupo = materiasPorGrupoMaterias[grupo];
        selectMaterias.innerHTML = ''; // Limpiar el select antes de agregar las nuevas opciones
        materiasDelGrupo.forEach(materia => {
            let option = document.createElement('option');
            option.text = materia;
            selectMaterias.add(option);
        });

        // Obtener y mostrar los alumnos del grupo seleccionado
        let alumnosPorGrupo = JSON.parse(document.getElementById('alumnos-data').textContent);
        selectAlumnos.innerHTML = ''; // Limpiar el select antes de agregar las nuevas opciones
        alumnosPorGrupo.forEach(expediente => {
            let option = document.createElement('option');
            option.text = expediente;
            selectAlumnos.add(option);
        });
    }

    function enviarCalificacion() {
        // Obtener los valores seleccionados
        let materia = document.getElementById('select-materias').value;
        let alumno = document.getElementById('select-alumnos').value;
        let calificacion = document.getElementById('calificacion').value;

        // Validar la calificación
        if (calificacion < 0 || calificacion > 10) {
            alert('La calificación debe ser un valor numérico entre 0 y 10');
            return;
        }

        // Crear un formulario oculto y enviar los datos por POST
        let form = document.createElement('form');
        form.method = 'POST';
        form.style.display = 'none';

        let inputMateria = document.createElement('input');
        inputMateria.name = 'clave_materia';
        inputMateria.value = materia;
        form.appendChild(inputMateria);

        let inputAlumno = document.createElement('input');
        inputAlumno.name = 'expediente_alumno';
        inputAlumno.value = alumno;
        form.appendChild(inputAlumno);

        let inputCalificacion = document.createElement('input');
        inputCalificacion.name = 'calificacion';
        inputCalificacion.value = calificacion;
        form.appendChild(inputCalificacion);

        document.body.appendChild(form);
        form.submit();
    }
    </script>
    <br><br><br><br><br>
    <?php include '../../footer.php'; ?>
</body>
</html>