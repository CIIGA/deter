<?php
session_start();
if((isset($_SESSION['usr'])) and (isset($_SESSION['rol']))){
require "../../acnxerdm/cnxAd.php";

//*****************************baja usuario dcf*********************************************/
if(isset($_GET['poneUsr'])){
    require "../../EditorPlantilla/historial.php";
    $fecha = date('Y-m-d');
    $hora = date('H:i:s');
    
    $idusr=$_GET['tkn'];
    $insert = commit($_SESSION['usr'],  $idusr,13, '', $fecha, $hora);
    $state="update usuarioNuevo set estado=0 where id_usuarioNuevo=$idusr";
    sqlsrv_query($cnx,$state) or die ('No se ejecuto la consulta update usuarioNuevo');
    echo '<meta http-equiv="refresh" content="0,url=usrs.php">';
}
//***************************fin baja usuario dcf*******************************************/
//*****************************alta usuario dcf*********************************************/
if(isset($_GET['reUsr'])){
    require "../../EditorPlantilla/historial.php";
    $fecha = date('Y-m-d');
    $hora = date('H:i:s');
    $idusr=$_GET['tkn'];
    $insert = commit($_SESSION['usr'],  $idusr,13, '', $fecha, $hora);
    $state="update usuarioNuevo set estado=1 where id_usuarioNuevo=$idusr";
    sqlsrv_query($cnx,$state) or die ('No se ejecuto la consulta update usuarioNuevo');
    echo '<meta http-equiv="refresh" content="0,url=usrs.php">';
}
//***************************fin alta usuario dcf*******************************************/
//****************************************************************************************
if(isset($_GET['poneorigen'])){
    $idorigen=$_GET['origen'];
    
    $delaccess="DELETE FROM proveniente WHERE id_proveniente='$idorigen'";
    sqlsrv_query($cnx,$delaccess);
        echo '<script> alert("Resgistro origen de datos Eliminado.")</script>';
    echo '<meta http-equiv="refresh" content="0,url=origen.php">';
}
//****************************************************************************************
//****************************************************************************************
if(isset($_GET['poneplz'])){
    require "../../EditorPlantilla/historial.php";
    $idplz=$_GET['plz'];
    $estado=$_GET['poneplz'];
    
    $delaccess="update modulos set estado='$estado' WHERE id_modulo='$idplz'";
    sqlsrv_query($cnx,$delaccess);
    $fecha = date('Y-m-d');
    $hora = date('H:i:s');
    $insert = commit($_SESSION['usr'], $idplz,12, '', $fecha, $hora);
        echo '<script> alert("Estado del modulo actualizado.")</script>';
    echo '<meta http-equiv="refresh" content="0,url=addplz.php">';
}
//****************************************************************************************
//****************************************************************************************
if(isset($_GET['poneacces'])){
    $id_acceso=$_GET['acces'];
    $usr=$_GET['usr'];
    
    $delaccess="DELETE FROM acceso WHERE id_acceso='$id_acceso'";
    sqlsrv_query($cnx,$delaccess);
    
    echo '<meta http-equiv="refresh" content="0,url=permisoPlz.php?usr='.$usr.'&plz=65&crhm=950721&idus=659898895">';
}
//****************************************************************************************












} else{
    echo '<meta http-equiv="refresh" content="0,url=logout.php">';
} ?>