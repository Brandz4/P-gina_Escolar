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
    // Variables de los inputs de alumno
    $nombre = '';
    $correo = '';
    $telefono = '';
    $direccion = '';
    $expediente = '';
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
    $cupo = '';
    $facultad = '';
    $horarios_grupo = [];
    $descripcion1 = '';
    $descripcion2 = '';
    $enlace = '';

    if ($_SERVER['REQUEST_METHOD'] === 'POST'){
        if (isset($_POST['formulario-crear']) && $_POST['formulario-crear'] === 'Formulario_Alumno') {
            // Procesa el formulario de alumnos
            $nombre = mysqli_real_escape_string($db, $_POST['nombre-alumno']);
            $correo = mysqli_real_escape_string($db, $_POST['correo-alumno']);
            $telefono = mysqli_real_escape_string($db, $_POST['telefono-alumno']);
            $direccion = mysqli_real_escape_string($db, $_POST['direccion-alumno']);
            $expediente = mysqli_real_escape_string($db, $_POST['expediente-alumno']);
            $nip = mysqli_real_escape_string($db, $_POST['nip-alumno']);
            $carreras = mysqli_real_escape_string($db, $_POST['carrera-alumno']);
            $grupos = mysqli_real_escape_string($db, $_POST['grupo-alumno']);
            $semestre = mysqli_real_escape_string($db, $_POST['semestre-alumno']);
            $imagen = $_FILES['imagen-alumno'];
            //Mensajes de error
            if (!$nombre) {
                $errores[]="Debes añadir el nombre del alumno";
            }
            if (!$correo) {
                $errores[]="Debes añadir el correo del alumno";
            }
            if (!$telefono) {
                $errores[]="Debes añadir el telefono del alumno";
            }
            if (!$direccion) {
                $errores[]="Debes añadir la dirección del alumno";
            }
            if (!$expediente) {
                $errores[]="Debes añadir el expediente del alumno";
            }
            if (!$nip) {
                $errores[]="Debes añadir el NIP del alumno";
            }
            if (!$carreras) {
                $errores[]="Debes añadir la carrera del alumno";
            }
            if (!$grupos) {
                $errores[]="Debes añadir el grupo del alumno";
            }
            if (!$semestre) {
                $errores[]="Debes añadir el semestre del alumno";
            }
            if(!$imagen['name'] || $imagen['error']){
                $errores[]="La imagen es obligatoria";
            }
            //Validar por tamaño (100 kb máximo)
            $medida = 1000 * 1000;
    
            if ($imagen['size'] > $medida) {
                $errores[] = 'La imagen es muy pesada, el maximo de la imagen es de 1000KB';
            }

            if (empty($errores)){

                //Subir la imagen a la campera de imagenes
                // 1. creación de la carpeta
                $carpetaImagenes = '../../imagenes/';
                if (!is_dir($carpetaImagenes)) {
                    mkdir($carpetaImagenes);   
                }
                //Generar un nombre Unico
                $nombreImagen = md5(uniqid(rand(),true)) . ".jpg";
                //Subir la imagen
                move_uploaded_file($imagen['tmp_name'],$carpetaImagenes . $nombreImagen);
                //hashear el nip
                $nip_hashed = hasheaded($nip);
                $queryAlumno = "INSERT INTO ALUMNOS(
                    EXPEDIENTE_ALUMNO,
                    NIP_ALUMNO,
                    NOMBRE_ALUMNO,
                    ID_CARRERA,
                    SEMESTRE,
                    NUM_GRUPO,
                    CORREO_ALUMNO,
                    NUMERO_CONTACTO,
                    DIRECCION_ALUMNO,
                    FOTO_ALUMNO) 
                    VALUES (
                        $expediente,
                        '$nip_hashed',
                        '$nombre',
                        $carreras,
                        $semestre,
                        $grupos,
                        '$correo',
                        '$telefono',
                        '$direccion',
                        '$nombreImagen');";

                $resultadoAlumnos = mysqli_query($db,$queryAlumno);
                $exito = 1;
            }
        } elseif (isset($_POST['formulario-crear']) && $_POST['formulario-crear'] === 'Formulario_Docente') {
            // Procesa el formulario de docentes
            $nombre = mysqli_real_escape_string($db, $_POST['nombre-docente']);
            $correo = mysqli_real_escape_string($db, $_POST['correo-docente']);
            $telefono = mysqli_real_escape_string($db, $_POST['telefono-docente']);
            $direccion = mysqli_real_escape_string($db, $_POST['direccion-docente']);
            $expediente = mysqli_real_escape_string($db, $_POST['expediente-docente']);
            $nip = mysqli_real_escape_string($db, $_POST['nip-docente']);
            $empleo = mysqli_real_escape_string($db, $_POST['empleo-docente']);
            $imagen = $_FILES['imagen-docente'];
            $horarios_grupo = $_POST['horarios_grupo'];
        
            if (!empty($horarios_grupo)) {
                //Convertir el horario a string
                $delimiter = ", ";
                $stringHorario = implode($delimiter, $horarios_grupo);
            }
            //Mensajes de error
            if (!$nombre) {
                $errores[]="Debes añadir el nombre del docente";
            }
            if (!$correo) {
                $errores[]="Debes añadir el correo del docente";
            }
            if (!$telefono) {
                $errores[]="Debes añadir el telefono del docente";
            }
            if (!$direccion) {
                $errores[]="Debes añadir la dirección del docente";
            }
            if (!$expediente) {
                $errores[]="Debes añadir el expediente del docente";
            }
            if (!$nip) {
                $errores[]="Debes añadir el NIP del docente";
            }
            if (!$empleo) {
                $errores[]="Debes añadir el tipo de empleo del docente";
            }
            if (empty($horarios_grupo)) {
                $errores[]="Debes añadir el horario del docente";
            }
            if(!$imagen['name'] || $imagen['error']){
                $errores[]="La imagen es obligatoria";
            }
            //Validar por tamaño (100 kb máximo)
            $medida = 1000 * 1000;
    
            if ($imagen['size'] > $medida) {
                $errores[] = 'La imagen es muy pesada, el maximo de la imagen es de 1000KB';
            } 
            
            if (empty($errores)){

                //Subir la imagen a la campera de imagenes
                // 1. creación de la carpeta
                $carpetaImagenes = '../../imagenes/';
                if (!is_dir($carpetaImagenes)) {
                    mkdir($carpetaImagenes);   
                }
                //Generar un nombre Unico
                $nombreImagen = md5(uniqid(rand(),true)) . ".jpg";
                //Subir la imagen
                move_uploaded_file($imagen['tmp_name'],$carpetaImagenes . $nombreImagen);
                //hashear el nip
                $nip_hashed = hasheaded($nip);

                $queryDocente = "INSERT INTO DOCENTES(
                    EXPEDIENTE_DOCENTE,
                    NIP_DOCENTE,
                    NOMBRE_DOCENTE,
                    TIPO_EMPLEO,
                    CORREO_DOCENTE,
                    NUMERO_CONTACTO,
                    DIRECCION_DOCENTE,
                    HORARIO_DOCENTE,
                    FOTO_DOCENTE
                    ) 
                    VALUES (
                        $expediente,
                        '$nip_hashed',
                        '$nombre',
                        '$empleo',
                        '$correo',
                        '$telefono',
                        '$direccion',
                        '$stringHorario',
                        '$nombreImagen');";

                $resultadoDocentes = mysqli_query($db,$queryDocente);
                $exito = 2;
            }
        }elseif (isset($_POST['formulario-crear']) && $_POST['formulario-crear'] === 'Formulario_Materia') {
            $nombre = mysqli_real_escape_string($db, $_POST['nombre-materia']);
            $clave_materia = mysqli_real_escape_string($db, $_POST['clave-materia']);
            $semestre = mysqli_real_escape_string($db, $_POST['semestre-materia']);

            if (!$nombre) {
                $errores[]="Debes añadir el nombre de la materia";
            }
            if(!$clave_materia){
                $errores[]="La clave de la materia es obligatoria";
            }
            if(!$semestre){
                $errores[]="Debes añadir el semestre de la materia";
            }

            if (empty($errores)){
                $queryMateria = "INSERT INTO MATERIAS(
                    CLAVE_MATERIA,
                    NOMBRE_MATERIA,
                    SEMESTRE,
                    FECHA_CREACION) 
                    VALUES (
                        $clave_materia,
                        '$nombre',
                        $semestre,
                        '$creacion');";
    
                $resultadoMateria = mysqli_query($db,$queryMateria);
                $exito = 3;
            }
        }elseif (isset($_POST['formulario-crear']) && $_POST['formulario-crear'] === 'Formulario_Clase'){
            $carreras = mysqli_real_escape_string($db, $_POST['carrera-alumno']);
            $grupos = mysqli_real_escape_string($db, $_POST['grupo-alumno']);
            $materias = mysqli_real_escape_string($db, $_POST['clase-materia']);
            $expediente = mysqli_real_escape_string($db, $_POST['expediente-docente']);
            $salon = mysqli_real_escape_string($db, $_POST['salon-materia']);
            $horario_clase = mysqli_real_escape_string($db, $_POST['horario-clase']);

            if (!$carreras) {
                $errores[]="Debes seleccionar la carrera de la clase";
            }
            if(!$grupos){
                $errores[]="Debes seleccionar el grupo de la clase";
            }
            if(!$materias){
                $errores[]="Debes seleccionar la materia de la clase";
            }
            if (!$expediente) {
                $errores[]="Debes añadir el expediente del docente";
            }
            if(!$salon){
                $errores[]="Debes añadir el salon de la clase";
            }
            if(!$horario_clase){
                $errores[]="Debes el horario de la clase";
            }

            if (empty($errores)){
                $queryClase = "INSERT INTO CLASES(
                    NUM_GRUPO,
                    ID_CARRERA,
                    CLAVE_MATERIA,
                    EXPEDIENTE_DOCENTE,
                    SALON,
                    HORARIO) 
                    VALUES (
                        $grupos,
                        $carreras,
                        $materias,
                        $expediente,
                        '$salon',
                        '$horario_clase');";
    
                $resultadoClase = mysqli_query($db,$queryClase);
                $exito = 4;
            }
        }elseif (isset($_POST['formulario-crear']) && $_POST['formulario-crear'] === 'Formulario_Grupo'){
            $num_grupos = mysqli_real_escape_string($db, $_POST['num-grupo']);
            $num_alumnos = mysqli_real_escape_string($db, $_POST['num-alumnos']);
            $carreras = mysqli_real_escape_string($db, $_POST['carrera-alumno']);
            $horarios_grupo = $_POST['horarios_grupo'];
            //Convertir el horario a string
            $delimiter = ", ";
            $stringHorario = implode($delimiter, $horarios_grupo);

            if(!$num_grupos){
                $errores[]="Debes añadir el numero de identificacion del grupo";
            }
            if(!$num_alumnos){
                $errores[]="Debes añadir el numero de alumnos del grupo";
            }
            if (!$carreras) {
                $errores[]="Debes seleccionar la carrera del grupo";
            }
            
            if (!$stringHorario) {
                $errores[]="Debes añadir el horario del grupo";
            }
            
            if (empty($errores)){
                $queryGrupo = "INSERT INTO GRUPOS(
                    NUM_GRUPO,
                    ID_CARRERA,
                    TOTAL_ALUMNOS,
                    HORARIO) 
                    VALUES (
                        $num_grupos,
                        $carreras,
                        $num_alumnos,
                        '$stringHorario');";
    
                $resultadoGrupo = mysqli_query($db,$queryGrupo);
                $exito = 5;
            }
        }elseif (isset($_POST['formulario-crear']) && $_POST['formulario-crear'] === 'Formulario_Carrera'){
            $nombre = mysqli_real_escape_string($db, $_POST['nombre-carrera']);
            $clave_materia = mysqli_real_escape_string($db, $_POST['clave-carrera']);
            if (!$nombre) {
                $errores[]="Debes añadir el nombre de la materia";
            }
            if(!$clave_materia){
                $errores[]="La clave de la materia es obligatoria";
            }

            if (empty($errores)){
                $queryCarrera = "INSERT INTO CARRERAS(
                    CLAVE_CARRERA,
                    NOMBRE) 
                    VALUES (
                        '$clave_materia',
                        '$nombre');";
    
                $resultadoCarrera = mysqli_query($db,$queryCarrera);
                $exito = 6;
            }
        }elseif (isset($_POST['formulario-crear']) && $_POST['formulario-crear'] === 'Formulario_Blog'){
            $nombre = mysqli_real_escape_string($db, $_POST['titulo-blog']);
            $descripcion1 = mysqli_real_escape_string($db, $_POST['descripcion-breve']);
            $descripcion2 = mysqli_real_escape_string($db, $_POST['descripcion-detallada']);
            $enlace = mysqli_real_escape_string($db, $_POST['enlace']);
            $imagen = $_FILES['imagen-blog'];

            if (!$nombre) {
                $errores[]="Debes añadir un título";
            }
            if (!$descripcion1) {
                $errores[]="Debes añadir una descripción breve";
            }
            if (strlen($descripcion2) < 50) {
                $errores[]="La descripción debe de tener al menos 50 carácteres";
            }
            if (!$enlace) {
                $errores[]="Debes añadir un enlace";
            }
            if(!$imagen['name'] || $imagen['error']){
                $errores[]="La imagen es obligatoria";
            }
            //Validar por tamaño (100 kb máximo)
            $medida = 1000 * 1000;
    
            if ($imagen['size'] > $medida) {
                $errores[] = 'La imagen es muy pesada, el maximo de la imagen es de 1000KB';
            }

            if (empty($errores)){
                //Subir la imagen a la campeta de imagenes
                // 1. creación de la carpeta
                $carpetaImagenes = '../../imagenes/';
                if (!is_dir($carpetaImagenes)) {
                    mkdir($carpetaImagenes);   
                }
                //Generar un nombre Unico
                $nombreImagen = md5(uniqid(rand(),true)) . ".jpg";
                //Subir la imagen
                move_uploaded_file($imagen['tmp_name'],$carpetaImagenes . $nombreImagen);
                $queryBlog = "INSERT INTO BLOG (
                    TITULO,
                    DESCRIPCION_BREVE,
                    DESCRIPCION_DETALLADA,
                    ENLACE,
                    IMAGEN,
                    FECHA)
                    VALUES(
                        '$nombre',
                        '$descripcion1',
                        '$descripcion2',
                        '$enlace',
                        '$nombreImagen',
                        '$creacion')";
                $resultadoBlog = mysqli_query($db,$queryBlog);
                $exito = 7;
            }
        }elseif (isset($_POST['formulario-crear']) && $_POST['formulario-crear'] === 'Formulario_MD'){
            $nombre = mysqli_real_escape_string($db, $_POST['titulo-MD']);
            $descripcion1 = mysqli_real_escape_string($db, $_POST['descripcion-breve']);
            $cupo = mysqli_real_escape_string($db, $_POST['cupo']);
            $facultad = mysqli_real_escape_string($db, $_POST['facultad']);
            $telefono = mysqli_real_escape_string($db, $_POST['telefono-MD']);
            $imagen = $_FILES['imagen-MD'];

            if (!$nombre) {
                $errores[]="Debes añadir un título";
            }
            if (!$descripcion1) {
                $errores[]="Debes añadir una descripción breve";
            }
            if (!$cupo) {
                $errores[]="Debes añadir el cupo de aspirantes";
            }
            if (!$facultad) {
                $errores[]="Debes añadir la facultad que lo imparte";
            }
            if (!$telefono) {
                $errores[]="Debes añadir un telefono de referencia";
            }
            if(!$imagen['name'] || $imagen['error']){
                $errores[]="La imagen es obligatoria";
            }
            //Validar por tamaño (100 kb máximo)
            $medida = 1000 * 1000;
    
            if ($imagen['size'] > $medida) {
                $errores[] = 'La imagen es muy pesada, el maximo de la imagen es de 1000KB';
            }

            if (empty($errores)){
                //Subir la imagen a la campeta de imagenes
                // 1. creación de la carpeta
                $carpetaImagenes = '../../imagenes/';
                if (!is_dir($carpetaImagenes)) {
                    mkdir($carpetaImagenes);   
                }
                //Generar un nombre Unico
                $nombreImagen = md5(uniqid(rand(),true)) . ".jpg";
                //Subir la imagen
                move_uploaded_file($imagen['tmp_name'],$carpetaImagenes . $nombreImagen);
                $queryMD = "INSERT INTO MAESTRIAS_DOCTORADOS(
                    NOMBRE,
                    DESCRIPCION_BREVE,
                    CUPO,
                    FACULTAD,
                    TELEFONO,
                    IMAGEN)
                    VALUES(
                        '$nombre',
                        '$descripcion1',
                        $cupo,
                        '$facultad',
                        $telefono,
                        '$nombreImagen'
                    );";
                $resultadoMD = mysqli_query($db,$queryMD);
                $exito = 8;
            }
        }
    
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil Administrador</title>

    <link rel="stylesheet" href="../../build/css/app.css">
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
            <h1>Crear</h1>
        </div>
        <br><br>
        <div class="campos-crud menu-crear">
            <div class="campo-crud">
                <button class="boton botones-crud menu-alumno">Alumno</button>
            </div>
            <div class="campo-crud">
                <button class="boton botones-crud menu-docente">Docente</button>
            </div>
            <div class="campo-crud">
                <button class="boton botones-crud menu-materia">Materia</button>
            </div>
            <div class="campo-crud">
                <button class="boton botones-crud menu-clase">Clase</button>
            </div>
            <div class="campo-crud">
                <button class="boton botones-crud menu-grupo">Grupo</button>
            </div>
            <div class="campo-crud">
                <button class="boton botones-crud menu-carrera">Carrera</button>
            </div>
            <div class="campo-crud">
                <button class="boton botones-crud menu-blog">Blog</button>
            </div>
            <div class="campo-crud">
                <button class="boton botones-crud menu-MD">Maestria-Doctorado</button>
            </div>
        </div>

        <div class="form-crear-alumno contenedor">
            <form class="formulario form-principales" method="POST" enctype="multipart/form-data">
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
                        <input class="campo__field" type="text" id="correo-alumno" name="correo-alumno" placeholder="Correo del alumno" value="<?php echo $correo ;?>">
                    </div>
                    <div class="campo">
                        <label class="campo__label" for="telefono-alumno">Teléfono</label>
                        <input class="campo__field" type="number" id="telefono-alumno" name="telefono-alumno" placeholder="Telefono del alumno" value="<?php echo $telefono ;?>">
                    </div>
                    <div class="campo">
                        <label class="campo__label" for="direccion-alumno">Dirección</label>
                        <input class="campo__field" type="text" id="direccion-alumno" name="direccion-alumno" placeholder="Direccion del alumno" value="<?php echo $direccion ;?>">
                    </div>
                    <div class="campo">
                        <label for="imagen-alumno" class="campo__label">Imagen:</label>
                        <input class="campo__imagen campo__field" type="file" id="imagen-alumno" accept="image/jpeg, image/png" name="imagen-alumno">
                    </div>
                </fieldset>
                <fieldset class="contenedor-contacto campo-principales">
                    <legend class="campo__legend">Datos Acádemicos</legend>
                    <div class="campo">
                        <label class="campo__label" for="expediente-alumno">Expediente</label>
                        <input class="campo__field" type="number" id="expediente-alumno" name="expediente-alumno" placeholder="Expediente del alumno" value="<?php echo $expediente ;?>">
                    </div>
                    <div class="campo">
                        <label class="campo__label" for="nip-alumno">NIP</label>
                        <input class="campo__field" type="number" id="nip-alumno" name="nip-alumno" placeholder="NIP del alumno" value="<?php echo $nip ;?>">
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
                        <label class="campo__label" for="semestre-alumno">Semestre</label>
                        <input class="campo__field" type="number" id="semestre-alumno" name="semestre-alumno" placeholder="Semestre del alumno" value="<?php echo $semestre ;?>">
                    </div>
                </fieldset>
                <div class="pass">
                    <input type="submit" value="Crear" class="pass-boton">
                </div>
            </form>
        </div>

        <div class="form-crear-docente contenedor">
            <form class="formulario form-principales" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="formulario-crear" value="Formulario_Docente">
                <h2 class="titulo">Ingresa los datos del Docente</h2>
                <fieldset class="contenedor-contacto campo-principales">
                    <legend class="campo__legend" >Datos Personales</legend>
                    <div class="campo">
                        <label class="campo__label" for="nombre-docente">Nombre</label>
                        <input class="campo__field" type="text" id="nombre-docente" name="nombre-docente" placeholder="Nombre del docente" value="<?php echo $nombre ;?>">
                    </div>
                    <div class="campo">
                        <label class="campo__label" for="correo-docente">Correo</label>
                        <input class="campo__field" type="text" id="corree-docente" name="correo-docente" placeholder="Correo del docente" value="<?php echo $correo ;?>">
                    </div>
                    <div class="campo">
                        <label class="campo__label" for="telefono-docente">Teléfono</label>
                        <input class="campo__field" type="number" id="telefone-docente" name="telefono-docente" placeholder="Telefono del docente" value="<?php echo $telefono ;?>">
                    </div>
                    <div class="campo">
                        <label class="campo__label" for="direccion-docente">Dirección</label>
                        <input class="campo__field" type="text" id="direccioe-docente" name="direccion-docente" placeholder="Direccion del docente" value="<?php echo $direccion ;?>">
                    </div>
                    <div class="campo">
                        <label for="imagen-docente" class="campo__label">Imagen:</label>
                        <input class="campo__imagen campo__field" type="file" id="imagen-docente" accept="image/jpeg, image/png" name="imagen-docente">
                    </div>
                </fieldset>
                <fieldset class="contenedor-contacto campo-principales">
                    <legend class="campo__legend">Datos Acádemicos</legend>
                    <div class="campo">
                        <label class="campo__label" for="expediente-docente">Expediente</label>
                        <input class="campo__field" type="number" id="expediente-docente" name="expediente-docente" placeholder="Expediente del docente" value="<?php echo $expediente ;?>">
                    </div>
                    <div class="campo">
                        <label class="campo__label" for="nip-docente">NIP</label>
                        <input class="campo__field" type="number" id="nip-docente" name="nip-docente" placeholder="NIP del docente" value="<?php echo $nip ;?>">
                    </div>
                    <div class="campo">
                                    <label class="campo__label" for="empleo-docente">Estatus</label>
                                    <select name="empleo-docente" class="campo__field">
                                        <option value="">-Seleccione-</option>
                                        <option <?php echo $empleo === 'Tiempo completo' ? 'selected' : '' ?>  value="Tiempo completo">Tiempo completo</option>
                                        <option <?php echo $empleo === 'Medio tiempo' ? 'selected' : '' ?>  value="Medio tiempo">Medio tiempo</option>
                                    </select>
                                </div>
                    <div class="campo">
                            <P class="indicacion-horario">Presiona "Ctrl" y has clics sobre los horarios que quieras elegir</P>
                        </div>
                    <div class="campo">
                        <label class="campo__label" for="horarios_grupo">Horario</label>
                        <select name="horarios_grupo[]" class="campo__horario" id="horarios_grupo" multiple>
                            <?php while($horario= mysqli_fetch_assoc($resultadoHorarios)):?>
                                <option <?php echo $horarios === $horario['ID_CLASE'] ? 'selected' : '' ?> value="<?php echo $horario['ID_CLASE']; ?>" class="linea-inferior">
                                    <?php echo $horario['HORARIO']; ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                </fieldset>
                <div class="campo pass">
                    <input type="submit" value="Crear" class="pass-boton">
                </div>
            </form>
        </div>

        <div class="form-crear-materia contenedor">
            <form class="formulario form-principales" method="POST">
                <input type="hidden" name="formulario-crear" value="Formulario_Materia">
                <h2 class="titulo">Ingresa los datos de la Materia</h2>
                <fieldset class="contenedor-contacto campo-principales">
                    <legend class="campo__legend" >Datos de la Materia</legend>
                    <div class="campo">
                        <label class="campo__label" for="clave-materia">Clave</label>
                        <input class="campo__field" type="number" id="clave-materia" name="clave-materia" placeholder="Clave de la materia" value="<?php echo $clave_materia ;?>">
                    </div>
                    <div class="campo">
                        <label class="campo__label" for="nombre-materia">Nombre</label>
                        <input class="campo__field" type="text" id="nombre-materia" name="nombre-materia" placeholder="Nombre de la materia" value="<?php echo $nombre ;?>">
                    </div>
                    <div class="campo">
                        <label class="campo__label" for="semestre-materia">Semestre</label>
                        <input class="campo__field" type="number" id="semestre-materia" name="semestre-materia" placeholder="Semestre de la materia" value="<?php echo $semestre ;?>">
                    </div>
                </fieldset>
                <div class="campo pass">
                    <input type="submit" value="Crear" class="pass-boton">
                </div>
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
                        <label class="campo__label" for="clase-materia">Materia</label>
                        <select name="clase-materia" class="campo__field">
                            <option value="">-Seleccione-</option>
                                <?php while($materia = mysqli_fetch_assoc($resultadoMaterias)):?>
                                    <option <?php echo $materias === $materia['CLAVE_MATEIRA'] ? 'selected' : '' ?> value="<?php echo $materia['CLAVE_MATERIA']; ?>"><?php echo $materia['NOMBRE_MATERIA']; ?></option>
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
                        <input class="campo__field" type="number" id="expediente-docente" name="expediente-docente" placeholder="Expediente del docente que dará la materia" value="<?php echo $expediente ;?>">
                    </div>
                    <div class="campo">
                        <label class="campo__label" for="salon-materia">Salon</label>
                        <input class="campo__field" type="text" id="salon-materia" name="salon-materia" placeholder="Salon donde se impartirá la materia" value="<?php echo $salon ;?>">
                    </div>
                    <div class="campo">
                        <label class="campo__label" for="horario-clase">Horario</label>
                        <input class="campo__field" type="text" id="horario-clase" name="horario-clase" placeholder="Formato: (dia 1) y (dia 2), (00:00) - (00:00)" value="<?php echo $horario_clase ;?>">
                    </div>
                </fieldset>
                <div class="campo pass">
                    <input type="submit" value="Crear" class="pass-boton">
                </div>
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
                        <label class="campo__label" for="num-grupo">Grupo</label>
                        <input class="campo__field" type="number" id="num-grupo" name="num-grupo" placeholder="Numero del grupo" value="<?php echo $num_grupos ;?>">
                    </div>
                    <div class="campo">
                        <label class="campo__label" for="num-alumnos">Alumnos</label>
                        <input class="campo__field" type="number" id="num-alumnos" name="num-alumnos" placeholder="Numero del alumnos" value="<?php echo $num_alumnos ;?>">
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
                                <option <?php echo $horarios_grupo === $horario['ID_CLASE'] ? 'selected' : '' ?> value="<?php echo $horario['ID_CLASE']; ?>" class="linea-inferior">
                                    <?php echo $horario['HORARIO']; ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                </fieldset>
                <div class="campo pass">
                    <input type="submit" value="Crear" class="pass-boton">
                </div>
            </form>
        </div>
        <div class="form-crear-carrera contenedor">
            <form class="formulario form-principales" method="POST">
                <input type="hidden" name="formulario-crear" value="Formulario_Carrera">
                <h2 class="titulo">Ingresa los datos de la Carrera</h2>
                <fieldset class="contenedor-contacto campo-principales">
                    <legend class="campo__legend" >Datos de la Materia</legend>
                    <div class="campo">
                        <label class="campo__label" for="clave-carrera">Clave</label>
                        <input class="campo__field" type="text" id="clave-carrera" name="clave-carrera" placeholder="Clave de la carrera" value="<?php echo $clave_materia ;?>">
                    </div>
                    <div class="campo">
                        <label class="campo__label" for="nombre-carrera">Nombre</label>
                        <input class="campo__field" type="text" id="nombre-carrera" name="nombre-carrera" placeholder="Nombre de la carrera" value="<?php echo $nombre ;?>">
                    </div>
                </fieldset>
                <div class="campo pass">
                    <input type="submit" value="Crear" class="pass-boton">
                </div>
            </form>
        </div>
        <div class="form-crear-blog contenedor">
            <form class="formulario form-principales" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="formulario-crear" value="Formulario_Blog">
                <h2 class="titulo">Ingresa los datos del blog</h2>
                <fieldset class="contenedor-contacto campo-principales">
                    <legend class="campo__legend" >Campos del Blog</legend>
                    <div class="campo">
                        <label class="campo__label" for="titulo-blog">Titulo</label>
                        <input class="campo__field" type="text" id="titulo-blog" name="titulo-blog" placeholder="Titulo del Blog" value="<?php echo $nombre ;?>">
                    </div>
                    <div class="campo">
                        <label class="campo__label" for="descripcion-breve">Descripcion 1</label>
                        <input class="campo__field" type="text" id="descripcion-breve" name="descripcion-breve" placeholder="Coloca una descripción breve" value="<?php echo $descripcion1 ;?>">
                    </div>
                    <div class="campo">
                        <label class="campo__label" for="descripcion-detallada">Descripcion 2</label>
                        <textarea 
                        class="campo__descripcion"
                        name="descripcion-detallada" 
                        id="descricion-detallada"
                        placeholder="Coloca una descripción más detallada" 
                        value="<?php echo $descripcion2 ;?>"><?php echo $descripcion2 ;?></textarea>
                    </div>
                </fieldset>
                <fieldset class="contenedor-contacto campo-principales">
                    <legend class="campo__legend" >Referencias</legend>
                    <div class="campo">
                        <label class="campo__label" for="enlace">Enlace</label>
                        <input class="campo__field" type="text" id="enlace" name="enlace" placeholder="Enlace para verlo desde una cuenta oficial" value="<?php echo $enlace ;?>">
                    </div>
                    <div class="campo">
                        <label for="imagen-blog" class="campo__label">Imagen:</label>
                        <input class="campo__imagen campo__field" type="file" id="imagen-blog" accept="image/jpeg, image/png" name="imagen-blog">
                    </div>
                </fieldset>
                <div class="campo pass">
                    <input type="submit" value="Crear" class="pass-boton">
                </div>
            </form>
        </div>
        <div class="form-crear-MD contenedor">
            <form class="formulario form-principales" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="formulario-crear" value="Formulario_MD">
                    <h2 class="titulo">Ingresa los campo de la Maestria o Doctorado</h2>
                    <fieldset class="contenedor-contacto campo-principales">
                        <legend class="campo__legend" >Campos del Blog</legend>
                        <div class="campo">
                            <label class="campo__label" for="titulo-MD">Titulo</label>
                            <input class="campo__field" type="text" id="titulo-MD" name="titulo-MD" placeholder="Titulo" value="<?php echo $nombre ;?>">
                        </div>
                        <div class="campo">
                            <label class="campo__label" for="descripcion-breve">Descripcion</label>
                            <input class="campo__field" type="text" id="descripcion-breve" name="descripcion-breve" placeholder="Coloca una descripción breve" value="<?php echo $descripcion1 ;?>">
                        </div>
                        <div class="campo">
                            <label class="campo__label" for="cupo">Cupo</label>
                            <input class="campo__field" type="number" id="cupo" name="cupo" placeholder="Ingresa el cupo de aspirante" value="<?php echo $cupo ;?>">
                        </div>
                    </fieldset>
                    <fieldset class="contenedor-contacto campo-principales">
                        <legend class="campo__legend" >Referencias</legend>
                        <div class="campo">
                            <label class="campo__label" for="facultad">Facultad</label>
                            <input class="campo__field" type="text" id="facultad" name="facultad" placeholder="Ingresa la facultad que lo imparte" value="<?php echo $facultad ;?>">
                        </div>
                        <div class="campo">
                            <label class="campo__label" for="telefono-MD">Teléfolo</label>
                            <input class="campo__field" type="number" id="telefono-MD" name="telefono-MD" placeholder="Ingresa el teléfono para solicitar informes" value="<?php echo $telefono ;?>">
                        </div>
                        <div class="campo">
                            <label for="imagen-MD" class="campo__label">Imagen:</label>
                            <input class="campo__imagen campo__field" type="file" id="imagen-MD" accept="image/jpeg, image/png" name="imagen-MD">
                        </div>
                    </fieldset>
                    <div class="campo pass">
                        <input type="submit" value="Crear" class="pass-boton">
                    </div>
                </form>
        </div>

        <?php foreach ($errores as $error) : ?>
            <div class="alerta error">
                <?php echo $error; ?>
            </div>
        <?php endforeach; ?>
        <?php if(intval($exito) === 1):?>
            <p class="alerta exito">Alumno creado correctamente</p>
        <?php elseif(intval($exito) === 2):?>
            <p class="alerta exito">Docente creado correctamente</p>
        <?php elseif(intval($exito) === 3):?>
            <p class="alerta exito">Materia creada correctamente</p>
        <?php elseif(intval($exito) === 4):?>
            <p class="alerta exito">Clase creada correctamente</p>
        <?php elseif(intval($exito) === 5):?>
            <p class="alerta exito">Grupo creado correctamente</p>
        <?php elseif(intval($exito) === 6):?>
            <p class="alerta exito">Carrera creada correctamente</p> 
        <?php elseif(intval($exito) === 7):?>
            <p class="alerta exito">Blog creado correctamente</p> 
        <?php elseif(intval($exito) === 8):?>
            <p class="alerta exito">Maestria o Doctorado creado correctamente</p>  
        <?php endif;?>

    </div><br><br><br>
<?php include 'footer.php'; ?>