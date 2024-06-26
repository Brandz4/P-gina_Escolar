<?php 
    include 'templates/header.php';

    // Importar la conexión a la base de datos
    require 'includes/config/database.php';
    $db = conectarDB();

    // Escribir el Query (consulta)
    $query = "SELECT NOMBRE, ID_CARRERA FROM CARRERAS";

    // Consultar la base de datos
    $resultado = mysqli_query($db, $query);
?>
<main>
    <div class="contenedor contenedor-convocatorias">
        <div class="Convotatorias">
            <div class="titulo">
                <h1>Carreras</h1>
                <p>Selecciona la carrera para ver su plan de estudios</p>
            </div>
        </div>
    </div>
    <div class="campo-carreras contenedor">
        <?php while ($carrera = mysqli_fetch_assoc($resultado)): ?>
            <a href="Secundarias/carreras/carrera.php?id=<?php echo $carrera['ID_CARRERA']; ?>" class="boton boton--primario">
                <?php echo htmlspecialchars($carrera['NOMBRE']); ?>
            </a>
        <?php endwhile; ?>
    </div>
</main>
<br><br><br><br><br><br><br><br><br>
<?php 
    include 'templates/footer.php';
?>
