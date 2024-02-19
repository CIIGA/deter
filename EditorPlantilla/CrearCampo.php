<?php
session_start();
if (!(isset($_POST['nombre'])) || !(isset($_SESSION['m'])) || !(isset($_SESSION['f']))) {
    $_SESSION['snDatos'] = 'Parámetros incompletos para continuar con el proceso';
    header("Location: campos.php");
    exit();
}

require "cnx/cnx.php";

$nombre = $_POST['nombre'];
$descripcion = $_POST['descripcion'];
$id_modulo = $_SESSION['m'];
$id_formato = $_SESSION['f'];


// Validar si no existe un campo con el mismo nombre en este formato
$sql_campo = sqlsrv_query($cnx, "SELECT c.id FROM formatos AS f INNER JOIN campos AS c ON f.id = c.id_formato WHERE f.id = '$id_formato' and c.campo='$nombre'");

if (sqlsrv_has_rows($sql_campo)) {
    $_SESSION['error'] = 'Este campo ya existe en este formato.';
    header("Location: campos.php");
    exit();
}
$sql_insert="INSERT INTO campos (campo,descripcion,id_formato) values ('$nombre','$descripcion','$id_formato')";
// echo $sql_insert;
// exit();
//insertar el formato
$insert = sqlsrv_query($cnx, $sql_insert);

if (!$insert) {
    $_SESSION['error'] = 'Error al agregar el campo, comuniquese con seporte.';
    header("Location: campos.php");
    exit();
}
$_SESSION['success'] = 'Campo agregado correctamente.';
    header("Location: campos.php");
    exit();