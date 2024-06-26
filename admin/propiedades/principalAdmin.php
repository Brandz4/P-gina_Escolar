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
    // Escribir el Query (la consulta)
    $query = "SELECT * FROM ADMINISTRATIVOS WHERE EXPEDIENTE_ADMIN = ".$id;
    // Consultar la DB
    $resultado = mysqli_query($db, $query);
    // Extraer los datos
    $usuario = mysqli_fetch_assoc($resultado);

    // Consulta para obtener los horarios del admin
    $consulta_horarios = "SELECT HORARIO_ADMIN FROM ADMINISTRATIVOS WHERE EXPEDIENTE_ADMIN = $id";
    $resultado_horarios = mysqli_query($db, $consulta_horarios);
    $resultado_horarios = mysqli_fetch_assoc($resultado_horarios);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil Administrador</title>
    <link rel="stylesheet" href="../../build/css/app.css">
    <style>
        .titulo-horario {
            text-align: center;
            margin: 20px 0;
        }

        .imagen-campus {
            width: 80%; /* Hacer la imagen más pequeña */
            height: auto;
            border-radius: 10px;
            display: block;
            margin: 20px auto; /* Centrar la imagen y añadir margen */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Añadir sombra */
            border: 2px solid #ddd; /* Añadir borde */
        }
    </style>
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
            <a href="crear.php?expediente=<?php echo $usuario['EXPEDIENTE_ADMIN']; ?>" class="nav-en-principal">Crear</a>
            <a href="actualizar.php?expediente=<?php echo $usuario['EXPEDIENTE_ADMIN']; ?>" class="nav-en-principal">Actualizar</a>
            <a href="borrar.php?expediente=<?php echo $usuario['EXPEDIENTE_ADMIN']; ?>" class="nav-en-principal">Borrar</a>
            <a href="principalAdmin.php?expediente=<?php echo $usuario['EXPEDIENTE_ADMIN']; ?>" class="nav-en-principal">Inicio</a>
        </nav>
    </div>
    <div class="contenedor-principales">
        <div class="titulo">
            <h1>Bienvenido</h1>
        </div>
        <div class="titulo-horario">
            <h3>Horario</h3>
        </div>
        <table class="tabla-horario contenedor">
            <thead>
                <tr>
                    <th>Lunes</th>
                    <th>Martes</th>
                    <th>Miércoles</th>
                    <th>Jueves</th>
                    <th>Viernes</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    echo "<tr>";
                    $horario_parts = explode(',', $resultado_horarios['HORARIO_ADMIN']);
                    for ($i = 0; $i < 5; $i++) {
                        echo "<td>" . $horario_parts[1] . "</td>";
                    }
                    echo "</tr>";
                ?>     
            </tbody>
        </table>
        <br>
        <?php $imagen = '../../img/campus.jpg'; ?>  
        <img class="imagen-campus" src="<?php echo $imagen; ?>" alt="Imagen del campus">  
    </div>
    <?php include 'footer.php'; ?>
</body>
</html>
