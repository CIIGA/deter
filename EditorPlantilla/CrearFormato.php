<?php
session_start();
if (!(isset($_FILES['html'])) || !(isset($_POST['nombre'])) || !(isset($_POST['tamanio'])) || !(isset($_FILES['css'])) || 
!(isset($_FILES['header'])) || !(isset($_FILES['footer'])) || !(isset($_SESSION['m'])) || !(isset($_POST['top']))
|| !(isset($_POST['bottom'])) || !(isset($_POST['left'])) || !(isset($_POST['right']))) {
    $_SESSION['snDatos'] = 'Parámetros incompletos para continuar con el proceso';
    header("Location: formatos.php");
    exit();
}

require "cnx/cnx.php";

try {
    // Iniciar la transacción
    sqlsrv_begin_transaction($cnx);

    $nombre = $_POST['nombre'];
    $top = $_POST['top'];
    $bottom = $_POST['bottom'];
    $left = $_POST['left'];
    $right = $_POST['right'];
    $tamanio = $_POST['tamanio'];
    $html = $_FILES['html']['tmp_name'];
    $css = $_FILES['css']['tmp_name'];
    $header = $_FILES['header']['tmp_name'];
    $footer = $_FILES['footer']['tmp_name'];
    $id_modulo = $_SESSION['m'];

    // Validar si no existe un formato con el mismo nombre en este modulo
    $sql_formato = sqlsrv_query($cnx, "SELECT f.id FROM modulos AS m INNER JOIN formatos AS f ON m.id_modulo = f.id_modulo WHERE m.id_modulo = '$id_modulo' and f.nombre='$nombre'");

    if (sqlsrv_has_rows($sql_formato)) {
        throw new Exception('El nombre del formato introducido ya existe en este modulo');
    }

    // Obtener el nombre del modulo en el que está, ya que así se llama la carpeta de las plantillas
    $sql_modulo = sqlsrv_query($cnx, "SELECT * FROM nombreModulos WHERE id_modulo='$id_modulo'");
    $modulo = sqlsrv_fetch_array($sql_modulo);
    $Nmodulo = $modulo['nombre'];

    // Ubicacion de la carpeta del modulo
    $moduloPath = 'C:/inetpub/vhosts/beautiful-einstein.51-79-98-210.plesk.page/httpdocs/deter/EditorPlantilla/plantillas/' . $Nmodulo;
    // $moduloPath = 'C:/wamp64/www/deter/EditorPlantilla/plantillas/' . $Nmodulo;
    // Directorio de la carpeta del formato
    $formatoPath = $moduloPath . '/' . $nombre;



    // Crear una carpeta del formato
    if (!mkdir($formatoPath, 0755)) {
        throw new Exception('Error al crear el formato, comuniquese con soporte.');
    }

    $directorioHtml = $formatoPath . '/html.txt';
    $directorioCss = $formatoPath . '/css.txt';
    $directorioHeader = $formatoPath . '/header.jpg';
    $directorioFooter = $formatoPath . '/footer.jpg';


    // Cargar los archivos
    cargar($directorioHtml, $html);
    cargar($directorioCss, $css);
    cargar($directorioHeader, $header);
    cargar($directorioFooter, $footer);

    $urlHtml='https://'.$_SERVER['HTTP_HOST'].'/deter/EditorPlantilla/plantillas/'.$Nmodulo.'/'.$nombre.'/html.txt';
    $urlCss='https://'.$_SERVER['HTTP_HOST'].'/deter/EditorPlantilla/plantillas/'.$Nmodulo.'/'.$nombre.'/css.txt';
    $urlHeader='https://'.$_SERVER['HTTP_HOST'].'/deter/EditorPlantilla/plantillas/'.$Nmodulo.'/'.$nombre.'/header.jpg';
    $urlFooter='https://'.$_SERVER['HTTP_HOST'].'/deter/EditorPlantilla/plantillas/'.$Nmodulo.'/'.$nombre.'/footer.jpg';

    // $urlHtml='http://'.$_SERVER['HTTP_HOST'].'/deter/EditorPlantilla/plantillas/'.$Nmodulo.'/'.$nombre.'/html.txt';
    // $urlCss='http://'.$_SERVER['HTTP_HOST'].'/deter/EditorPlantilla/plantillas/'.$Nmodulo.'/'.$nombre.'/css.txt';
    // $urlHeader='http://'.$_SERVER['HTTP_HOST'].'/deter/EditorPlantilla/plantillas/'.$Nmodulo.'/'.$nombre.'/header.jpg';
    // $urlFooter='http://'.$_SERVER['HTTP_HOST'].'/deter/EditorPlantilla/plantillas/'.$Nmodulo.'/'.$nombre.'/footer.jpg';



    $timeInsert = date('Y-m-d') . ' ' . date('H:i:s');

    // Cargar los datos a la base de datos
    $sql_insert = sqlsrv_query($cnx, "INSERT INTO formatos(nombre,fechaC,html,css,header,footer,[top],bottom,[left],[right],id_modulo,tamanio,estado) 
    VALUES ('$nombre','$timeInsert','$urlHtml','$urlCss','$urlHeader','$urlFooter','$top','$bottom','$left','$right','$id_modulo','$tamanio','1')");

    if (!($sql_insert)) {
        throw new Exception('Error al insertar el formato, comuniquese con soporte.');
    }


    $sql_formato_inserted = sqlsrv_query($cnx, "select id from formatos where nombre='$nombre' and id_modulo='$id_modulo'");
    $formato_inserted = sqlsrv_fetch_array($sql_formato_inserted);
    $id_formato = $formato_inserted['id'];
    //crear archivo php del pdf
    $nombreArchivoTxt = 'plantillaPdf.txt';
    $rutaPhp = $formatoPath . '/';

    //crear el archivo de segunda version
    $nombreArchivoPHP = 'htmlv2.txt';
    $v2 = touch($rutaPhp . $nombreArchivoPHP);
    if (!$v2) {
        throw new Exception('Error un archivo, comuniquese con soporte.');
    }
    // Commit de la transacción
    sqlsrv_commit($cnx);

    $_SESSION['success'] = 'Formato creado correctamente.';
    header("Location: formatos.php");
    exit();
} catch (Exception $e) {
    // Rollback en caso de error
    sqlsrv_rollback($cnx);

    // Eliminar la carpeta del formato y sus archivos
    if (isset($formatoPath) && is_dir($formatoPath)) {
        eliminarDirectorio($formatoPath);
    }

    // Almacenar el mensaje de error en la sesión
    $_SESSION['error'] = $e->getMessage();

    // Redireccionar a la página de error
    header("Location: formatos.php");
    exit();
}

// Función para cargar los archivos del formato
function cargar($directorio, $archivo)
{
    move_uploaded_file($archivo, $directorio);
}

// Función para eliminar un directorio y su contenido recursivamente
function eliminarDirectorio($directorio)
{
    $archivos = glob($directorio . '/*');
    foreach ($archivos as $archivo) {
        is_dir($archivo) ? eliminarDirectorio($archivo) : unlink($archivo);
    }
    rmdir($directorio);
}
