      //COMPILADOR DEL CSS//
// "src": función que sirve para identificar un archivo o una serie de archivos
// "dest": permite almacenar algo en una carpeta de archivo
// "watch": evita estar compilando manuelmente en la consola cada que se modifica el "app.scss"
const { src, dest, watch, parallel } = require("gulp")
//Importar desde otro archivo
const sass = require("gulp-sass")(require('sass'));
const plumber = require('gulp-plumber');


function css(done) {
    src("src/scss/**/*.scss") // Identificar el archivo de SASS 
        .pipe( plumber()) 
        .pipe( sass() )
        .pipe(dest("build/css")) // - Almacenarlo en el disco duro 
    done(); //Callback que avisa a gulp cuando llegamos al final
}
function dev(done) {
    watch("src/scss/**/*.scss", css)
    done();
}

//Mandar llamar las funciones
exports.css = css;
exports.dev = parallel(dev);