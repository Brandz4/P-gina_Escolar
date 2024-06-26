<?php
session_start();
// Extraer el expediente de la URL
$id = $_GET['expediente'];

if (!$_SESSION['login']) {
    header('Location: /somosUAQ/admin/index.php');
    exit;
}

// Importa la conexion de la DB
require '../../includes/config/database.php';
$db = conectarDB();
//Importar las funciones del proyecto
require '../../includes/funciones.php';

//CONSULTAS
//Ecribir el Query (la consulta)
$query = "SELECT * FROM ADMINISTRATIVOS WHERE EXPEDIENTE_ADMIN = ".$id;
$queryCarreras = "SELECT * FROM CARRERAS";
$queryGrupos = "SELECT * FROM GRUPOS";
$queryHorarios = "SELECT * FROM CLASES";
$queryMaterias = "SELECT * FROM MATERIAS";

// Consultar la DB
$resultado = mysqli_query($db,$query);
$resultadoCarreras = mysqli_query($db,$queryCarreras);
$resultadoGrupos = mysqli_query($db,$queryGrupos);
$resultadoHorarios = mysqli_query($db,$queryHorarios);
$resultadoMaterias = mysqli_query($db,$queryMaterias);

// extraer los datos
$usuario = mysqli_fetch_assoc($resultado);

// Arreglo con lo mensajes de errores
$errores = [];
$exito = 0;

