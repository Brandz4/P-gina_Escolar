<?php 
    //Importa la conexion de la DB
    require 'includes/config/database.php';
    $db = conectarDB();
    //Ecribir el Query (la consulta)
    $queryBlog = "SELECT * FROM BLOG ORDER BY ID_BLOG DESC";
    $query_maestria_doctorado = "SELECT * FROM MAESTRIAS_DOCTORADOS ORDER BY ID_MD DESC";
    // Consultar la DB
    $resultadoConsultaBlog = mysqli_query($db,$queryBlog);
    $resultadoConsulta_maestria_doctorado = mysqli_query($db,$query_maestria_doctorado);
    //Importar el header
    include 'templates/header.php';
?>
    <header class="header">
        <div class="header__texto">
            <h2 class="no-margin">Universidad Autónoma de Querétaro</h2>
            <p>Educo en la Verdad y en el Honor</p>
        </div>>
        </div>
    </header>

    <div class="menu">
        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-school" width="44" height="44" viewBox="0 0 24 24" stroke-width="1.5" stroke="#2c3e50" fill="none" stroke-linecap="round" stroke-linejoin="round">
            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
            <path d="M22 9l-10 -4l-10 4l10 4l10 -4v6" />
            <path d="M6 10.6v5.4a6 3 0 0 0 12 0v-5.4" />
        </svg>
        <button onclick="toggleMenu()" class="dropbtn">Portal UAQ</button>
        <div id="myDropdown" class="dropdown-content">
          <a href="portales/portalAlumno/index.php">Portal Alumnos</a><br>
          <a href="portales/portalDocente/index.php">Portal Docentes</a>
        </div>
        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-login" width="44" height="44" viewBox="0 0 24 24" stroke-width="1.5" stroke="#2c3e50" fill="none" stroke-linecap="round" stroke-linejoin="round">
            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
            <path d="M15 8v-2a2 2 0 0 0 -2 -2h-7a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h7a2 2 0 0 0 2 -2v-2" />
            <path d="M21 12h-13l3 -3" />
            <path d="M11 15l-3 -3" />
        </svg>
    </div>
    
    <div class="contenedor contenido-principal">
        <main class="blog">
            <h3>Nuestro Blog</h3>
            <?php while ($blog = mysqli_fetch_assoc($resultadoConsultaBlog)): ?> 
                <article class="entrada">
                    <div class="entrada__imagen">
                        <picture>
                            <img loading="lazy" src="imagenes/<?php echo $blog['IMAGEN'];?>" alt="imagen blog"> 
                        </picture>
                    </div>

                    <div class="entrada__contenido">
                        <h4 class="no-margin"><?php echo $blog['TITULO'];?></h4>
                        <p><?php echo $blog['DESCRIPCION_BREVE'];?></p>
                        <a href="blog.php" class="boton boton--primario">Leer Entrada</a>
                    </div>
                </article>
            <?php endwhile;?>
        </main>
        <aside class="sidebar linea-inferior">
            <h3>Nuestras Mestrías y Doctorados</h3>

            <ul class="cursos no-padding">
                <?php while ($maestria_doctorado = mysqli_fetch_assoc($resultadoConsulta_maestria_doctorado)): ?>
                    <li class="widget-curso">
                        <h4 class="no-margin"><?php echo $maestria_doctorado['NOMBRE'];?></h4>
                        <p class="widget-curso__label">Facultad: 
                            <span class="widget-curso__info"><?php echo $maestria_doctorado['FACULTAD'];?></span>
                        </p>
                        <p class="widget-curso__label">Cupo: 
                            <span class="widget-curso__info"><?php echo $maestria_doctorado['CUPO'];?></span>
                        </p>
                        <a href="maestrias_doctorados.php" class="boton boton--secundario">Más Información</a>
                    </li>
                <?php endwhile;?>
            </ul>
        </aside>
    </div>
    <div class="contenedor contenedor-contacto" id="contacto">
        <h3 class="centrar-texto">Contacto</h3>

        <form class="formulario">
            <div class="campo">
                <label class="campo__label" for="nombre">Nombre</label>
                <input 
                    class="campo__field"
                    type="text" 
                    placeholder="Tu Nombre" 
                    id="nombre"
                >
            </div>
            <div class="campo">
                <label class="campo__label" for="email">E-mail</label>
                <input 
                    class="campo__field"
                    type="email" 
                    placeholder="Tu E-mail" 
                    id="email"
                >
            </div>
            <div class="campo">
                <label class="campo__label" for="mensaje">Mensaje</label>
                <textarea 
                    class="campo__field campo__field--textarea"
                    id="mensaje"
                ></textarea>
            </div>

            <div class="campo">
                <input type="submit" value="Enviar" class="boton boton--primario">
            </div>
        </form>
    </div>
<?php 
    include 'templates/footer.php';
?>