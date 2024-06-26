<?php
// Conexión a la base de datos
require '../../includes/config/database.php';
$db = conectarDB();

// Obtener las contraseñas actuales de la base de datos
$query = "SELECT EXPEDIENTE_ALUMNO, NIP_ALUMNO FROM ALUMNOS";
$resultado = mysqli_query($db, $query);

// Hashear cada contraseña y actualizar la base de datos
while ($usuario = mysqli_fetch_assoc($resultado)) {
    $expediente = $usuario['EXPEDIENTE_ALUMNO'];
    $nip = $usuario['NIP_ALUMNO'];
    
    // Hashear la contraseña
    $nip_hash = password_hash($nip, PASSWORD_DEFAULT);
    
    // Actualizar la base de datos con la contraseña hasheada
    $update_query = "UPDATE ALUMNOS SET NIP_ALUMNO = '$nip_hash' WHERE EXPEDIENTE_ALUMNO = '$expediente'";
    mysqli_query($db, $update_query);
}

echo "Contraseñas hasheadas exitosamente.";
?>