//VARIABLES
$id_campos = '';
$nombre = '';
$correo = '';
$telefono = '';
$direccion = '';
$expediente = '';
$estatus = '';
$nip = '';
$carreras = '';
$grupos = '';
$semestre = '';
$horarios = [];
$empleo = '';
$materias = '';
$salon = '';
$horario_clase = '';
$creacion = date('Y/m/d');
$num_grupo = '';
$num_alumnos = '';
$horarios_grupo = [];
$descripcion1 = '';
$descripcion2 = '';
$enlace = '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil Administrador</title>

    <link rel="stylesheet" href="../../build/css/app.css">
    <?php
        if ($_SERVER['REQUEST_METHOD'] === 'GET'){
            if (isset($_GET['busqueda-alumno'])) {
                $busqueda = $_GET['busqueda-alumno'];
                $queryActulizar = consultaBusqueda("ALUMNOS","EXPEDIENTE_ALUMNO",$busqueda);
                ocultarMenu("form-crear-alumno");
                $resultadoActualizar = mysqli_query($db,$queryActulizar);
                $alumno = mysqli_fetch_assoc($resultadoActualizar);
                //Asignar sus datos a las variables$busqueda 
                $nombre = $alumno['NOMBRE_ALUMNO'];
                $correo = $alumno['CORREO_ALUMNO'];
                $telefono = $alumno['NUMERO_CONTACTO'];
                $direccion = $alumno['DIRECCION_ALUMNO'];
                $expediente  = $alumno['EXPEDIENTE_ALUMNO'];
                $busqueda  = $alumno['EXPEDIENTE_ALUMNO'];
                $estatus = $alumno['ESTATUS'];
                $grupos = $alumno['NUM_GRUPO'];
                $carreras = $alumno['ID_CARRERA'];
                $semestre = $alumno['SEMESTRE'];
            }
            if (isset($_GET['busqueda-docente'])){
                $busqueda = $_GET['busqueda-docente'];
                $queryActulizar = consultaBusqueda("DOCENTES","EXPEDIENTE_DOCENTE",$busqueda);
                ocultarMenu("form-crear-docente");
                $resultadoActualizar = mysqli_query($db,$queryActulizar);
                $docente = mysqli_fetch_assoc($resultadoActualizar);

                // //Asignar sus datos a las variables
                $nombre = $docente['NOMBRE_DOCENTE'];
                $correo = $docente['CORREO_DOCENTE'];
                $telefono = $docente['NUMERO_CONTACTO'];
                $direccion = $docente['DIRECCION_DOCENTE'];
                $expediente = $docente['EXPEDIENTE_DOCENTE'];
                $empleo = $docente['TIPO_EMPLEO'];
                $stringHorario = $docente['HORARIO_DOCENTE'];
                $horarios = explode(",", $stringHorario);
            }
            if (isset($_GET['busqueda-materia'])){
                $busqueda = $_GET['busqueda-materia'];
                $queryActulizar = consultaBusqueda("MATERIAS","CLAVE_MATERIA",$busqueda);
                ocultarMenu("form-crear-materia");
                $resultadoActualizar = mysqli_query($db,$queryActulizar);
                $materia = mysqli_fetch_assoc($resultadoActualizar);

                //Asignar los datos a las variables
                $clave_materia = $materia['CLAVE_MATERIA'];
                $nombre  = $materia['NOMBRE_MATERIA'];
                $semestre  = $materia['SEMESTRE'];
            }
            if (isset($_GET['busqueda-clase'])){
                $busqueda = $_GET['busqueda-clase'];
                $queryActulizar = consultaBusqueda("CLASES","ID_CLASE",$busqueda);
                ocultarMenu("form-crear-clase");
                $resultadoActualizar = mysqli_query($db,$queryActulizar);
                $clase = mysqli_fetch_assoc($resultadoActualizar);

                $id_campos =  $clase['ID_CLASE'];
                $materias = $clase['CLAVE_MATERIA'];
                $carreras = $clase['ID_CARRERA'];
                $expediente  = $clase['EXPEDIENTE_DOCENTE'];
                $salon  = $clase['SALON'];
                $horario_clase = $clase['HORARIO'];
                $grupos = $clase['NUM_GRUPO'];
            }
            if (isset($_GET['busqueda-grupo'])){
                $busqueda = $_GET['busqueda-grupo'];
                $queryActulizar = consultaBusqueda("GRUPOS","NUM_GRUPO",$busqueda);
                ocultarMenu("form-crear-grupo");
                $resultadoActualizar = mysqli_query($db,$queryActulizar);
                $grupo = mysqli_fetch_assoc($resultadoActualizar);

                $num_grupos = $grupo['NUM_GRUPO'];
                $num_alumnos = $grupo['TOTAL_ALUMNOS'];
                $carreras = $grupo['ID_CARRERA'];
                $stringHorario = $grupo['HORARIO'];
                $horarios_grupo = explode(",", $stringHorario);
            }
            if (isset($_GET['busqueda-carrera'])){
                $busqueda = $_GET['busqueda-carrera'];
                $queryActulizar = consultaBusqueda("CARRERAS","CLAVE_CARRERA",$busqueda);
                ocultarMenu("form-crear-carrera");
                $resultadoActualizar = mysqli_query($db,$queryActulizar);
                $carrera = mysqli_fetch_assoc($resultadoActualizar);

                $id_campos = $carrera['ID_CARRERA'];
                $clave_materia  = $carrera['CLAVE_CARRERA'];
                $nombre  = $carrera['NOMBRE'];
            }
            if (isset($_GET['busqueda-blog'])){
                $busqueda = $_GET['busqueda-blog'];
                $queryActulizar = consultaBusqueda("BLOG","ENLACE","$busqueda");
                ocultarMenu("form-crear-blog");
                $resultadoActualizar = mysqli_query($db,$queryActulizar);
                $blog = mysqli_fetch_assoc($resultadoActualizar);

                $id_campos = $blog['ID_BLOG'];
                $nombre = $blog['TITULO'];
                $descripcion1 = $blog['DESCRIPCION_BREVE'];
                $descripcion2 = $blog['DESCRIPCION_DETALLADA'];
                $enlace = $blog['ENLACE'];
            }
            if (isset($_GET['busqueda-MD'])){
                $busqueda = $_GET['busqueda-MD'];
                $queryActulizar = consultaBusqueda("MAESTRIAS_DOCTORADOS","NOMBRE","$busqueda");
                ocultarMenu("form-crear-MD");
                $resultadoActualizar = mysqli_query($db,$queryActulizar);
                $MD = mysqli_fetch_assoc($resultadoActualizar);

                $id_campos = $MD['ID_MD'];
                $nombre  = $MD['NOMBRE'];
                $descripcion1  = $MD['DESCRIPCION_BREVE'];
                $cupo  = $MD['CUPO'];
                $facultad  = $MD['FACULTAD'];
                $telefono  = $MD['TELEFONO'];
            }
        }
        // VALIDACIÓN DE LOS FORMULARIOS PARA ACTUALIZAR
        if ($_SERVER['REQUEST_METHOD'] === 'POST'){
            if (isset($_POST['formulario-crear']) && $_POST['formulario-crear'] === 'Formulario_Alumno') {
                // Procesa el formulario de alumnos
                $expediente = mysqli_real_escape_string($db, $_POST['expediente-alumno']);

                $queryAlumno = "DELETE FROM ALUMNOS WHERE EXPEDIENTE_ALUMNO = ".$expediente;
                $resultadoAlumno = mysqli_query($db,$queryAlumno);
                $exito = 1;
            }
            if (isset($_POST['formulario-crear']) && $_POST['formulario-crear'] === 'Formulario_Docente'){
                // Procesa el formulario de docentes
                $expediente = mysqli_real_escape_string($db, $_POST['expediente-docente']);

                $queryDocente = "DELETE FROM DOCENTES WHERE EXPEDIENTE_DOCENTE = $expediente ;";
                mysqli_query($db,$queryDocente);
                $exito = 2;
            }
            if (isset($_POST['formulario-crear']) && $_POST['formulario-crear'] === 'Formulario_Materia'){
                
                $clave_materia = mysqli_real_escape_string($db, $_POST['clave-materia']);

                $queryMateria = "DELETE FROM MATERIAS WHERE CLAVE_MATERIA = $clave_materia;";
                mysqli_query($db,$queryMateria);
                $exito = 3;
            }
            if (isset($_POST['formulario-crear']) && $_POST['formulario-crear'] === 'Formulario_Clase'){
                $id_campos = $_POST['id-campos'];

                $queryClase = "DELETE FROM CLASES WHERE ID_CLASE = $id_campos;";
                mysqli_query($db,$queryClase);
                $exito = 4;
            }
            if (isset($_POST['formulario-crear']) && $_POST['formulario-crear'] === 'Formulario_Grupo'){
                $num_grupos = $_POST['num-grupo'];

                $queryGrupo = "DELETE FROM GRUPOS WHERE NUM_GRUPO = $num_grupos;";
                mysqli_query($db,$queryGrupo);
                $exito = 5;
            }
            if (isset($_POST['formulario-crear']) && $_POST['formulario-crear'] === 'Formulario_Carrera'){
                $id_campos = $_POST['id-carrera'];

                $queryCarrera = "DELETE FROM CARRERAS WHERE ID_CARRERA = $id_campos;";
                mysqli_query($db,$queryCarrera);
                $exito = 6;
            }
            if (isset($_POST['formulario-crear']) && $_POST['formulario-crear'] === 'Formulario_Blog'){
                $id_campos = $_POST['id-blog'];

                $queryBlog = "DELETE FROM BLOG WHERE ID_BLOG = $id_campos";
                mysqli_query($db,$queryBlog);
                $exito = 7;
            }
            if (isset($_POST['formulario-crear']) && $_POST['formulario-crear'] === 'Formulario_MD'){
                $id_campos = $_POST['id-MD'];

                $queryMD = "DELETE FROM MAESTRIAS_DOCTORADOS  WHERE ID_MD = $id_campos;";
                mysqli_query($db,$queryMD);
                $exito = 8;
            }
        }
    ?>
