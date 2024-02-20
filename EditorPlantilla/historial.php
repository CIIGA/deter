<?php

function commit($id_usuario,$id_campo,$id_edicion,$comentario,$fecha,$hora){
    
require "cnx/cnx.php";
    $commit=sqlsrv_query($cnx,"INSERT INTO historial (id_usuarioNuevo,id_edicion,comentario,id_campo,fecha,hora) values 
    ('$id_usuario','$id_edicion','$comentario','$id_campo','$fecha','$hora')");

    if ($commit) {
        return 1;
    }else{
        return 0;
    }

}

