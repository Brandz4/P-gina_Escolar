<?php
    function hasheaded($nip){
        $niphash = password_hash($nip, PASSWORD_DEFAULT);
        return $niphash;
    }
    function ocultarMenu($formulario){
        echo "<style>.menu-crear{ display: none; }.".$formulario." { display: block; }</style>";
    }
    function consultaBusqueda($tabla,$columna,$identificador){
        if (is_int($identificador)) {
            $query = "SELECT * FROM ".$tabla."  WHERE ".$columna." = ".$identificador;
        }elseif(is_string($identificador)){
            $query = "SELECT * FROM ".$tabla."  WHERE ".$columna." = '${identificador}'";
        }
        return $query;
    }
?>