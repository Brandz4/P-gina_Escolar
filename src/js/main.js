function toggleMenu() {
    var dropdownMenu = document.getElementById("myDropdown");
    dropdownMenu.classList.toggle("show");
  }
  
  // Cerrar el menú desplegable si se hace clic fuera de él
  window.onclick = function(event) {
    if (!event.target.matches('.dropbtn')) {
      var dropdowns = document.getElementsByClassName("dropdown-content");
      for (var i = 0; i < dropdowns.length; i++) {
        var openDropdown = dropdowns[i];
        if (openDropdown.classList.contains('show')) {
          openDropdown.classList.remove('show');
        }
      }
    }
  }

  //MOSTRAR LOS FORMULARIOS-PRINCIPALES
  document.addEventListener('DOMContentLoaded', function() {
    function mostrarFormulario(formClass) {
        document.querySelector(formClass).style.display = "block";
        document.querySelector('.menu-crear').style.display = "none";
    }
    document.querySelector('.menu-alumno').addEventListener('click', function() {
        mostrarFormulario('.form-crear-alumno');
    });

    document.querySelector('.menu-docente').addEventListener('click', function() {
        mostrarFormulario('.form-crear-docente');
    });

    document.querySelector('.menu-materia').addEventListener('click', function() {
        mostrarFormulario('.form-crear-materia');
    });

    document.querySelector('.menu-clase').addEventListener('click', function() {
        mostrarFormulario('.form-crear-clase');
    });

    document.querySelector('.menu-grupo').addEventListener('click', function() {
        mostrarFormulario('.form-crear-grupo');
    });
    document.querySelector('.menu-carrera').addEventListener('click', function() {
      mostrarFormulario('.form-crear-carrera');
    });
    document.querySelector('.menu-blog').addEventListener('click', function() {
      mostrarFormulario('.form-crear-blog');
    });
    document.querySelector('.menu-MD').addEventListener('click', function() {
      mostrarFormulario('.form-crear-MD');
    });
});
// MOSTRAR LOS BUSCADORES
document.addEventListener('DOMContentLoaded', function(){
  function mostrarBuscador(buscador) {
    document.querySelector(buscador).style.display = "block";
    document.querySelector('.menu-crear').style.display = "none";
  }

  document.querySelector('.menu-actualizar-alumno').addEventListener('click', function() {
    mostrarBuscador('.buscacion-alumno');
  });
  document.querySelector('.menu-actualizar-docente').addEventListener('click', function() {
    mostrarBuscador('.buscacion-docente');
  });
  document.querySelector('.menu-actualizar-materia').addEventListener('click', function() {
    mostrarBuscador('.buscacion-materia');
  });
  document.querySelector('.menu-actualizar-clase').addEventListener('click', function() {
    mostrarBuscador('.buscacion-clase');
  });
  document.querySelector('.menu-actualizar-grupo').addEventListener('click', function() {
    mostrarBuscador('.buscacion-grupo');
  });
  document.querySelector('.menu-actualizar-carrera').addEventListener('click', function() {
    mostrarBuscador('.buscacion-carrera');
  });
  document.querySelector('.menu-actualizar-blog').addEventListener('click', function() {
    mostrarBuscador('.buscacion-blog');
  });
  document.querySelector('.menu-actualizar-MD').addEventListener('click', function() {
    mostrarBuscador('.buscacion-MD');
  });
});
  