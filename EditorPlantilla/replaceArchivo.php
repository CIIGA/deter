<?php
session_start();
if (!isset($_SESSION['usr']) || ($_SESSION['rol'] != 1)) {
    header("Location: ../modulos/administrator/logout.php");
}
if (!(isset($_FILES['archivo'])) || !(isset($_SESSION['m']) || isset($_POST['id_formato']) || isset($_POST['tipo']))) {
    $_SESSION['snDatos'] = 'Parámetros incompletos para continuar con el proceso';
    header("Location: formatos.php");
    exit();
}

require "cnx/cnx.php";
require "historial.php";

try {
    // Iniciar la transacción
    sqlsrv_begin_transaction($cnx);

    $id_usuario = $_SESSION['usr'];
    $id_formato = $_POST['id_formato'];
    $tipo = $_POST['tipo'];
    $archivo = $_FILES['archivo']['tmp_name'];
    $id_modulo = $_SESSION['m'];


    // Obtener el nombre del modulo en el que está, ya que así se llama la carpeta de las plantillas
    $sql_modulo = sqlsrv_query($cnx, "SELECT * FROM nombreModulos WHERE id_modulo='$id_modulo'");
    $modulo = sqlsrv_fetch_array($sql_modulo);
    $Nmodulo = $modulo['nombre'];

    // Obtener el nombre del formato en el que está, ya que así se llama la carpeta de las plantillas
    $sql_formato = sqlsrv_query($cnx, "SELECT * FROM formatos WHERE id='$id_formato'");
    $formato = sqlsrv_fetch_array($sql_formato);
    $Nformato = $formato['nombre'];

    // Ubicacion de la carpeta del modulo
    $moduloPath = 'C:/inetpub/vhosts/beautiful-einstein.51-79-98-210.plesk.page/httpdocs/deter/EditorPlantilla/plantillas/' . $Nmodulo;
    // $moduloPath = 'C:/wamp64/www/deter/EditorPlantilla/plantillas/' . $Nmodulo;
    // Directorio de la carpeta del formato
    $formatoPath = $moduloPath . '/' . $Nformato;

    if ($tipo == 'html') {
        $id_tipo='1';
        $id_edicion='2';
        $directorioArchivo = $formatoPath . '/html.txt';
    }elseif ($tipo == 'header') {
        $id_edicion='4';
        $id_tipo='3';
        $directorioArchivo = $formatoPath . '/header.jpg';
    }elseif ($tipo == 'footer') {
        $id_edicion='5';
        $id_tipo='4';
        $directorioArchivo = $formatoPath . '/footer.jpg';
    }elseif ($tipo == 'css') {
        $id_edicion='3';
        $id_tipo='2';
        $directorioArchivo = $formatoPath . '/css.txt';
    }else{
        throw new Exception('Error en el tipo de archivo a reemplazar, comuniquese con soporte.');
    }
    


    

    // Mueve el archivo cargado al archivo destino
    if (move_uploaded_file($archivo, $directorioArchivo)) {
    } else {
        throw new Exception('Error a2 al reemplasar el archivo, comuniquese con soporte.');
    }
    $fecha = date('Y-m-d');
    $hora = date('H:i:s');
    $insert = commit($_SESSION['usr'], $id_formato,$id_edicion, '', $fecha, $hora);
    // Commit de la transacción
    sqlsrv_commit($cnx);

    $_SESSION['success'] = 'Archivo '. $tipo. ' reemplasado.';
    header("Location: formatos.php");
    exit();
} catch (Exception $e) {
    // Rollback en caso de error
    sqlsrv_rollback($cnx);

    // Almacenar el mensaje de error en la sesión
    $_SESSION['error'] = $e->getMessage();

    // Redireccionar a la página de error
    header("Location: formatos.php");
    exit();
}