</head>
<body>
    <header class="header-principales header-azul">
            <div class="datos">
                <img src="../../img/escudoBN.png" alt="Escudo UAQ" class="logo-footer imagen-escudo">
            </div>
            <div class="datos">
                <h3>Correo:</h3>
                <p><?php echo $usuario['CORREO_ADMIN']; ?></p>
            </div>
            <div class="datos">
                <h3>Expediente:</h3>
                <p><?php echo $usuario['EXPEDIENTE_ADMIN']; ?></p>
            </div>
            <div class="datos">
                <h3>Nombre:</h3>
                <p><?php echo $usuario['NOMBRE_ADMIN']; ?></p>
            </div>
            <div class="datos">
                <h3>Laboral:</h3>
                <p><?php echo $usuario['CARGO_ADMIN']; ?></p>
            </div>
            <div class="imagen-usuario datos">
                <img src="../../imagenes/<?php echo $usuario['FOTO_ADMIN']?>" alt="Imagen Usuario" width="120" height="120">
            </div>
    </header>
    <div class="barra-lateral">
        <nav class="nav-principales">
            <a 
                href="crear.php?expediente=<?php echo $usuario['EXPEDIENTE_ADMIN']; ?>" 
                class="nav-en-principal">
                Crear
            </a>
            <a 
                href="actualizar.php?expediente=<?php echo $usuario['EXPEDIENTE_ADMIN']; ?>" 
                class="nav-en-principal">
                Actualizar
            </a>
            <a 
                href="borrar.php?expediente=<?php echo $usuario['EXPEDIENTE_ADMIN']; ?>" 
                class="nav-en-principal">
                Borrar
            </a>
            <a 
                href="principalAdmin.php?expediente=<?php echo $usuario['EXPEDIENTE_ADMIN']; ?>" 
                class="nav-en-principal">
                Incio
            </a>
    </div>
    <div class="contenedor-principales">
        <div class="titulo">
            <h1>Borrar</h1>
        </div>
        <br><br>
        <div class="campos-crud menu-crear">
            <div class="campo-crud">
                <button class="boton botones-crud menu-actualizar-alumno">Alumno</button>
            </div>
            <div class="campo-crud">
                <button class="boton botones-crud menu-actualizar-docente">Docente</button>
            </div>
            <div class="campo-crud">
                <button class="boton botones-crud menu-actualizar-materia">Materia</button>
            </div>
            <div class="campo-crud">
                <button class="boton botones-crud menu-actualizar-clase">Clase</button>
            </div>
            <div class="campo-crud">
                <button class="boton botones-crud menu-actualizar-grupo">Grupo</button>
            </div>
            <div class="campo-crud">
                <button class="boton botones-crud menu-actualizar-carrera">Carrera</button>
            </div>
            <div class="campo-crud">
                <button class="boton botones-crud menu-actualizar-blog">Blog</button>
            </div>
            <div class="campo-crud menu-MD">
                <button class="boton botones-crud menu-actualizar-MD">Maestria-Doctorado</button>
            </div>
        </div>

        <div class="buscacion buscacion-alumno">
                <h4>Ingresa el expediente del alumno</h4>
                <form method="GET" id="formActualizar">
                    <input type="hidden" name="expediente" value="<?php echo $id ?>">
                    <input  type="text" id="busqueda-alumno" name="busqueda-alumno" placeholder="Buscar...">
                    <input type="submit" value="Buscar">
                </form>
        </div>
        <!-- Contenedor de ALUMNO -->
        <div class="form-crear-alumno contenedor">
            <form class="formulario form-principales" method="POST">
                <input type="hidden" name="formulario-crear" value="Formulario_Alumno">
                <h2 class="titulo">Ingresa los datos del Alumno</h2>
                <fieldset class="contenedor-contacto campo-principales">
                    <legend class="campo__legend" >Datos Personales</legend>
                    <div class="campo">
                        <label class="campo__label" for="nombre-alumno">Nombre</label>
                        <input class="campo__field" type="text" id="nombre-alumno" name="nombre-alumno" placeholder="Nombre del alumno" value="<?php echo $nombre ;?>">
                    </div>
                    <div class="campo">
                        <label class="campo__label" for="correo-alumno">Correo</label>
                        <input class="campo__field" type="text" id="correo-alumno" name="correo-alumno" placeholder="Correo del alumno" value="<?php echo $correo ;?>" readonly>
                    </div>
                    <div class="campo">
                        <label class="campo__label" for="telefono-alumno">Teléfono</label>
                        <input class="campo__field" type="number" id="telefono-alumno" name="telefono-alumno" placeholder="Telefono del alumno" value="<?php echo $telefono ;?>" readonly>
                    </div>
                    <div class="campo">
                        <label class="campo__label" for="direccion-alumno">Dirección</label>
                        <input class="campo__field" type="text" id="direccion-alumno" name="direccion-alumno" placeholder="Direccion del alumno" value="<?php echo $direccion ;?>"  readonly>
                    </div>
                </fieldset>
                <fieldset class="contenedor-contacto campo-principales">
                    <legend class="campo__legend">Datos Acádemicos</legend>
                    <div class="campo">
                        <p class="indicacion-horario ">No puede modificar el expediente</p>
                    </div>
                    <div class="campo">
                        <label class="campo__label" for="expediente-alumno">Expediente</label>
                        <input class="campo__field" type="text" id="expediente-alumno" name="expediente-alumno" placeholder="Expediente del alumno" value="<?php echo $expediente ;?>" readonly>
                    </div>
                    <div class="campo">
                        <label class="campo__label" for="estatus-alumno">Estatus</label>
                        <select name="estatus-alumno" class="campo__field" readonly>
                            <option value="">-Seleccione-</option>
                            <option <?php echo $estatus === 'Inscrito, con recibo pagado' ? 'selected' : '' ?>  value="Inscrito, con recibo pagado">Inscrito, con recibo pagado</option>
                            <option <?php echo $estatus === 'Inscrito, con recibo no pagado' ? 'selected' : '' ?>  value="Inscrito, con recibo no pagado">Inscrito, con recibo no pagado</option>
                        </select>
                    </div>
                    <div class="campo">
                        <label class="campo__label" for="grupo-alumno">Grupo</label>
                        <select name="grupo-alumno" class="campo__field" readonly>
                            <option value="">-Seleccione-</option>
                                <?php while($grupo = mysqli_fetch_assoc($resultadoGrupos)):?>
                                    <option <?php echo $grupos === $grupo['NUM_GRUPO'] ? 'selected' : '' ?>  value="<?php echo $grupo['NUM_GRUPO']; ?>"><?php echo $grupo['NUM_GRUPO']; ?></option>
                                <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="campo">
                        <label class="campo__label" for="carrera-alumno">Carrera</label>
                        <select name="carrera-alumno" class="campo__field" readonly>
                            <option value="">-Seleccione-</option>
                                <?php while($carrera = mysqli_fetch_assoc($resultadoCarreras)):?>
                                    <option <?php echo $carreras === $carrera['ID_CARRERA'] ? 'selected' : '' ?> value="<?php echo $carrera['ID_CARRERA']; ?>"><?php echo $carrera['NOMBRE']; ?></option>
                                <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="campo">
                        <label class="campo__label" for="semestre-alumno">Semestre</label>
                        <input class="campo__field" type="number" id="semestre-alumno" name="semestre-alumno" placeholder="Semestre del alumno" value="<?php echo $semestre ;?>" readonly readonly>
                    </div>
                </fieldset>
                <div class="pass">
                    <input type="submit" value="Borrar" class="pass-boton">
                </div>
            </form>
        </div>

    
        <!-- Contenedor de DOCENTE -->
        <div class="buscacion buscacion-docente" >
            <h4>Ingresa el expediente del docente</h4>
            <form method="GET">
                <input type="hidden" name="expediente" value="<?php echo $id ?>">
                <input type="text" id="busqueda-docente" name="busqueda-docente" placeholder="Buscar...">
                <input type="submit" value="Buscar">
            </form>
        </div>
        <div class="form-crear-docente contenedor">
            <form class="formulario form-principales" method="POST">
                <input type="hidden" name="formulario-crear" value="Formulario_Docente">
                <h2 class="titulo">Ingresa los datos del Docente</h2>
                <fieldset class="contenedor-contacto campo-principales">
                    <legend class="campo__legend" >Datos Personales</legend>
                    <div class="campo">
                        <label class="campo__label" for="nombre-docente">Nombre</label>
                        <input class="campo__field" type="text" id="nombre-docente" name="nombre-docente" placeholder="Nombre del docente" value="<?php echo $nombre ;?>" readonly>
                    </div>
                    <div class="campo">
                        <label class="campo__label" for="correo-docente">Correo</label>
                        <input class="campo__field" type="text" id="corree-docente" name="correo-docente" placeholder="Correo del docente" value="<?php echo $correo ;?>" readonly>
                    </div>
                    <div class="campo">
                        <label class="campo__label" for="telefono-docente">Teléfono</label>
                        <input class="campo__field" type="number" id="telefone-docente" name="telefono-docente" placeholder="Telefono del docente" value="<?php echo $telefono ;?>" readonly>
                    </div>
                    <div class="campo">
                        <label class="campo__label" for="direccion-docente">Dirección</label>
                        <input class="campo__field" type="text" id="direccioe-docente" name="direccion-docente" placeholder="Direccion del docente" value="<?php echo $direccion ;?>" readonly>
                    </div>
                </fieldset>
                <fieldset class="contenedor-contacto campo-principales">
                    <legend class="campo__legend">Datos Acádemicos</legend>
                    <div class="campo">
                        <p class="indicacion-horario ">No puede modificar el expediente</p>
                    </div>
                    <div class="campo">
                        <label class="campo__label" for="expediente-docente">Expediente</label>
                        <input class="campo__field" type="text" id="expediente-docente" name="expediente-docente" placeholder="Expediente del docente" value="<?php echo $expediente ;?>" readonly>
                    </div>
                    <div class="campo">
                                    <label class="campo__label" for="empleo-docente">Estatus</label>
                                    <select name="empleo-docente" class="campo__field">
                                        <option value="">-Seleccione-</option readonly>
                                        <option <?php echo $empleo === 'Tiempo completo' ? 'selected' : '' ?>  value="Tiempo completo">Tiempo completo</option>
                                        <option <?php echo $empleo === 'Medio tiempo' ? 'selected' : '' ?>  value="Medio tiempo">Medio tiempo</option>
                                    </select>
                                </div>
                    <div class="campo">
                            <P class="indicacion-horario">Presiona "Ctrl" y has clics sobre los horarios que quieras elegir</P>
                        </div>
                    <div class="campo">
                        <label class="campo__label" for="horarios_grupo">Horario</label>
                        <select name="horarios_grupo[]" class="campo__horario" id="horarios_grupo" multiple readonly>
                            <?php while($horario= mysqli_fetch_assoc($resultadoHorarios)):?>
                                <option <?php echo in_array($horario['ID_CLASE'],$horarios) ? 'selected' : '' ?> value="<?php echo $horario['ID_CLASE']; ?>" class="linea-inferior">
                                    <?php echo $horario['HORARIO']; ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                </fieldset>
                <div class="campo pass">
                    <input type="submit" value="Borrar" class="pass-boton">
                </div>
            </form>
        </div>
        <!-- Contenedor de MATERIA -->
        <div class="buscacion buscacion-materia">
            <h4>Ingresa la clave de la materia</h4>
            <form method="GET">
                <input type="hidden" name="expediente" value="<?php echo $id ?>">
                <input type="text" id="busqueda-materiae" name="busqueda-materia" placeholder="Buscar...">
                <input type="submit" value="Buscar">
            </form>
        </div>
        <div class="form-crear-materia contenedor">
            <form class="formulario form-principales" method="POST">
                <input type="hidden" name="formulario-crear" value="Formulario_Materia">
                <h2 class="titulo">Ingresa los datos de la Materia</h2>
                <fieldset class="contenedor-contacto campo-principales">
                    <legend class="campo__legend" >Datos de la Materia</legend>
                    <div class="campo">
                        <p class="indicacion-horario ">No puede modificar la clave de la marteria</p>
                    </div>
                    <div class="campo">
                        <label class="campo__label" for="clave-materia">Clave</label>
                        <input class="campo__field" type="text" id="clave-materia" name="clave-materia" placeholder="Clave de la materia" value="<?php echo $clave_materia ;?>" readonly>
                    </div>
                    <div class="campo">
                        <label class="campo__label" for="nombre-materia">Nombre</label>
                        <input class="campo__field" type="text" id="nombre-materia" name="nombre-materia" placeholder="Nombre de la materia" value="<?php echo $nombre ;?>" readonly>
                    </div>
                    <div class="campo">
                        <label class="campo__label" for="semestre-materia">Semestre</label>
                        <input class="campo__field" type="number" id="semestre-materia" name="semestre-materia" placeholder="Semestre de la materia" value="<?php echo $semestre ;?>" readonly>
                    </div>
                </fieldset>
                <div class="campo pass">
                    <input type="submit" value="Borrar" class="pass-boton">
                </div>
            </form>
        </div>
        <!-- Contenedor de CLASE -->
        <div class="buscacion buscacion-clase">
            <h4>Ingresa el ID de la clase</h4>
            <form method="GET" id="formActualizar">
                <input type="hidden" name="expediente" value="<?php echo $id ?>">
                <input type="text" id="busqueda-clase" name="busqueda-clase" placeholder="Buscar...">
                <input type="submit" value="Buscar">
            </form>
        </div>
        <div class="form-crear-clase contenedor">
            <form class="formulario form-principales" method="POST">
                <input type="hidden" name="formulario-crear" value="Formulario_Clase">
                <h2 class="titulo">Ingresa los datos de la Clase</h2>
                <fieldset class="contenedor-contacto campo-principales">

                    <legend class="campo__legend" >Datos de la Clase</legend>
                    <?php
                    // Reiniciar las consultas que YA se habían iterado
                    $resultadoGrupos->data_seek(0);
                    $resultadoCarreras->data_seek(0);
                    ?>
                    <div class="campo">
                        <p class="indicacion-horario ">No puede modificar el ID de la clase</p>
                    </div>
                    <div class="campo">
                        <label class="campo__label" for="id-campos">ID Clase</label>
                        <input class="campo__field" type="text" id="id-campos" name="id-campos" placeholder="ID de la clase" value="<?php echo $id_campos ;?>" readonly>
                    </div>
                    <div class="campo">
                        <label class="campo__label" for="clase-materia">Materia</label>
                        <select name="clase-materia" class="campo__field">
                            <option value="">-Seleccione-</option>
                            <?php while($materia = mysqli_fetch_assoc($resultadoMaterias)): ?>
                                <option <?php echo $materias === $materia['CLAVE_MATERIA'] ? 'selected' : ''; ?> value="<?php echo $materia['CLAVE_MATERIA']; ?>">
                                    <?php echo $materia['NOMBRE_MATERIA']; ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="campo">
                        <label class="campo__label" for="grupo-alumno">Grupo</label>
                        <select name="grupo-alumno" class="campo__field">
                            <option value="">-Seleccione-</option>
                                <?php while($grupo = mysqli_fetch_assoc($resultadoGrupos)):?>
                                    <option <?php echo $grupos === $grupo['NUM_GRUPO'] ? 'selected' : '' ?> value="<?php echo $grupo['NUM_GRUPO']; ?>"><?php echo $grupo['NUM_GRUPO']; ?></option>
                                <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="campo">
                        <label class="campo__label" for="carrera-alumno">Carrera</label>
                        <select name="carrera-alumno" class="campo__field">
                            <option value="">-Seleccione-</option>
                                <?php while($carrera = mysqli_fetch_assoc($resultadoCarreras)):?>
                                    <option <?php echo $carreras === $carrera['ID_CARRERA'] ? 'selected' : '' ?> value="<?php echo $carrera['ID_CARRERA']; ?>"><?php echo $carrera['NOMBRE']; ?></option>
                                <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="campo">
                        <label class="campo__label" for="expediente-docente">Expediente</label>
                        <input class="campo__field" type="number" id="expediente-docente" name="expediente-docente" placeholder="Expediente del docente que dará la materia" value="<?php echo $expediente ;?>" readonly>
                    </div>
                    <div class="campo">
                        <label class="campo__label" for="salon-materia">Salon</label>
                        <input class="campo__field" type="text" id="salon-materia" name="salon-materia" placeholder="Salon donde se impartirá la materia" value="<?php echo $salon ;?>" readonly>
                    </div>
                    <div class="campo">
                        <label class="campo__label" for="horario-clase">Horario</label>
                        <input class="campo__field" type="text" id="horario-clase" name="horario-clase" placeholder="Formato: (dia 1) y (dia 2), (00:00) - (00:00)" value="<?php echo $horario_clase ;?>">
                    </div>
                </fieldset>
                <div class="campo pass">
                    <input type="submit" value="Borrar" class="pass-boton">
                </div>
            </form>
        </div>
        <!-- Contenedor de GRUPO -->
        <div class="buscacion buscacion-grupo">
            <h4>Ingresa el numero del grupo</h4>
            <form method="GET" id="formActualizar">
                <input type="hidden" name="expediente" value="<?php echo $id ?>">
                <input type="text" id="busqueda-grupo" name="busqueda-grupo" placeholder="Buscar...">
                <input type="submit" value="Buscar">
            </form>
        </div>
        <div class="form-crear-grupo contenedor">
            <form class="formulario form-principales" method="POST">
                <input type="hidden" name="formulario-crear" value="Formulario_Grupo">
                <h2 class="titulo">Ingresa los datos del grupo</h2>
                <fieldset class="contenedor-contacto campo-principales">
                    <legend class="campo__legend" >Datos del Grupo</legend>
                    <?php
                    // Reiniciar las consultas que YA se habían iterado
                    $resultadoGrupos->data_seek(0);
                    $resultadoCarreras->data_seek(0);
                    $resultadoHorarios->data_seek(0);
                    ?>
                    <div class="campo">
                        <p class="indicacion-horario ">No puede modificar el numero del grupo </p>
                    </div>
                    <div class="campo">
                        <label class="campo__label" for="num-grupo">Grupo</label>
                        <input class="campo__field" type="text" id="num-grupo" name="num-grupo" placeholder="Numero del grupo" value="<?php echo $num_grupos ;?>" readonly>
                    </div>
                    <div class="campo">
                        <label class="campo__label" for="num-alumnos">Alumnos</label>
                        <input class="campo__field" type="number" id="num-alumnos" name="num-alumnos" placeholder="Numero del alumnos" value="<?php echo $num_alumnos ;?>" readonly>
                    </div>
                    <div class="campo">
                        <label class="campo__label" for="carrera-alumno">Carrera</label>
                        <select name="carrera-alumno" class="campo__field">
                            <option value="">-Seleccione-</option>
                                <?php while($carrera = mysqli_fetch_assoc($resultadoCarreras)):?>
                                    <option <?php echo $carreras === $carrera['ID_CARRERA'] ? 'selected' : '' ?> value="<?php echo $carrera['ID_CARRERA']; ?>"><?php echo $carrera['NOMBRE']; ?></option>
                                <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="campo">
                            <P class="indicacion-horario">Presiona "Ctrl" y has clics sobre los horarios que quieras elegir</P>
                        </div>
                    <div class="campo">
                        <label class="campo__label" for="horarios_grupo">Horario</label>
                        <select name="horarios_grupo[]" class="campo__horario" id="horarios_grupo" multiple>
                            <?php while($horario= mysqli_fetch_assoc($resultadoHorarios)):?>
                                <option <?php echo in_array($horario['ID_CLASE'], $horarios_grupo) ? 'selected' : '' ?> value="<?php echo $horario['ID_CLASE']; ?>" class="linea-inferior">
                                    <?php echo $horario['HORARIO']; ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                </fieldset>
                <div class="campo pass">
                    <input type="submit" value="Borrar" class="pass-boton">
                </div>
            </form>
        </div>
        <!-- Contenedor de CARRERA -->
        <div class="buscacion buscacion-carrera">
            <h4>Ingresa clave de de carrera</h4>
            <form method="GET" id="formActualizar">
                <input type="hidden" name="expediente" value="<?php echo $id ?>">
                <input type="text" id="busqueda-carrera" name="busqueda-carrera" placeholder="Buscar...">
                <input type="submit" value="Buscar">
            </form>
        </div>
        <div class="form-crear-carrera contenedor">
            <form class="formulario form-principales" method="POST">
                <input type="hidden" name="formulario-crear" value="Formulario_Carrera">
                <h2 class="titulo">Ingresa los datos de la Carrera</h2>
                <fieldset class="contenedor-contacto campo-principales">
                    <legend class="campo__legend" >Datos de la Materia</legend>
                    <div class="campo">
                        <p class="indicacion-horario ">No puede modificar el ID de la carrera </p>
                    </div>
                    <div class="campo">
                        <label class="campo__label" for="id-carrera">ID</label>
                        <input class="campo__field" type="text" id="id-carrera" name="id-carrera" value="<?php echo $id_campos ;?>" readonly>
                    </div>
                    <div class="campo">
                        <label class="campo__label" for="clave-carrera">Clave</label>
                        <input class="campo__field" type="text" id="clave-carrera" name="clave-carrera" placeholder="Clave de la carrera" value="<?php echo $clave_materia ;?>" readonly>
                    </div>
                    <div class="campo">
                        <label class="campo__label" for="nombre-carrera">Nombre</label>
                        <input class="campo__field" type="text" id="nombre-carrera" name="nombre-carrera" placeholder="Nombre de la carrera" value="<?php echo $nombre ;?>" readonly>
                    </div>
                </fieldset>
                <div class="campo pass">
                    <input type="submit" value="Borrar" class="pass-boton">
                </div>
            </form>
        </div>
        <!-- Contenedor de BLOG -->
        <div class="buscacion buscacion-blog">
            <h4>Entra al enlace del blog que quieres actualizar, copia y pega aqui la URL</h4>
            <form method="GET" id="formActualizar">
                <input type="hidden" name="expediente" value="<?php echo $id ?>">
                <input type="text" id="busqueda-blog" name="busqueda-blog" placeholder="Buscar...">
                <input type="submit" value="Buscar">
            </form>
        </div>
        <div class="form-crear-blog contenedor">
            <form class="formulario form-principales" method="POST">
                <input type="hidden" name="formulario-crear" value="Formulario_Blog">
                <h2 class="titulo">Ingresa los datos del blog</h2>
                <fieldset class="contenedor-contacto campo-principales">
                    <legend class="campo__legend" >Campos del Blog</legend>
                    <div class="campo">
                        <p class="indicacion-horario ">No puede modificar el ID del Blog </p>
                    </div>
                    <div class="campo">
                        <label class="campo__label" for="id-blog">ID</label>
                        <input class="campo__field" type="text" id="id-blog" name="id-blog" value="<?php echo $id_campos ;?>" readonly>
                    </div>
                    <div class="campo">
                        <label class="campo__label" for="titulo-blog">Titulo</label>
                        <input class="campo__field" type="text" id="titulo-blog" name="titulo-blog" placeholder="Titulo del Blog" value="<?php echo $nombre ;?>"readonly>
                    </div>
                    <div class="campo">
                        <label class="campo__label" for="descripcion-breve">Descripcion 1</label>
                        <input class="campo__field" type="text" id="descripcion-breve" name="descripcion-breve" placeholder="Coloca una descripción breve" value="<?php echo $descripcion1 ;?>"readonly>
                    </div>
                    <div class="campo">
                        <label class="campo__label" for="descripcion-detallada"readonly>Descripcion 2</label>
                        <textarea 
                        class="campo__descripcion"
                        name="descripcion-detallada" 
                        id="descricion-detallada"
                        placeholder="Coloca una descripción más detallada" 
                        value="<?php echo $descripcion2 ;?>" readonly><?php echo $descripcion2 ;?></textarea>
                    </div>
                </fieldset>
                <fieldset class="contenedor-contacto campo-principales">
                    <legend class="campo__legend" >Referencias</legend>
                    <div class="campo">
                        <label class="campo__label" for="enlace">Enlace</label>
                        <input class="campo__field" type="text" id="enlace" name="enlace" placeholder="Enlace para verlo desde una cuenta oficial" value="<?php echo $enlace ;?>" readonly>
                    </div>
                </fieldset>
                <div class="campo pass">
                    <input type="submit" value="Borrar" class="pass-boton">
                </div>
            </form>
        </div>

        <!-- ACTUALIZAR MAESTRIAS O DOCTORADOS -->
        <div class="buscacion buscacion-MD">
            <h4>Ingresa el nombre de la maestria o doctorado</h4>
            <form method="GET" id="formActualizar">
                <input type="hidden" name="expediente" value="<?php echo $id ?>">
                <input type="text" id="busqueda-MD" name="busqueda-MD" placeholder="Buscar...">
                <input type="submit" value="Buscar">
            </form>
        </div>
        <div class="form-crear-MD contenedor">
            <form class="formulario form-principales" method="POST">
                <input type="hidden" name="formulario-crear" value="Formulario_MD">
                <h2 class="titulo">Ingresa los campo de la Maestria o Doctorado</h2>
                <fieldset class="contenedor-contacto campo-principales">
                    <legend class="campo__legend" >Campos del Blog</legend>
                    <div class="campo">
                        <p class="indicacion-horario ">No puede modificar el ID de la Maestria o Doctorado </p>
                    </div>
                    <div class="campo">
                        <label class="campo__label" for="id-MD">ID</label>
                        <input class="campo__field" type="text" id="id-MD" name="id-MD" value="<?php echo $id_campos ;?>" readonly>
                    </div>
                    <div class="campo">
                        <label class="campo__label" for="titulo-MD">Titulo</label>
                        <input class="campo__field" type="text" id="titulo-MD" name="titulo-MD" placeholder="Titulo" value="<?php echo $nombre ;?>"readonly>
                    </div>
                    <div class="campo">
                        <label class="campo__label" for="descripcion-breve">Descripcion</label>
                        <input class="campo__field" type="text" id="descripcion-breve" name="descripcion-breve" placeholder="Coloca una descripción breve" value="<?php echo $descripcion1 ;?>"readonly>
                    </div>
                    <div class="campo">
                        <label class="campo__label" for="cupo">Cupo</label>
                        <input class="campo__field" type="number" id="cupo" name="cupo" placeholder="Ingresa el cupo de aspirante" value="<?php echo $cupo ;?>"readonly>
                    </div>
                </fieldset>
                <fieldset class="contenedor-contacto campo-principales">
                    <legend class="campo__legend" >Referencias</legend>
                    <div class="campo">
                        <label class="campo__label" for="facultad">Facultad</label>
                        <input class="campo__field" type="text" id="facultad" name="facultad" placeholder="Ingresa la facultad que lo imparte" value="<?php echo $facultad ;?>" readonly>
                    </div>
                    <div class="campo">
                        <label class="campo__label" for="telefono-MD">Teléfolo</label>
                        <input class="campo__field" type="number" id="telefono-MD" name="telefono-MD" placeholder="Ingresa el teléfono para solicitar informes" value="<?php echo $telefono ;?>"readonly>
                    </div>
                </fieldset>
                <div class="campo pass">
                    <input type="submit" value="Borrar" class="pass-boton">
                </div>
            </form>
        </div>

        <?php foreach ($errores as $error) : ?>
            <div class="alerta error">
                <?php echo $error; ?>
            </div>
        <?php endforeach; ?>
        <?php if(intval($exito) === 1):?>
            <p class="alerta exito">Alumno eliminado correctamente</p>
        <?php elseif(intval($exito) === 2):?>
            <p class="alerta exito">Docente eliminado correctamente</p>
        <?php elseif(intval($exito) === 3):?>
            <p class="alerta exito">Materia eliminada correctamente</p>
        <?php elseif(intval($exito) === 4):?>
            <p class="alerta exito">Clase eliminada correctamente</p>
        <?php elseif(intval($exito) === 5):?>
            <p class="alerta exito">Grupo eliminado correctamente</p>
        <?php elseif(intval($exito) === 6):?>
            <p class="alerta exito">Carrera eliminada correctamente</p> 
        <?php elseif(intval($exito) === 7):?>
            <p class="alerta exito">Blog eliminado correctamente</p> 
        <?php elseif(intval($exito) === 8):?>
            <p class="alerta exito">Maestria o Doctorado eliminado correctamente</p>  
        <?php endif;?>
    </div><br><br><br>
<?php include 'footer.php'; ?>