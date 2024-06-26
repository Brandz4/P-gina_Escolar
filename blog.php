<?php 
    // Importa la conexion de la DB
    require 'includes/config/database.php';
    $db = conectarDB();
    //Ecribir el Query (la consulta)
    $queryBlog = "SELECT * FROM BLOG ORDER BY ID_BLOG DESC";
    // Consultar la DB
    $resultadoConsultaBlog = mysqli_query($db,$queryBlog);
    //Importar el header
    include 'templates/header.php';
?>
    <main class="blog-principal blog-grid">
        <div>
            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-calendar-check" width="44" height="44" viewBox="0 0 24 24" stroke-width="1.5" stroke="#2c3e50" fill="none" stroke-linecap="round" stroke-linejoin="round">
                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                <path d="M11.5 21h-5.5a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v6" />
                <path d="M16 3v4" />
                <path d="M8 3v4" />
                <path d="M4 11h16" />
                <path d="M15 19l2 2l4 -4" />
            </svg>
            <h3>Nuestro Blog</h3>
            <img src="img/escudoUAQ.jpeg" alt="Escudo UAQ" class="logo-footer imagen-escudo">
        </div>
            <?php while ($blog = mysqli_fetch_assoc($resultadoConsultaBlog)): ?> 
                <div class="entrada-blog linea-inferior">
                    <article">
                        <div class="entrada__imagen">
                            <picture>
                                <img loading="lazy" src="imagenes/<?php echo $blog['IMAGEN'];?>" alt="imagen blog"> 
                            </picture>
                        </div>

                        <div class="entrada__contenido">
                            <h4 class="no-margin"><?php echo $blog['TITULO'];?></h4>
                            <p><?php echo $blog['DESCRIPCION_DETALLADA'];?></p>
                        </div>
                        <a class="entrada__enlace" href="<?php echo $blog['ENLACE'];?>">Visita nuestra publicación  en Facebook</a>
                    </article>
                </div>
            <?php endwhile;?>
    </main>
<?php 
    include 'templates/footer.php';
?>