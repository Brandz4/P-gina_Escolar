<?php 
    // Importa la conexion de la DB
    require 'includes/config/database.php';
    $db = conectarDB();
    //Ecribir el Query (la consulta)
    $query_maestria_doctorado = "SELECT * FROM MAESTRIAS_DOCTORADOS ORDER BY ID_MD DESC";
    // Consultar la DB
    $resultadoConsulta_maestria_doctorado = mysqli_query($db,$query_maestria_doctorado);
    //Importar el header
    include 'templates/header.php';
?>
    <aside class="sidebar blog-principal">
        <h3>Nuestras Mestrías y Doctorados</h3>

        <ul class="cursos no-padding curso-principal">
            <?php while ($maestria_doctorado = mysqli_fetch_assoc($resultadoConsulta_maestria_doctorado)): ?>
                <li class="widget-curso campo-curso">
                    <h4 class="no-margin linea-inferior"><?php echo $maestria_doctorado['NOMBRE'];?></h4>
                    <img class="imagen-curso" loading="lazy" src="imagenes/<?php echo $maestria_doctorado['IMAGEN'];?>" alt="imagen blog">
                    <p class="widget-curso__label">Facultad: 
                        <span class="widget-curso__info"><?php echo $maestria_doctorado['FACULTAD'];?></span>
                    </p>
                    <p>
                        <?php echo $maestria_doctorado['DESCRIPCION_BREVE'];?>
                    </p>
                    <p class="widget-curso__label">Cupo: 
                        <span class="widget-curso__info"><?php echo $maestria_doctorado['CUPO'];?></span>
                    </p>
                    <p class="widget-curso__label">
                        Contacta la facutulad para inscribirte:
                        <a href="tel: <?php echo $maestria_doctorado['TELEFONO'];?>" class="widget-curso__info">
                            <?php echo $maestria_doctorado['TELEFONO'];?>
                        </a>
                    </p>
                </li>
            <?php endwhile;?>
        </ul>
    </aside>
<?php 
    include 'templates/footer.php';
?>