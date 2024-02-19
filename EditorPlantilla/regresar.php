<?php
session_start();
if (!isset($_SESSION['f']) || empty($_SESSION['f'])) {
    header("Location: formatos.php");
    exit();
}
if (!isset($_SESSION['m']) || empty($_SESSION['m'])) {
    header("Location: modulos.php");
    exit();
}
require "cnx/cnx.php";
require "historial.php";
$id_formato = $_SESSION['f'];
$id_modulo = $_SESSION['m'];
$timeInsert = date('Y-m-d') . ' ' . date('H:i:s');

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

//extraer el contenido del html actual y mandarlo al html de la segunda version
$contenidoHtml = file_get_contents($formatoPath . '/htmlv2.txt');
$resultado = file_put_contents($formatoPath . '/html.txt', $contenidoHtml);
$deleteVA = sqlsrv_query($cnx, "delete from formatosVA where id_formato='$id_formato'");
$fecha = date('Y-m-d');
$hora = date('H:i:s');
$insert = commit($_SESSION['usr'], $id_formato,9, '', $fecha, $hora);
header("Location: editor.php");
exit();
