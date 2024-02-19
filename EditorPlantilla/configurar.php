<?php
session_start();
if (isset($_SESSION['usr']) and ($_SESSION['rol'] == 1 || $_SESSION['rol'] == 2) and $_SESSION['m']) {
    if (
        !isset($_POST['id_formato']) and !empty($_POST['id_formato']) and !isset($_POST['top']) and !isset($_POST['bottom']) and !isset($_POST['left'])
        and !isset($_POST['right']) and !isset($_POST['tamanio'])
    ) {
        $_SESSION['error'] = 'Datos incompletos';
        header("Location: formatos.php");
        exit();
    }
    require "cnx/cnx.php";
    require "historial.php";
    $id_formato = $_POST['id_formato'];
    $id_modulo = $_SESSION['m'];
    $tamanio = $_POST['tamanio'];
    $top = $_POST['top'];
    $bottom = $_POST['bottom'];
    $left = $_POST['left'];
    $right = $_POST['right'];
    // validar si el formato pertenece al modulo
    $sql_validar = sqlsrv_query($cnx, "select f.id from formatos as f inner join modulos as m
     on f.id_modulo=m.id_modulo where m.id_modulo='$id_modulo' and f.id='$id_formato'");

    if (!sqlsrv_has_rows($sql_validar)) {
        $_SESSION['error'] = 'Acceso denegado';
        header("Location: formatos.php");
        exit();
    }
    $fecha = date('Y-m-d');
    $hora = date('H:i:s');
    
    if ($tamanio != '0') {
        $update = sqlsrv_query($cnx, "UPDATE formatos set tamanio='$tamanio' where id='$id_formato'");
        
        $insert = commit($_SESSION['usr'],$id_formato, 7, '', $fecha, $hora);
    }
    // validar si cambio los margenes
    $margenes = sqlsrv_query($cnx, "select * from formatos where id='$id_formato' and [top]='$top' and [bottom]='$bottom' and [left]='$left' and [right]='$right'");
    if (!sqlsrv_has_rows($margenes)) {
        $update = sqlsrv_query($cnx, "UPDATE formatos set [top]='$top',[bottom]='$bottom',[left]='$left',[right]='$right' where id='$id_formato'");
        $insert = commit($_SESSION['usr'],$_SESSION['f'] , 6, '', $fecha, $hora);
    } 




    if (!$update) {
        $_SESSION['error'] = 'Error al actualizar el formato, comuniquese con soporte.';
        header("Location: formatos.php");
        exit();
    }
    $_SESSION['success'] = 'Formato acualizado correctamente.';
    header("Location: formatos.php");
    exit();
} else {
    echo '<meta http-equiv="refresh" content="1,url=../modulos/administrator/logout.php">';
}
