<?php
session_start();
if (isset($_SESSION['usr']) and ($_SESSION['rol'] == 1)) {
    if (!isset($_SESSION['m'])) {
        header("Location: modulos.php");
        exit();
    }
    require "cnx/cnx.php";
    require "historial.php";
    $id_modulo = $_SESSION['m'];

    // Conexión a la base de datos y lógica para actualizar el estado aquí
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $estado = $_POST['estado'];
        $id_formato = $_POST['id_formato'];
        // echo $estado;
        // exit();

        // validamos que el formato si pertenece al modulo en el que se esta4
        $sql_validar = sqlsrv_query($cnx, "select f.* from formatos as f inner join modulos as m
      on f.id_modulo=m.id_modulo where m.id_modulo='$id_modulo' and f.id='$id_formato'");



        if (!sqlsrv_has_rows($sql_validar)) {
            $_SESSION['error'] = 'Acceso denegado';
            header("Location: formatos.php");
            exit();
        }
        $fecha = date('Y-m-d');
        $hora = date('H:i:s');
        // Actualizamos el estado
        $update = sqlsrv_query($cnx, "UPDATE FORMATOS SET estado='$estado' WHERE id='$id_formato'");
        $insert = commit($_SESSION['usr'], $id_formato,8, '', $fecha, $hora);


        if (!$update) {
            $_SESSION['error'] = 'Error al actualizar el estado, intente de nuevi y si el problema persiste comuniquese con soporte.';
            header("Location: formatos.php");
            exit();
        }

        // Redirige de vuelta a la página actual después de actualizar el estado
        header("Location: formatos.php");
        exit();
    }
} else {
    echo '<meta http-equiv="refresh" content="1,url=../modulos/administrator/logout.php">';
}
