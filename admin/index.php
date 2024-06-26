<?php
// Importar la conexión a la base de datos
require '../includes/config/database.php';
$db = conectarDB();
// Variables que contienen los campos
$expediente = '';
$nip = '';
$errores = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $expediente = mysqli_real_escape_string($db, $_POST['expediente']);
    $nip = mysqli_real_escape_string($db, $_POST['nip']);

    // Hashear el NIP antes de compararlo con el de la base de datos
    $nip_hash = mysqli_real_escape_string($db, $_POST['nip']);

    // Preparar la consulta
    $query = "SELECT EXPEDIENTE_ADMIN, NIP_ADMIN FROM ADMINISTRATIVOS WHERE EXPEDIENTE_ADMIN = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param('s', $expediente);
    $stmt->execute();
    $stmt->bind_result($expediente_db, $nip_hash_db);
    $stmt->fetch();

    // Verificar si se encontró un usuario con el expediente proporcionado y comparar la contraseña hasheada
    if ($expediente_db && password_verify($_POST['nip'], $nip_hash_db)) {
        // Usuario autenticado, redirigir al área de miembros
        session_start();
        $_SESSION['login'] = true;
        header('Location: propiedades/principalAdmin.php?expediente='.$expediente);
        exit();
    } else {
        // Usuario no encontrado o contraseña incorrecta, mostrar mensaje de error
        $errores[] = "El usuario no existe o los datos son incorrectas";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
  <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SomosUAQ</title>
    <meta name="description" content="Página web principal de la UAQ">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans&family=PT+Sans:wght@400;700&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="../build/css/app.css">
  </head>
  <body class="body-login">
        <?php foreach($errores as $error):?>
          <div class="alerta error">
            <?php echo $error; ?>
          </div>
        <?php endforeach;?>
    <section class="login_form">
      <div class="center">
        <div class="logo-login">
          <img src="../img/escudoUAQ.jpeg" alt="Logo UAQ" /><br />
        </div>
        <div class="login">
          <div class="bienvenido">
            <h1>Portal-UAQ</h1>
            <h3>Acceso como Administrador</h3>
          </div>
          <form class="form-login" method="POST" action="index.php">
            <div class="txt_field">
              <input type="number" required name="expediente"/>
              <label>Expediente</label>
            </div>
            <div class="txt_field">
              <input type="password" required name="nip" />
              <label>NIP</label>
            </div>
            <div class="pass">
              <input type="submit" value="Ingresar" />
            </div>
          </form>
        </div>
      </div>
    </section>
  </body>
</html>